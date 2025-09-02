<?php

namespace App\Observers;

use App\Actions\Detachments\AssignOfficerAction;
use App\Models\Detachment;

class DetachmentObserver
{
    public function created(Detachment $detachment): void
    {
        //
    }

    /**
     * Handle the Detachment "updating" event.
     * This event fires BEFORE the database is updated.
     */
    public function updating(Detachment $detachment): void
    {
        // If the 'assigned_officer' field is being changed, execute our Action.
        if ($detachment->isDirty('assigned_officer')) {
            (new AssignOfficerAction)->execute($detachment, $detachment->assigned_officer);
        }
    }

    /**
     * Handle the Detachment "updated" event.
     * This event fires AFTER the database is updated.
     * We leave this empty because all our logic is now handled cleanly in the "updating" event.
     */
    public function updated(Detachment $detachment): void
    {
        // Automatically set the detachment's category based on its user count.
        $numberOfPersonnel = $detachment->users->count();

        if ($numberOfPersonnel >= 61) {
            $detachment->category = 'Large Detachment';
        } elseif ($numberOfPersonnel > 20) {
            $detachment->category = 'Medium Detachment';
        } elseif ($numberOfPersonnel > 3) {
            $detachment->category = 'Small Team';
        } else {
            $detachment->category = 'Single Post';
        }

        $detachment->saveQuietly();
    }

    public function deleted(Detachment $detachment): void
    {
        //
    }
}
