<?php

namespace App\Listeners;

use App\Http\Classes\UserClass;
use Spatie\Permission\Events\RoleAttached;
use Spatie\Permission\Models\Role;

class UpdatePrimaryRoleOnRoleSync
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RoleAttached $event): void
    {
        // Add this line at the very top of the method
        $user = $event->model;
        $syncedRoles = $event->rolesOrIds;

        if (empty($syncedRoles)) {
            if ($user->primary_role_id !== null) {
                $user->primary_role_id = null;
                // if personnel has not primary role then change the status to floating
                $user->status = 'floating';
                $user->save();
            }

            return;
        }

        $primaryRoleIdentifier = is_array($syncedRoles) ? $syncedRoles[0] : $syncedRoles;
        $primaryRole = null;
        if (is_numeric($primaryRoleIdentifier)) {
            $primaryRole = Role::findById($primaryRoleIdentifier);
        } elseif (is_string($primaryRoleIdentifier)) {
            $primaryRole = Role::findByName($primaryRoleIdentifier);
        }

        if ($primaryRole && $user->primary_role_id !== $primaryRole->id) {

            // Check if the personnel is given a staff role then remove it from the detachment
            $staff_roles = (new UserClass)->listStaffRoles();
            if (in_array($primaryRole->name, $staff_roles)) {
                $user->detachment_id = null;
            }

            $user->primary_role_id = $primaryRole->id;
            $user->save();
        }
    }
}
