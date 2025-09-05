<?php

namespace App\Observers;

use App\Models\Detachment;
use App\Models\User;
use Spatie\Permission\Models\Role;

class DetachmentObserver
{
    public function created(Detachment $detachment): void
    {
        //
    }

    /**
     * Handle the Detachment "updating" event, which fires before the database is updated.
     *
     * This method is the central trigger for managing officer assignments. When a detachment's
     * 'assigned_officer' is changed, this method orchestrates the promotion of the new officer
     * and the demotion of the old one. It achieves this by updating the respective User models,
     * which in turn triggers the UserObserver to handle the complex role synchronization logic.
     *
     * @param  \App\Models\Detachment  $detachment  The detachment instance being updated.
     */
    public function updating(Detachment $detachment): void
    {
        // Check if the 'assigned_officer' field is what's being changed.
        if ($detachment->isDirty('assigned_officer')) {
            $new_officer_id = $detachment->assigned_officer;
            $old_officer_id = $detachment->getOriginal('assigned_officer');

            // Step 1: Demote the OLD officer (if there was one, and they are changing)
            if ($old_officer_id && $old_officer_id !== $new_officer_id) {
                $former_officer = User::find($old_officer_id);
                if ($former_officer) {
                    // The UserObserver will handle all role removal when primary_role_id is set to null.
                    // This is the safest and most reliable way to trigger a full demotion.
                    // The previous logic was risky and could cause errors if the user didn't have a second role.
                    $former_officer->primary_role_id = null;
                    $former_officer->save();

                }
            }

            // Step 2: Promote the NEW officer (if one is being assigned)
            if ($new_officer_id) {
                $new_officer = User::find($new_officer_id);
                if ($new_officer) {
                    $assigned_officer_role = Role::where('name', 'assigned officer')->first();

                    if ($assigned_officer_role) {
                        // The UserObserver will handle syncing the correct roles.
                        // We just need to set the primary role and detachment.
                        $new_officer->primary_role_id = $assigned_officer_role->id;
                        // Save the primary role change FIRST to reliably trigger the promotion logic in UserObserver.
                        $new_officer->save();

                        // Ensure the new officer is assigned to this detachment.
                        // This might trigger a separate update if the user was in a different detachment.
                        if ($new_officer->detachment_id !== $detachment->id) {
                            $new_officer->detachment_id = $detachment->id;
                            // Save the detachment change separately.
                            $new_officer->save();
                        }
                    }
                }
            }
        }
    }

    /**
     * Handle the Detachment "updated" event, which fires after the database is updated.
     *
     * This method is intentionally left empty. The critical logic is handled in the `updating`
     * method to ensure a clean and predictable sequence of events. The UserObserver is now the
     * single source of truth for category updates based on personnel count changes.
     *
     * @param  \App\Models\Detachment  $detachment  The detachment instance that was updated.
     */
    public function updated(Detachment $detachment): void
    {
        // This logic has been moved to the UserObserver to prevent conflicts.
        // The UserObserver is the single source of truth for category updates,
        // as it's triggered by user creation, deletion, and detachment changes.
    }

    public function deleted(Detachment $detachment): void
    {
        //
    }
}
