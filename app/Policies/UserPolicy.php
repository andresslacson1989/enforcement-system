<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
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
    public function viewOwnPersonnelProfile(User $user, User $personnel): bool
    {
        // A user can always view their own profile.
        if ($user->id === $personnel->id && $user->can(config('permit.view own personnel profile.name'))) {
            return true;
        }

        if ($user->detachment) {
            if ($user->id == $personnel->detachment->assigned_officer && $user->can(config('permit.view own detachment personnel profile.name'))) {
                return true;
            }

        }

        if ($user->can(config('permit.view any personnel profile.name'))) {
            return true;
        }

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
    public function update(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
