<?php

namespace App\Http\Controllers\pages;
use App\Http\Classes\PermissionsClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Access extends Controller
{

  public function roles()
  {
    $permissions = (new PermissionsClass())->getPermission();

    $roles = Role::where('name', '!=', 'root')->get();
    return view('content.pages.roles')
      ->with('permissions', $permissions)
      ->with('roles', $roles);
  }


  public function form_roles(Request $request)
  {
    //validation
    $request->validate([
      'name' => 'required',
      'permissions' => 'required|array',
      'description' => 'required'
    ]);

    $name = $request->name;
    $permissions = $request->permissions;
    $description = $request->description;

    //create roles
    $create = Role::create(['name' => $name]);
    if ($create->givePermissionTo($permissions)) {
      $create->description = $description;
      $create->save();
      return response()->json(['message' => 'Success', 'text' => "Role Created", 'icon' => 'success']);
    }

    return response()->json(['message' => 'Failed', 'text' => "Invalid inputs", 'icon' => 'error']);


  }
  public function form_update_roles(Request $request)
  {
    //validation
    $request->validate([
      'role_id' => 'required|integer',
      'role_permissions' => 'required|array',
      'role_description' => 'required'
    ]);

    $id = $request->role_id;
    $permissions = $request->role_permissions;
    $description = $request->role_description;

    //create roles
    $role = Role::findById($id);
    if ($role->syncPermissions($permissions)) {
      $role->description = $description;
      $role->save();
      return response()->json(['message' => 'Success', 'text' => "Role Updated", 'icon' => 'success']);
    }

    return response()->json(['message' => 'Failed', 'text' => "Invalid inputs", 'icon' => 'error']);

  }

  public function table_roles(Request $request)
  {
    if ($request->ajax()) {
      // Build your Eloquent query.
      // DataTables will handle sorting, searching, and pagination based on its parameters.
      $roles = Role::all();
      foreach ($roles as $item) {
        if ($item->name == 'root') {
          continue;
        }
        $role = Role::findById($item->id);
        $permissions = $role->permissions;

      }
      $perm = [];
      foreach ($permissions as $permission) {
        $perm[] = $permission->name;
      }

      // You can add more conditions to your query here, for example:
      // $data = User::where('status', 'active')->select(['id', 'name', 'email', 'created_at']);
      // If you're using Spatie permissions and want to show roles:
      // $data = User::with('roles')->select(['id', 'name', 'email', 'created_at']);


      $draw = $request->get('draw');
      $start = $request->get("start");
      $rowPerPage = $request->get("length"); // Rows display per page

      $columnIndex_arr = $request->get('order');
      $columnName_arr = $request->get('columns');
      $order_arr = $request->get('order');

      $search_arr = $request->get('search');

      $columnIndex = $columnIndex_arr[0]['column']; // Column index
      $columnName = $columnName_arr[$columnIndex]['data']; // Column name
      $columnSortOrder = $order_arr[0]['dir']; // asc or desc
      $searchValue = $request->searchFilter ?? ''; //$search_arr['value']; // Search value
      $totalRecords = $roles->count();

      $typeFilter = $request->typeFilter;
      $staffFilter = $request->staff;

      $totalRecordsWithFilter = $roles->count();
      $active_pin = 0;
      $inactive_pin = 0;
      $total_pins = $roles->count();

      foreach ($roles as $item) {
        $data[] = [
          "id" => $item->id,
          "name" => $item->name,
          "permissions" => $perm ?? '',
          "actions" => 'buttons'
        ];
      }

      $response = array(
        "draw" => (int)$draw,
        "recordsTotal" => $totalRecords,
        "recordsFiltered" => $totalRecordsWithFilter,
        "data" => $data,
        "current_data" => [
          'selected' => ucfirst($typeFilter),
          'active_pin' => $active_pin,
          'inactive_pin' => $inactive_pin,
          'total' => $total_pins
        ]
      );
      return json_encode($response, JSON_THROW_ON_ERROR | false);
    }
  }

  public function see_permissions(Request $request)
  {
      $user = auth()->user();
      $request->validate([
          'id' => 'required|integer',
        ]);
      if($user->can('view_permission')){
        $role = Role::findById($request->id);
        $permissions = $role->permissions;
        return json_encode($permissions, JSON_PRETTY_PRINT, JSON_THROW_ON_ERROR | false);
      }
      return json_encode(['status' => 'error', 'message' => 'unauthorized'], JSON_THROW_ON_ERROR | false);
  }

  public function permissions()
  {
    $permissions = (new PermissionsClass())->getPermission();
    return view('content.pages.permissions')
      ->with('permissions', $permissions);
  }
  public function form_permissions(Request $request)
  {
    $request->validate([
      'permission_group' => 'required',
      'permission_name' => 'required',
    ]);

    $name = $request->permission_name;
    $group = $request->permission_group;

    if ( Permission::create(['name' => $name, 'group' => $group])) {
      return response()->json(['message' => 'Success', 'text' => "Role Created", 'icon' => 'success']);
    }

    return response()->json(['message' => 'Failed', 'text' => "Invalid inputs", 'icon' => 'error']);
  }

  public function form_update_permissions(Request $request)
  {
    $request->validate([
      'update_permission_name' => 'required',
      'update_permission_group' => 'required',
    ]);

    $permission_name = $request->update_permission_name;
    $permission_group = $request->update_permission_group;

    $permission = Permission::findByName($permission_name);
    $permission->group = $permission_group;
    if ($permission->save()) {
      return response()->json(['message' => 'Success', 'text' => "Permission Updated", 'icon' => 'success']);
    }

    return response()->json(['message' => 'Failed', 'text' => "Invalid inputs", 'icon' => 'error']);

  }

}
