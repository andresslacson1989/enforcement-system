<?php

namespace App\Http\Controllers\pages;

use App\Http\Classes\UserClass;
use App\Http\Requests\CompleteProfileRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Detachment;
use App\Models\Submission;
use App\Models\Suspension;
use App\Models\User;
use App\Traits\ImageUploadTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use LaravelIdea\Helper\App\Models\_IH_User_QB;
use Spatie\Permission\Models\Role;
use Spatie\Tags\Tag;

class UsersController
{
    use ImageUploadTrait;

    public function profile(string $id = 'my-profile')
    {
        $personnel = ($id == 'my-profile') ? Auth::user() : User::findOrFail($id);
        // The policy will handle the authorization check.
        if (Gate::denies('viewOwnPersonnelProfile', $personnel)) {
            return view('content.pages.pages-misc-error');
        }

        // If the check passes, continue with the rest of the method.
        $notifications = $personnel->notifications;
        $forms = (new Submission)->getSubmittedForms($personnel->id);
        $personnel_roles = (new UserClass)->listPersonnelRoles();
        $roles = Role::whereIn('name', $personnel_roles)->get();

        // Fetch all existing tags to populate the dropdown
        $all_tags = Tag::all();

        $telegram_linking_url = null;
        if (! $personnel->telegram_chat_id) {
            // Generate a unique token for Telegram linking only if not already linked.
            $telegram_token = Str::random(32);
            // Store the token in the cache, linking it to the user's ID for 10 minutes.
            Cache::put('telegram_token:'.$telegram_token, $personnel->id, now()->addMinutes(10));

            // Generate the Telegram linking URL
            $bot_username = config('telegram.username');
            $telegram_linking_url = "https://t.me/{$bot_username}?start={$telegram_token}";
        }

        return view('content.pages.profile') // Changed to snake_case
            ->with('user', $personnel)
            ->with('notifications', $notifications)
            ->with('detachment', $personnel->detachment)
            ->with('forms', $forms)
            ->with('roles', $roles)
            ->with('all_tags', $all_tags)
            ->with('telegram_linking_url', $telegram_linking_url);
    }

    /**
     * Fetch a single user's data for the edit modal.
     */
    public function show(string $id): JsonResponse // This is our standard method for fetching user JSON
    {

        $user = User::with(['detachment'])->find($id);

        if ($user) {
            return response()->json($user);
        }

        return response()->json(['error' => 'User not found'], 404);
    }

    public function store(StoreEmployeeRequest $request): JsonResponse // Uses Form Request for validation
    {
        try {
            $validated = $request->validated();
            $validated['name'] = trim($validated['first_name'].' '.$validated['last_name'].' '.($validated['suffix'] ?? ''));
            $validated['password'] = $validated['password'] ? Hash::make($validated['password']) : Hash::make('esiai'.$validated['employee_number']);

            $staff = User::create($validated);
            $staff->assignRole(Role::findById($validated['primary_role_id']));
            $staff->setPrimaryRole($validated['primary_role_id']);

            return response()->json(['message' => 'Success', 'text' => 'Staff added successfully!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function delete(string $id): JsonResponse
    {
        auth()->user()->can(config('permit.delete personnel.name')); // Use Laravel's built-in authorization
        $personnel = User::findOrFail($id);
        $personnel->delete();

        return response()->json(['message' => 'Success', 'text' => 'Personnel was deleted successfully!']);
    }

    public function remove(string $id): JsonResponse
    {
        auth()->user()->can(config('permit.remove personnel.name')); // Use Laravel's built-in authorization
        $personnel = User::findOrFail($id);

        $personnel->detachment_id = null;
        $personnel->save();

        return response()->json(['message' => 'Success', 'text' => 'Personnel was deleted successfully!']);
    }

    /**
     * @return JsonResponse
     *
     * Suspension will notify the admins.
     */
    public function suspend(Request $request): JsonResponse
    {
        auth()->user()->can(config('permit.suspend personnel.name'));
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string|in:preventive,disciplinary',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        $user = User::findOrFail($validated['user_id']);

        $user->status = 'suspended'; // Standardized status
        $user->save();

        Suspension::create([
            'user_id' => $user->id,
            'type' => $request->type ?? 'preventive',
            'reason' => $request->reason,
            'start_date' => $request->start_date ?? now(),
            'end_date' => $request->end_date ?? null,
            'suspended_by' => Auth::id(),
        ]);

        return response()->json(['text' => 'User has been suspended.']);
    }

    public function unsuspend(Request $request): JsonResponse
    {
        $user = User::findOrFail($request->user_id);
        $suspension = Suspension::where('user_id', $user->id)->whereNull('end_date')->latest()->first();
        if ($suspension) {
            $suspension->end_date = now();
            $suspension->save();
        }
        $user->status = 'hired';
        $user->save();

        return response()->json(['text' => 'User has been unsuspended.']);
    }

    public function update(UpdateEmployeeRequest $request, string $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $validated_data = $request->validated();

        // Prepare data for update, starting with the validated fields.
        $update_data = $validated_data;

        // Conditionally reconstruct the full 'name' if any name part is being updated.
        if (isset($validated_data['first_name']) || isset($validated_data['last_name'])) {
            $first_name = $validated_data['first_name'] ?? $user->first_name;
            $last_name = $validated_data['last_name'] ?? $user->last_name;
            $suffix = $validated_data['suffix'] ?? $user->suffix;
            $update_data['name'] = trim($first_name.' '.$last_name.' '.$suffix);
        }

        // Conditionally hash the password if a new one is provided.
        if (! empty($validated_data['password'])) {
            $update_data['password'] = Hash::make($validated_data['password']);
        } else {
            unset($update_data['password']);
        }

        $user->update($update_data);

        // Conditionally update the user's role if it was part of the request.
        if (isset($validated_data['primary_role_id'])) {
            $user->syncRoles(Role::findById($validated_data['primary_role_id'])->name);
            $user->setPrimaryRole($validated_data['primary_role_id']);
        }

        // The frontend JS handles the specific success message.
        return response()->json(['message' => 'Success', 'icon' => 'success'], 200);
    }

    // Helper functions to determine badge colors
    private function getRoleColor(string $role_name): string
    {
        return match (strtolower($role_name)) {
            'hr manager', 'president', 'general manager' => 'bg-label-danger',
            'accounting manager', 'operation manager' => 'bg-label-warning',
            'detachment commander', 'officer in charge' => 'bg-label-info',
            default => 'bg-label-secondary'
        };
    }

    private function getStatusColor(string $status): string
    {
        return match ($status) {
            'hired' => 'bg-label-success',
            'floating' => 'bg-label-warning',
            'on_leave' => 'bg-label-info',
            'resigned', 'preventive_suspension' => 'bg-label-danger',
            default => 'bg-label-secondary'
        };
    }

    /**
     * Display the staffs page with summary stats and roles for filtering.
     */
    public function staffs_index(): View
    {
        if (! auth()->user()->can(config('permit.view staffs.name'))) {
            return view('content.pages.pages-misc-error');
        }

        // Calculate statistics for the summary cards
        $user_class = (new UserClass);
        $staff_roles = $user_class->listStaffRoles();
        $stats = User::whereHas('roles', function ($q) use ($staff_roles) {
            $q->whereIn('name', $staff_roles);
        })->selectRaw("count(case when status in ('hired', 're_hired') then 1 end) as hired")
            ->selectRaw("count(case when status = 'floating' then 1 end) as floating")
            ->selectRaw("count(case when status = 'on_leave' then 1 end) as on_leave")
            ->selectRaw("count(case when status = 'suspended' then 1 end) as suspended")
            ->selectRaw("count(case when status = 'resigned' then 1 end) as resigned")
            ->selectRaw('count(*) as total')
            ->first()
            ->toArray();

        // Fetch all roles to populate the filter dropdown
        $roles = Role::whereIn('name', $staff_roles)->get();

        return view('content.pages.staffs', compact('stats', 'roles'));
    }

    /**
     * Display a listing of the personnel.
     */
    public function personnel_index(): View
    {
        if (! auth()->user()->can(config('permit.view personnel.name'))) {
            return view('content.pages.pages-misc-error');
        }
        // Define the roles that are considered "staff"
        $user_class = (new UserClass);
        $personnel_roles = $user_class->listPersonnelRoles();
        $roles = Role::whereIn('name', $personnel_roles)->get();

        // Fetch all users who DO NOT have any of the staff roles
        // and eager-load their primary role and detachment in ONE query.
        $personnel = User::WhereHas('roles', function ($query) use ($personnel_roles) {
            $query->whereIn('name', $personnel_roles);
        })
            ->orWhere(function ($query) {
                $query->doesntHave('roles');
            })
            ->with(['primaryRole', 'detachment'])
            ->get(); // Eager load the collection in one go

        // Now, work with the retrieved collection to get the stats.
        // This is much faster as it avoids hitting the database multiple times.
        $stats = [
            'total' => $personnel->count(),
            'hired' => $personnel->whereIn('status', ['hired', 're_hired'])->count(),
            'floating' => $personnel->where('status', 'floating')->count(),
            'suspended' => $personnel->where('status', 'suspended')->count(),
        ];

        // Fetch all detachments to populate the modal dropdown
        $detachments = Detachment::all();

        // Return the view and pass the collection and stats to it.
        return view('content.pages.personnel')
            ->with('personnel', $personnel)
            ->with('stats', $stats)
            ->with('roles', $roles)
            ->with('detachments', $detachments);
    }

    public function staffsTable(Request $request): JsonResponse
    {

        // ## 1. Get DataTables parameters
        $draw = $request->input('draw');
        $start = $request->input('start');
        $rowperpage = $request->input('length');
        $order_arr = $request->input('order');
        $search_arr = $request->input('search');
        $search_value = $search_arr['value'];

        // ## 2. Build the base query with JOINS
        $user_class = (new UserClass);
        $personnel_roles = $user_class->listStaffRoles();
        $query = User::query()
            ->where('users.id', '!=', Auth::id())
            ->whereHas('roles', function ($q) use ($personnel_roles) {
                $q->whereIn('name', $personnel_roles);
            })
            ->leftJoin('roles', 'users.primary_role_id', '=', 'roles.id')
            ->leftJoin('detachments', 'users.detachment_id', '=', 'detachments.id')
            ->where('roles.name', '!=', 'root')
            ->select('users.*', 'roles.name as role_name', 'detachments.name as detachment_name');

        // Total records
        return $this->usersTable($personnel_roles, $search_value, $query, $request, $order_arr, $start, $rowperpage, $draw);
    }

    public function personnelTable(Request $request)
    {

        // ## 1. Get DataTables parameters
        $draw = $request->input('draw');
        $start = $request->input('start');
        $rowperpage = $request->input('length');
        $order_arr = $request->input('order');
        $search_arr = $request->input('search');
        $search_value = $search_arr['value'];

        // ## 2. Build the base query with JOINS
        $user_class = (new UserClass);
        $personnel_roles = $user_class->listPersonnelRoles();
        $query = User::query()
            ->where('users.id', '!=', Auth::id())
            ->whereHas('roles', function ($q) use ($personnel_roles) {
                $q->whereIn('name', $personnel_roles);
            })
            ->leftJoin('roles', 'users.primary_role_id', '=', 'roles.id')
            ->leftJoin('detachments', 'users.detachment_id', '=', 'detachments.id')
            ->where('roles.name', '!=', 'root')
            ->select('users.*', 'roles.name as role_name', 'detachments.name as detachment_name');

        // Total records
        return $this->usersTable($personnel_roles, $search_value, $query, $request, $order_arr, $start, $rowperpage, $draw);
    }

    public function detachmentPersonnelTable(Request $request)
    {

        $detachment_id = $request->input('detachment_id');

        if (Gate::denies('viewDetachmentPersonnel', Detachment::findOrFail($detachment_id))) {
            return [];
        }

        // ## 1. Get DataTables parameters
        $draw = $request->input('draw');
        $start = $request->input('start');
        $rowperpage = $request->input('length');
        $order_arr = $request->input('order');
        $search_arr = $request->input('search');
        $search_value = $search_arr['value'];

        // ## 2. Build the base query with JOINS
        $user_class = (new UserClass);
        $personnel_roles = $user_class->listPersonnelRoles();
        $query = User::query()
            ->where('users.detachment_id', $detachment_id)
            ->where('users.id', '!=', Auth::id())
            ->whereHas('roles', function ($q) use ($personnel_roles) {
                $q->whereIn('name', $personnel_roles);
            })
            ->leftJoin('roles', 'users.primary_role_id', '=', 'roles.id')
            ->leftJoin('detachments', 'users.detachment_id', '=', 'detachments.id')
            ->where('roles.name', '!=', 'root')
            ->select('users.*', 'roles.name as role_name', 'detachments.name as detachment_name');

        // Total records
        return $this->usersTable($personnel_roles, $search_value, $query, $request, $order_arr, $start, $rowperpage, $draw);
    }

    public function usersTable(array $personnel_roles, mixed $search_value, _IH_User_QB|Builder $query, Request $request, mixed $order_arr, mixed $start, mixed $rowperpage, mixed $draw): JsonResponse
    {
        $totalRecords = User::whereHas('roles', function ($q) use ($personnel_roles) {
            return $q->whereIn('name', $personnel_roles);
        })->count();

        // ## 3. Apply Filters
        if (! empty($search_value)) {
            $query->where(function ($q) use ($search_value) {
                $q->where('users.name', 'like', '%'.$search_value.'%')
                    ->orWhere('users.employee_number', 'like', '%'.$search_value.'%')
                    ->orWhere('detachments.name', 'like', '%'.$search_value.'%');
            });
        }
        if ($request->filled('status_filter')) {
            $query->where('users.status', $request->status_filter);
        }
        if ($request->filled('role_filter')) {
            $query->where('roles.name', $request->role_filter);
        }

        $total_records_with_filter = $query->count();

        // ## 4. Apply Sorting
        if (! empty($order_arr)) {
            $column_name_arr = $request->input('columns');
            $column_index = $order_arr[0]['column'];
            $column_name = $column_name_arr[$column_index]['data'];
            $column_sort_order = $order_arr[0]['dir'];
            $column_map = [
                'name' => 'users.name',
                'status' => 'users.status',
                'detachment' => 'detachment_name',
                'role' => 'role_name',
            ];
            if (isset($column_map[$column_name])) {
                $query->orderBy($column_map[$column_name], $column_sort_order);
            }
        }

        // ## 5. Apply Pagination and Fetch
        $records = $query->skip($start)
            ->take($rowperpage)
            ->get();

        // ## 6. Format the data for the response
        $data_arr = [];
        foreach ($records as $record) {
            $role_name = $record->role_name ? ucwords($record->role_name) : 'N/A';
            $role_color = $this->getRoleColor($role_name);
            $status_color = $this->getStatusColor($record->status);

            // ** ICON LOGIC UPDATED HERE **
            $suspend_action = $record->status === 'suspended'
              ? '<a href="javascript:;" class="dropdown-item unsuspend-user" data-user-id="'.$record->id.'"><i class="icon-base ti tabler-user-check me-1"></i>Unsuspend</a>'
              : '<a href="javascript:;" class="dropdown-item suspend-user" data-user-id="'.$record->id.'"><i class="icon-base ti tabler-user-off me-1"></i>Suspend</a>';

            $remove_action = $record->detachment?->name != null
              ? '<a href="javascript:;" class="dropdown-item text-warning remove-user" data-detachment-name="'.$record->detachment->name.'" data-detachment-id="'.$record->detachment->id.'" data-user-id="'.$record->id.'"><i class="icon-base ti tabler-user-x me-1"></i>Remove</a>'
              : '';

            $data_arr[] = [
                'name' => '<a href="/user/profile/'.$record->id.'" class="list-group-item list-group-item-action d-flex align-items-center">
                            <img src="'.$record->profile_photo_url.'" alt="User Image" class="rounded-circle me-4 w-px-50 img-thumbnail">
                            <div class="w-100">
                              <div class="d-flex justify-content-between">
                                <div class="user-info">
                                  <h6 class="mb-1">'.$record->name.'</h6>
                                  <small class="text-muted">'.$record->phone_number.'</small> <br>
                                  <small class="text-muted">#'.$record->employee_number.'</small>
                                  <div class="user-status">
                                    <span class="badge badge-dot bg-success"></span>
                                    <small>Online</small>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </a>',
                'role' => '<span class="badge '.$role_color.'">'.$role_name.'</span>',
                'detachment' => $record->detachment ? $record->detachment->name : '<span class="text-muted">Unassigned</span>',
                'status' => '<span class="badge '.$status_color.'">'.ucwords(str_replace('_', ' ', $record->status)).'</span>',
                'action' => '
                <div class="d-inline-block">
                    <a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="icon-base ti tabler-dots-vertical"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end m-0">
                        <a href="/user/profile/'.$record->id.'" class="dropdown-item"><i class="icon-base ti tabler-user-circle me-1"></i>View Profile</a>
                        <a href="javascript:;" class="dropdown-item edit-user" data-user-id="'.$record->id.'"><i class="icon-base ti tabler-edit me-1"></i>Edit</a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:;" class="dropdown-item change-role-btn"
                           data-user-name="'.$record->name.'"
                           data-user-id="'.$record->id.'"
                           data-current-role-id="'.$record->primary_role_id.'">
                           <i class="icon-base ti tabler-user-cog me-1"></i>Change Role
                        </a>
                        '.$suspend_action.'
                        <div class="dropdown-divider"></div>
                        '.$remove_action.'
                        <a href="javascript:;" class="dropdown-item text-danger delete-user" data-user-id="'.$record->id.'"><i class="icon-base ti tabler-trash me-1"></i>Delete</a>
                    </div>
                </div>',
            ];
        }

        // ## 7. Send the final JSON response
        $response = [
            'draw' => intval($draw),
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $total_records_with_filter,
            'aaData' => $data_arr,
        ];

        return response()->json($response);
    }

    public function showCompletionForm(): View
    {
        $user = Auth::user();

        // This view will be created in the response.
        return view('content.pages.profile-completion', compact('user'));
    }

    public function completeProfile(CompleteProfileRequest $request): JsonResponse
    {
        $user = Auth::user();
        $validated_data = $request->validated();

        // Also update the main 'name' field for consistency.
        $validated_data['name'] = trim($validated_data['first_name'].' '.$validated_data['last_name']);

        $user->update($validated_data);

        return response()->json([
            'redirect_url' => route('form-library'),
            'text' => 'Profile completed successfully! Welcome.',
        ]);
    }

    /**
     * Update the user's profile photo.
     */
    public function updateProfilePhoto(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'photo' => ['required', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // Find the user whose profile is being updated, not necessarily the logged-in user.
        $user = User::findOrFail($request->user_id);

        // Process and store the compressed image using our trait
        $path = $this->processAndStoreFile($request->file('photo'), 'profile-photos', 80);

        // Delete the old photo if it exists
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->forceFill(['profile_photo_path' => $path])->save();

        return response()->json(['profile_photo_url' => $user->profile_photo_url]);
    }
}
