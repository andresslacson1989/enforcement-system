<?php

namespace App\Http\Controllers\pages;

use App\Http\Classes\FormClass;
use App\Http\Classes\UserClass;
use App\Http\Requests\StoreDetachmentRequest;
use App\Jobs\SendAndBroadcastNotification;
use App\Models\Detachment;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class DetachmentController
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        if (! Auth::user()->can(config('permit.view detachment.name'))) {
            return view('content.pages.pages-misc-error');
        }
        $detachments = Detachment::all();
        $personnel_roles = (new UserClass)->listPersonnelRoles();
        $officers = User::whereHas('roles', function ($query) use ($personnel_roles) {
            $query->whereIn('name', $personnel_roles);
        })->get();

        $stats = Detachment::selectRaw('count(case when category = "Large Detachment" then 1 end) as large_detachment')
            ->selectRaw('count(case when category = "Medium Detachment" then 1 end) as medium_detachment')
            ->selectRaw('count(case when category = "Small Team" then 1 end) as small_team')
            ->selectRaw('count(case when category = "Single Post" then 1 end) as single_post')
            ->selectRaw('count(case when category is null then 1 end) as empty')
            ->selectRaw('count(*) as total')
            ->first()
            ->toArray();

        return view('content.pages.detachments')
            ->with('detachments', $detachments)
            ->with('officers', $officers)
            ->with('stats', $stats);
    }

    public function detachmentTable(Request $request)
    {
        if (! Auth::user()->can(config('permit.view detachment.name'))) {
            return response('Unauthorized.', 401);
        }
        // The column map remains mostly the same
        $columnMap = [
            'name' => 'detachments.name',
            'commander_name' => 'commanders.name', // Use the table alias
            'users_count' => 'users_count',
            'city' => 'detachments.city',
        ];

        $draw = $request->input('draw');
        $start = $request->input('start');
        $rowperpage = $request->input('length');
        $searchValue = $request->input('search.value');

        // Build the base query
        $query = Detachment::query()
          // Join the users table with an alias to avoid conflicts
            ->leftJoin('users as commanders', function ($join) {
                $join->on('detachments.assigned_officer', '=', 'commanders.id')
                    ->whereNull('commanders.deleted_at');
            })
          // Select all detachment columns, and the commander's name
            ->select('detachments.*', 'commanders.name as commander_name')
          // Now, attach the user count. Eloquent is smart enough to handle this.
            ->withCount(['users' => function ($query) {
                $query->whereNull('users.deleted_at');
            }]);

        // Total records count (from the base table)
        $totalRecords = Detachment::count();

        // Apply search filter
        if (! empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('detachments.name', 'like', '%'.$searchValue.'%')
                    ->orWhere('detachments.city', 'like', '%'.$searchValue.'%')
                  // Search using the alias
                    ->orWhere('commanders.name', 'like', '%'.$searchValue.'%');
            });
        }

        $totalRecordswithFilter = $query->count();

        // Apply sorting
        $sortOrder = $request->input('order');
        if ($sortOrder) {
            $frontendColumnIndex = $sortOrder[0]['column'];
            $columnDirection = $sortOrder[0]['dir'];
            $frontendColumnName = $request->input('columns')[$frontendColumnIndex]['data'];

            if (isset($columnMap[$frontendColumnName])) {
                $dbColumnName = $columnMap[$frontendColumnName];
                $query->orderBy($dbColumnName, $columnDirection);
            }
        } else {
            $query->orderBy('detachments.created_at', 'desc');
        }

        // Apply pagination
        $records = $query->skip($start)
            ->take($rowperpage)
            ->get();

        // Format the data (no changes needed here from our last fix)
        $data_arr = [];
        foreach ($records as $record) {
            $status = '';
            if ($record->status == 'pending') {
                $status = '<small class="text-warning">Pending</small>';
            }
            $data_arr[] = [
                'name' => '<div class="d-flex flex-column"><a href="/detachments/view/'.$record->id.'">'.$record->name.'</a>'.$status.'</div> ',
                'commander_name' => $record->commander_name,
                'users_count' => $record->users_count,
                'city' => $record->city,
                'action' => '',
            ];
        }

        // Prepare the response
        $response = [
            'draw' => intval($draw),
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalRecordswithFilter,
            'aaData' => $data_arr,
        ];

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDetachmentRequest $request)
    {
        $user = Auth::user();
        if (! $user->can(config('permit.add detachment.name'))) {
            return response('Unauthorized.', 401);
        }
        // The request is already validated by StoreDetachmentRequest.
        // If validation fails, Laravel automatically redirects back with errors.

        // Retrieve the validated input data...
        $validatedData = $request->validated();

        // Create and save the new detachment
        try {
            $detachment = Detachment::create($validatedData);
            $assigned_officer = User::find($detachment->assigned_officer);
            $assigned_officer->assignRole('assigned officer');
            $assigned_officer->setPrimaryRole('assigned officer');

            $roles_name = ['president', 'vice president', 'general manager'];
            $usersToNotify = User::whereHas('roles', function ($query) use ($roles_name) {
                $query->whereIn('name', $roles_name);
            })->get();

            $title = 'Pending Approval';
            $message = auth()->user()->name." has added {$detachment->name} Detachment for your approval";
            $link = "/detachments/view/{$detachment->id}";

            $this->notificationService->send($usersToNotify, $title, $message, $link);

            return response()->json(['message' => 'Success', 'icon' => 'success', 'text' => 'Detachment Form submitted successfully!']);

        } catch (\Exception $e) {
            return (new FormClass)->sendErrorMessage($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function view(?string $id)
    {
        if (Gate::denies('viewAnyDetachmentProfile', Detachment::findOrFail($id))) {
            return view('content.pages.pages-misc-error');
        }

        $detachment = Detachment::find($id);
        $user_class = (new UserClass);
        $personnel_roles = $user_class->listPersonnelRoles();
        $roles = Role::whereIn('name', $personnel_roles)->get();

        return $this->detachmentProfile($detachment)
            ->with('roles', $roles);
    }

    public function profile()
    {
        $user = Auth::user();

        if (Gate::denies('viewOwnDetachmentProfile', Detachment::findOrFail($user->detachment_id))) {
            return view('content.pages.pages-misc-error');
        }

        $detachment = Detachment::find($user->detachment_id);
        $personnel_roles = (new UserClass)->listPersonnelRoles();
        $roles = Role::whereIn('name', $personnel_roles)->get();

        return $this->detachmentProfile($detachment)
            ->with('roles', $roles);

    }

    public function update(StoreDetachmentRequest $request, string $id)
    {
        $user = Auth::user();
        if (! $user->can(config('permit.edit detachment.name'))) {
            return response('Unauthorized.', 401);
        }

        $validatedData = $request->validated();

        try {
            $detachment = Detachment::findOrFail($id); // Using findOrFail
            $detachment->update($validatedData);

            // Use the Notification Service for consistency
            $roles_name = (new UserClass)->listAdminRoles();
            $users_id = User::whereHas('roles', function ($query) use ($roles_name) {
                $query->whereIn('name', $roles_name);
            })->pluck('id')->toArray();

            $title = 'Detachment Updated';
            $message = "{$user->name} has updated {$detachment->name}";
            $link = "/detachments/view/{$detachment->id}";

            SendAndBroadcastNotification::dispatch($title, $message, $link, $users_id);

            return response()->json(['message' => 'Success', 'icon' => 'success', 'text' => 'Detachment updated successfully!']);

        } catch (\Exception $e) {
            return (new FormClass)->sendErrorMessage($e);
        }
    }

    public function approve(string $id)
    {
        try {
            $user = Auth::user();
            if (! $user->can(config('permit.approve detachment.name'))) {
                return response('Unauthorized.', 401);
            }

            $detachment = Detachment::find($id);
            $detachment->status = 'approved';
            $detachment->approved_at = now();
            $detachment->approved_by = $user->id;

            if ($detachment->save()) {
                return response()->json(['message' => 'Success', 'icon' => 'success', 'text' => 'Detachment approved successfully!']);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed', 'icon' => 'error', 'text' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Failed', 'icon' => 'error', 'text' => 'Something went wrong'], 500);
    }

    public function addPersonnel(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|array',
            'detachment_id' => 'required|integer|exists:detachments,id',
        ]);

        try {
            $user_ids = $validated['user_id'];
            $count = 0;
            foreach ($user_ids as $item) {
                User::find($item)->update(['detachment_id' => $validated['detachment_id']]);
                $count++;
            }

            if ($count > 0) {
                return response()->json(['message' => 'Success', 'icon' => 'success', 'text' => $count.' personnel have been added.']);
            } else {
                return response()->json(['message' => 'Info', 'icon' => 'info', 'text' => 'No personnel were updated. They may have already been assigned.'], 200);
            }

        } catch (\Exception $e) {
            return (new FormClass)->sendErrorMessage($e);
        }
    }

    public function detachmentProfile(Detachment $detachment)
    {
        $personnel_roles = (new UserClass)->listPersonnelRoles();
        $officers = User::whereHas('roles', function ($query) use ($personnel_roles) {
            $query->whereIn('name', $personnel_roles);
        })->get();

        $users = User::whereHas('roles', function ($query) use ($personnel_roles) {
            $query->whereIn('name', $personnel_roles);
        })
            ->whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', ['root']);
            })
            ->where(function ($q) use ($detachment) {
                return $q->where('id', '!=', $detachment->id)
                    ->orWhere('detachment_id', null)
                    ->get();
            })
            ->get();

        return view('content.pages.detachment-view')
            ->with('detachment', $detachment)
            ->with('officers', $officers)
            ->with('personnel', $users);
    }
}
