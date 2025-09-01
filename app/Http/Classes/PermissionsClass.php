<?php

namespace App\Http\Classes;

use Spatie\Permission\Models\Permission;

class PermissionsClass
{
    public function getPermission(): array
    {
        $data = Permission::all();
        // categorize permissions
        $permissions = [];
        foreach ($data as $item) {
            if (! $item->group || $item->group == null) {
                $permissions['Not Used'][] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                ];

                continue;
            }
            $permissions[$item->group][] = [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
            ];
        }

        return $permissions;
    }
}
