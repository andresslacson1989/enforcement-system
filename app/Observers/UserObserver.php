<?php

namespace App\Observers;

use App\Jobs\SendAndBroadcastNotification;
use App\Models\Detachment;
use App\Models\User;
use Auth;
use Spatie\Permission\Models\Role;

class UserObserver
{
    /**
     * Handle the User "created" event.
     * When a new user is created and assigned to a detachment.
     */
    public function created(User $user): void
    {
        if ($user->detachment_id) {
            $this->updateDetachmentCategory(Detachment::find($user->detachment_id));
        }
    }

    /**
     * Handle the User "updating" event.
     * This is the main logic for when a user is moved between detachments.
     */
    public function updating(User $user)
    {
        //  Only if the detachment_id is being changed.

        if ($user->isDirty('detachment_id')) {
            $oldDetachmentId = $user->getOriginal('detachment_id');
            $newDetachmentId = $user->detachment_id;

            // Update the category for the OLD detachment (if there was one)
            if ($oldDetachmentId) {
                $this->updateDetachmentCategory(Detachment::find($oldDetachmentId));
            }

            // Update the category for the NEW detachment (if there is one)
            if ($newDetachmentId) {
                $this->updateDetachmentCategory(Detachment::find($newDetachmentId));
            }

            $commanded_detachment = Detachment::where('assigned_officer', $user->id)->first();

            if ($commanded_detachment) {
                $commanded_detachment->assigned_officer = null;
                $commanded_detachment->save();

                $user->removeRole('assigned officer');
                $firstRoleName = $user->getRoleNames()->first();

                if ($firstRoleName) {
                    $user->primary_role_id = Role::findByName($firstRoleName)->id;
                } else {
                    $user->primary_role_id = null;
                }
            }

        }

        // Check if the user was just assigned the 'assigned officer' role.
        // We'll use the Spatie method for a clean check.
        if ($user->wasChanged('primary_role_id') && $user->hasRole('assigned officer')) {
            // We also need to check if the user was just assigned a detachment
            // to ensure the notification is relevant.
            if ($user->detachment_id) {
                $this->notifyAssignedOfficer($user, $user->detachment_id);
            }
        }
    }

    public function updated(User $user)
    {
        //   \Log::info('User detachment updated.');
    }

    /**
     * Handle the User "deleted" event.
     * When a user is deleted, their detachment's personnel count decreases.
     */
    public function deleted(User $user): void
    {
        if ($user->detachment_id) {
            $this->updateDetachmentCategory(Detachment::find($user->detachment_id));
        }
    }

    /**
     * A private helper function to calculate and update a detachment's category.
     */
    private function updateDetachmentCategory(?Detachment $detachment): mixed
    {
        // If the detachment doesn't exist (e.g., was deleted), do nothing.
        if (! $detachment) {
            return null;
        }

        // Use the efficient database count method
        $personnelCount = $detachment->users()->count();

        if ($personnelCount >= 61) {
            $detachment->category = 'Large Detachment';
        } elseif ($personnelCount > 20) {
            $detachment->category = 'Medium Detachment';
        } elseif ($personnelCount > 3) {
            $detachment->category = 'Small Team';
        } else {
            $detachment->category = 'Single Post';
        }

        // Save the detachment only if the category has changed.
        if ($detachment->isDirty('category')) {
            $detachment->save();
        }

        return $detachment->category;
    }

    /**
     * Send a notification to the newly assigned officer.
     */
    private function notifyAssignedOfficer(User $user, int $detachmentId): void
    {
        $detachment = Detachment::find($detachmentId);
        if (! $detachment) {
            return;
        }

        $actor = Auth::user();
        $title = 'You have been assigned as an Officer';
        $message = "You have been assigned as an Officer of {$detachment->name} by {$actor->name}";
        $link = "/detachments/view/{$detachment->id}";

        SendAndBroadcastNotification::dispatch($title, $message, $link, [$user->id]);
    }
}
