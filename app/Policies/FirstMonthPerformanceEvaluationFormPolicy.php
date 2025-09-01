<?php

namespace App\Policies;

use App\Models\FirstMonthPerformanceEvaluationForm;
use App\Models\User;

class FirstMonthPerformanceEvaluationFormPolicy
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
    public function view(User $user, FirstMonthPerformanceEvaluationForm $form): bool
    {
        if ($user->can(config('permit.view first month performance evaluation form.name')) && $user->id == $form->deployment && $user->isAssignedOfficer()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(config('permit.fill first month performance evaluation form.name'));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FirstMonthPerformanceEvaluationForm $form): bool
    {
        if ($user->can(config('permit.edit first month performance evaluation form.name')) && $user->id == $form->deployment && $user->isAssignedOfficer()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FirstMonthPerformanceEvaluationForm $form): bool
    {

        if ($user->can(config('permit.delete first month performance evaluation form.name')) && $user->id == $form->deployment && $user->isAssignedOfficer()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FirstMonthPerformanceEvaluationForm $firstMonthPerformanceEvaluationForm): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FirstMonthPerformanceEvaluationForm $firstMonthPerformanceEvaluationForm): bool
    {
        return false;
    }
}
