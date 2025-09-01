<?php

namespace App\Observers;

use App\Models\Detachment;
use App\Models\FirstMonthPerformanceEvaluationForm;
use App\Models\User;
use App\Traits\Loggable;

class FirsMonthPerformanceEvaluationFormObserver
{
    use Loggable;

    /**
     * Handle the User "created" event.
     * When a new user is created and assigned to a detachment.
     */
    public function created(FirstMonthPerformanceEvaluationForm $form): void
    {
        // logging
        $this->logCreation($form);

    }

    /**
     * Handle the User "updating" event.
     * This is the main logic for when a user is moved between detachments.
     */
    public function updating(FirstMonthPerformanceEvaluationForm $form)
    {
        // This will log any changed attributes automatically
        $this->logUpdate($form);
    }

    /**
     * Handle the User "deleted" event.
     * When a user is deleted, their detachment's personnel count decreases.
     */
    public function deleted(FirstMonthPerformanceEvaluationForm $form): void
    {
        $this->logDeletion($form);

    }
}
