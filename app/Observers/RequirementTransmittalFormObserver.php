<?php

namespace App\Observers;

use App\Models\RequirementTransmittalForm;
use App\Models\User;
use App\Traits\Loggable;

class RequirementTransmittalFormObserver
{
    use Loggable;

    /**
     * Handle the User "created" event.
     * When a new requirement transmittal form is created and assigned a detachment.
     */
    public function created(RequirementTransmittalForm $form): void
    {
        // logging
        $this->logCreation($form);
    }

    /**
     * Handle the User "updating" event.
     * .
     */
    public function updating(RequirementTransmittalForm $form)
    {
        // logging
        $this->logUpdate($form);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(RequirementTransmittalForm $form): void
    {
        // logging
        $this->logDeletion($form);
    }
}
