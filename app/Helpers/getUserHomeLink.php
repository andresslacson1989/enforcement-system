<?php

use App\Http\Classes\UserClass;

if (! function_exists('getUserHomeLink')) {
    /**
     * Returns a set of strings (permissions) for the authenticated user.
     */
    function getUserHomeLink(): string
    {
        $user = auth()->user();
        $user_class = new UserClass;
        $admin_roles = $user_class->listAdminRoles();
        $staff_roles = $user_class->listStaffRoles();
        $personnel_roles = $user_class->listPersonnelRoles();

        if ($user->hasAnyRole($admin_roles)) {
            return route('form-library');
        } elseif ($user->hasAnyRole($staff_roles)) {
            return route('personnel');
        } elseif ($user->hasAnyRole($personnel_roles)) {
            return route('profile', ['id' => $user->id]);
        }

        return route('login');
    }
}
