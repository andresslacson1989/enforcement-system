<?php

namespace App\Http\Controllers\pages;

use App\Http\Classes\UserClass;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role; // Assuming you might need the request object

class RoleController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @Accessible_roles
     * 'president',
     * 'vice president',
     * 'general manager',
     * 'accounting manager',
     * 'senior accounting manager',
     * 'accounting specialist',
     * 'hr manager',
     * 'hr specialist',
     * 'operation manager',
     * 'assigned officer',
     * 'detachment commander',
     * 'officer in charge',
     * 'security in charge',
     * 'cluster head guard',
     * 'head guard',
     * 'assistant head guard',
     * 'security guard',
     * 'lady guard'
     */
    public function getRoles(string $category, UserClass $user_class)
    {
        $personnel = $user_class->listPersonnelRoles();
        $staffs = $user_class->listStaffRoles();

        // Use a 'match' expression to cleanly get the role names
        $roleNames = match ($category) {
            'admin' => $user_class->listAdminRoles(),
            'staff' => $staffs,
            'personnel-staff' => array_merge($staffs, $personnel),

            'personnel' => $personnel,
            'personnel-admin' => $user_class->listPersonnelAdminRoles(),
            'personnel-base' => $user_class->listPersonnelBaseRoles(),

            default => array_merge(
                $user_class->listAdminRoles(),
                $user_class->listStaffRoles(),
                $user_class->listPersonnelRoles()
            ),
        };

        // Fetch the roles and transform the data using a collection map
        $data = Role::whereIn('name', $roleNames)
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => ucwords($role->name),
                ];
            });

        // Return the clean JSON array
        return response()->json($data);
    }
}
