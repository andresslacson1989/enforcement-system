<?php

namespace App\Policies;

use App\Models\Detachment;
use App\Models\User;

class DetachmentPolicy
{
    /**
     * Determine whether the user can get the personnel of a Detachment.
     */
    public function viewDetachmentPersonnel(User $user, Detachment $detachment): bool
    {

        // Allow users with the "view any detachment personnel" permission to view any detachment
        if ($user->can(config('permit.view any detachment personnel.name'))) {
            return true;
        }

        // Allow the assigned officer to view their own detachment
        return $detachment->assigned_officer === $user->id && $user->can(config('permit.view own detachment personnel.name'));
    }

    /**
     * Determine whether the user can get the personnel of a Detachment.
     * Route; /detachments/view/{id}
     */
    public function viewAnyDetachmentProfile(User $user, Detachment $detachment): bool
    {

        // Allow users with the "view any detachment profile" permission to view any detachment
        if ($user->can(config('permit.view any detachment profile.name'))) {
            return true;
        }

        // Allow the assigned officer to view their own detachment
        return $detachment->assigned_officer === $user->id && $user->can(config('permit.view own detachment profile.name'));
    }

    /**
     * Determine whether the user can get the personnel of a Detachment.
     * Route; /detachments/profile
     */
    public function viewOwnDetachmentProfile(User $user, Detachment $detachment): bool
    {

        // Allow users with the "view any detachment profile" permission to view any detachment
        if ($user->can(config('permit.view own detachment profile.name'))) {
            return true;
        }

        // Allow the assigned officer to view their own detachment
        return $detachment->assigned_officer === $user->id && $user->can(config('permit.view own detachment profile.name'));
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Detachment $detachment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Detachment $detachment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Detachment $detachment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Detachment $detachment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Detachment $detachment): bool
    {
        return false;
    }
}
