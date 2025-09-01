<?php

namespace App\Http\Controllers\pages;

use App\Http\Classes\PermissionsClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Access extends Controller
{
    public function index()
    {
        if (! auth()->user()->can(config('permit.view roles menu.name'))) {
            return view('content.pages.pages-misc-error');
        }
        $permissions = (new PermissionsClass)->getPermission();

        $roles = Role::withCount(['users', 'permissions'])
            ->where('name', '!=', 'root')
            ->orderBy('id', 'asc')
            ->get();

        return view('content.pages.roles')
            ->with('permissions', $permissions)
            ->with('roles', $roles);
    }

    public function form_roles(Request $request)
    {
        // UPDATED VALIDATION: Added unique rule for the role name.
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'sometimes|array', // 'sometimes' allows empty permission sets
            'description' => 'required|string|max:255',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        if ($request->has('permissions')) {
            $role->givePermissionTo($request->permissions);
        }

        return response()->json(['message' => 'Success', 'text' => 'Role Created', 'icon' => 'success']);
    }

    public function form_update_roles(Request $request)
    {
        // UPDATED VALIDATION:
        // 1. Also validates the 'name' field.
        // 2. Uses Rule::unique to ignore the current role's name when checking for duplicates.
        $request->validate([
            'role_id' => 'required|integer|exists:roles,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($request->role_id),
            ],
            'role_permissions' => 'sometimes|array',
            'role_description' => 'required|string|max:255',
        ]);

        $role = Role::findById($request->role_id);

        // THE PRIMARY FIX: Check if the role exists before using it.
        if (! $role) {
            return response()->json(['message' => 'Failed', 'text' => 'Role not found.', 'icon' => 'error'], 404);
        }

        // Update name and description
        $role->name = $request->name;
        $role->description = $request->role_description;
        $role->save();

        // Sync permissions
        $permissions = $request->role_permissions ?? []; // Default to empty array if no permissions are sent
        $role->syncPermissions($permissions);

        return response()->json(['message' => 'Success', 'text' => 'Role Updated', 'icon' => 'success']);
    }

    public function table_roles(Request $request)
    {
        if ($request->ajax()) {
            // Build your Eloquent query.
            // DataTables will handle sorting, searching, and pagination based on its parameters.
            $permissions = [];
            $roles = Role::all();
            foreach ($roles as $item) {
                if ($item->name == 'root') {
                    continue;
                }

                // Get permissions for the CURRENT role in the loop
                $rolePermissions = $item->permissions->pluck('name')->toArray();

                $data[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    // Use the permissions specific to this role
                    'permissions' => $rolePermissions,
                    'actions' => 'buttons',
                ];
            }

            // You can add more conditions to your query here, for example:
            // $data = User::where('status', 'active')->select(['id', 'name', 'email', 'created_at']);
            // If you're using Spatie permissions and want to show roles:
            // $data = User::with('roles')->select(['id', 'name', 'email', 'created_at']);

            $draw = $request->get('draw');
            $start = $request->get('start');
            $rowPerPage = $request->get('length'); // Rows display per page

            $columnIndex_arr = $request->get('order');
            $columnName_arr = $request->get('columns');
            $order_arr = $request->get('order');

            $search_arr = $request->get('search');

            $columnIndex = $columnIndex_arr[0]['column']; // Column index
            $columnName = $columnName_arr[$columnIndex]['data']; // Column name
            $columnSortOrder = $order_arr[0]['dir']; // asc or desc
            $searchValue = $request->searchFilter ?? ''; // $search_arr['value']; // Search value
            $totalRecords = $roles->count();

            $typeFilter = $request->typeFilter;
            $staffFilter = $request->staff;

            $totalRecordsWithFilter = $roles->count();
            $active_pin = 0;
            $inactive_pin = 0;
            $total_pins = $roles->count();

            $response = [
                'draw' => (int) $draw,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecordsWithFilter,
                'data' => $data,
                'current_data' => [
                    'selected' => ucfirst($typeFilter),
                    'active_pin' => $active_pin,
                    'inactive_pin' => $inactive_pin,
                    'total' => $total_pins,
                ],
            ];

            return json_encode($response, JSON_THROW_ON_ERROR | false);
        }

        return response('unauthorized', 401);
    }

    public function updateRole(Request $request, $id)
    {
        if (! auth()->user()->can(config('permit.change personnel role.name'))) {
            return response('Unauthorized.', 401);
        }

        $role = Role::findById($request->role_id);
        $personnel = User::find($id);

        if ($personnel->syncRoles([$role->id])) {
            $personnel->setPrimaryRole($role->id);

            return response()->json(['message' => 'Success', 'text' => 'Role Updated', 'icon' => 'success']);
        }

        return response()->json(['message' => 'Failed', 'text' => 'Invalid inputs', 'icon' => 'error']);
    }
}
