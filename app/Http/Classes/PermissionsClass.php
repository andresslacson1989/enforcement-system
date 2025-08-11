<?php

namespace App\Http\Classes;

use Spatie\Permission\Models\Permission;

class PermissionsClass
{
  /**
   * @return array
   */
  public function getPermission(): array
  {
    $data = Permission::all();
    //categorize permissions
    $permissions = [];
    foreach ($data as $item) {
      if (!$item->group || $item->group == null) {
        $permissions['Not Used'][] = [
          'id' => $item->id,
          'name' => $item->name,
        ];
        continue;
      }
      $permissions[$item->group][] = [
        'id' => $item->id,
        'name' => $item->name,
      ];
    }
    return $permissions;
  }

}
