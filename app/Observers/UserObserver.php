<?php

namespace App\Observers;

use App\Http\Classes\UserClass;
use App\Jobs\SendAndBroadcastNotification;
use App\Models\Detachment;
use App\Models\User;
use Auth;
use Spatie\Permission\Models\Role;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * When a new user is created and assigned to a detachment, this method triggers
     * an update of the detachment's category to reflect the new personnel count.
     *
     * @param  User  $user  The user instance that was created.
     */
    public function created(User $user): void
    {
        if ($user->detachment_id) {
            $this->updateDetachmentCategory(Detachment::find($user->detachment_id));
        }
        if (! $user->primary_role_id) {
            $user->primary_role_id = $user->getRoleNames()[0]->id ?? null;
            $user->saveQuietly();
        }
    }

    /**
     * Handle the User "updating" event, which fires before the database is updated.
     *
     * This method contains the core logic for reacting to changes in a user's status,
     * such as being moved between detachments or being promoted/demoted as an officer.
     * It ensures data integrity by updating detachment categories and synchronizing user roles
     * automatically.
     *
     * @param  User  $user  The user instance being updated.
     * @return void
     */
    public function updating(User $user)
    {
        //  Only if the detachment_id is being changed.
        if ($user->isDirty('detachment_id')) {
            $old_detachment_id = $user->getOriginal('detachment_id');
            $new_detachment_id = $user->detachment_id;

            // Update the category for the OLD detachment (if there was one)
            if ($old_detachment_id) {
                $this->updateDetachmentCategory(Detachment::find($old_detachment_id));
            }

            // Update the category for the NEW detachment (if there is one)
            if ($new_detachment_id) {
                $this->updateDetachmentCategory(Detachment::find($new_detachment_id));
            }

            // If a user is moved from a detachment they command, they must be demoted.
            $commanded_detachment = Detachment::where('assigned_officer', $user->id)->first();
            if ($commanded_detachment && $commanded_detachment->id !== $new_detachment_id) {
                $commanded_detachment->assigned_officer = null;
                $commanded_detachment->save();

                // Setting primary_role_id to null will trigger the demotion logic below.
                $user->primary_role_id = null;
            }

        }

        // This block handles all logic related to primary role changes to prevent race conditions.
        if ($user->isDirty('primary_role_id')) {
            $original_primary_role_id = $user->getOriginal('primary_role_id');
            $original_role = $original_primary_role_id ? Role::find($original_primary_role_id) : null;
            $new_primary_role_id = $user->primary_role_id;
            $new_role = $new_primary_role_id ? Role::find($new_primary_role_id) : null;

            // SCENARIO 1: User is being PROMOTED to 'assigned officer'.
            if ($new_role && $new_role->name === 'assigned officer') {
                if ($user->detachment_id) { // Ensure user is assigned to a detachment
                    $this->syncOfficerRoles($user);
                    $this->notifyAssignedOfficer($user, $user->detachment_id);
                }
            }
            // SCENARIO 2: User is being DEMOTED from 'assigned officer'.
            // This is triggered by DetachmentObserver setting primary_role_id to null.
            elseif ($original_role && $original_role->name === 'assigned officer' && is_null($new_primary_role_id)) {
                $this->unassignOfficerRoles($user);
            }
            // SCENARIO 3: Any other role change (e.g., staff assignment, guard role change).
            else {
                // This block replaces the logic from the disabled UpdatePrimaryRoleOnRoleSync listener.
                $this->syncPrimaryRoleAndStatus($user);
            }
        }
    }

    /**
     * Handle the User "updated" event, which fires after the database is updated.
     *
     * This method is currently not used but is kept for potential future logic
     * that needs to run after a user has been successfully updated.
     *
     * @param  User  $user  The user instance that was updated.
     * @return void
     */
    public function updated(User $user)
    {
        //   \Log::info('User detachment updated.');
    }

    /**
     * Handle the User "deleted" event.
     *
     * When a user is deleted, this method ensures their former detachment's category
     * is recalculated to reflect the decrease in personnel count.
     *
     * @param  User  $user  The user instance that was deleted.
     */
    public function deleted(User $user): void
    {
        if ($user->detachment_id) {
            $this->updateDetachmentCategory(Detachment::find($user->detachment_id));
        }
    }

    /**
     * Calculate and update a detachment's category based on its personnel count.
     *
     * This is a critical helper function that acts as the single source of truth for
     * determining a detachment's size category. If the category changes, it also
     * triggers the role synchronization for the detachment's assigned officer.
     *
     * @param  Detachment|null  $detachment  The detachment to be updated.
     * @return string|null The new category name or null if the detachment doesn't exist.
     */
    private function updateDetachmentCategory(?Detachment $detachment): mixed
    {
        // If the detachment doesn't exist (e.g., was deleted), do nothing.
        if (! $detachment) {
            return null;
        }

        // Use the efficient database count method
        $personnel_count = $detachment->users()->count();

        if ($personnel_count >= 61) {
            $detachment->category = 'Large Detachment';
        } elseif ($personnel_count > 10) { // As per new structure: Medium is 11-60
            $detachment->category = 'Medium Detachment';
        } elseif ($personnel_count > 2) { // As per new structure: Small Team is 3-10 (assuming 1-2 is Single Post)
            $detachment->category = 'Small Team';
        } else {
            $detachment->category = 'Single Post';
        }

        // Save the detachment only if the category has changed.
        if ($detachment->isDirty('category')) {
            // If the category changes, we need to update the assigned officer's secondary role.
            if ($detachment->assigned_officer) {
                $officer = User::find($detachment->assigned_officer);
                $this->syncOfficerRoles($officer, $detachment);
            }
            $detachment->save();
        }

        return $detachment->category;
    }

    /**
     * Send a notification to a user who has been promoted to 'assigned officer'.
     *
     * This method dispatches a job to send a real-time notification to the user,
     * informing them of their new role and providing a direct link to their detachment.
     *
     * @param  User  $user  The user receiving the notification.
     * @param  int  $detachment_id  The ID of the detachment they were assigned to.
     */
    private function notifyAssignedOfficer(User $user, int $detachment_id): void
    {
        $detachment = Detachment::find($detachment_id);
        if (! $detachment) {
            return;
        }

        $actor = Auth::user();
        $title = 'You have been assigned as an Officer';
        $message = "You have been assigned as an Officer for the {$detachment->name} detachment by {$actor->name}.";
        $link = "/detachments/view/{$detachment->id}";

        SendAndBroadcastNotification::dispatch($title, $message, $link, [$user->id]);
    }

    /**
     * Remove all officer-specific roles from a user upon demotion.
     *
     * When a user is no longer an 'assigned officer', this method cleanly strips all
     * related command roles ('detachment commander', 'officer in charge', etc.) to
     * ensure their permissions are correctly downgraded.
     *
     * @param  User  $user  The user being demoted.
     */
    private function unassignOfficerRoles(User $user): void
    {
        // Using syncRoles with an empty array is the cleanest way to remove all roles.
        // However, if you want to ensure they have a base role, you can do:
        // $user->syncRoles(['guard']);
        // For now, we just remove the officer roles as per the logic flow.
        $user->removeRole('detachment commander')->removeRole('officer in charge')->removeRole('head guard')->removeRole('assigned officer');
    }

    /**
     * Synchronizes the primary role and status based on the user's current roles.
     *
     * This method replaces the logic from the old UpdatePrimaryRoleOnRoleSync listener.
     * It ensures the `primary_role_id` always reflects the highest-priority role a user has.
     * It also handles side effects, like making a user 'floating' if they have no roles
     * or un-assigning them from a detachment if they are given a 'staff' role.
     *
     * @param  User  $user  The user to synchronize.
     */
    private function syncPrimaryRoleAndStatus(User $user): void
    {
        $current_roles = $user->getRoleNames();

        if ($current_roles->isEmpty()) {
            $user->primary_role_id = null;
            $user->status = 'floating'; // As per logic in the old listener.
        } else {
            // The first role is considered the primary one.
            $primary_role_name = $current_roles->first();
            $primary_role = Role::where('name', $primary_role_name)->first();
            $user->primary_role_id = $primary_role ? $primary_role->id : null;

            // If the new primary role is a staff role, remove user from detachment.
            $staff_roles = (new UserClass)->listStaffRoles();
            if (in_array($primary_role_name, $staff_roles)) {
                $user->detachment_id = null;
            }
        }
    }

    /**
     * Synchronize an officer's roles to match their detachment's size.
     *
     * This method is the definitive source of truth for assigning officer roles. It ensures
     * an officer always has the correct primary ('assigned officer') and secondary
     * ('detachment commander', etc.) roles based on their detachment's current personnel count.
     *
     * @param  User  $officer  The user who is the assigned officer.
     * @param  Detachment|null  $detachment  The detachment model. If not provided, it will be fetched automatically.
     */
    private function syncOfficerRoles(User $officer, ?Detachment $detachment = null): void
    {
        // If the officer or their detachment doesn't exist, we can't proceed.
        if (! $officer || ! $officer->detachment_id) {
            return;
        }

        // If the detachment wasn't passed in, load it with the user count for efficiency.
        if (! $detachment) {
            $detachment = Detachment::withCount('users')->find($officer->detachment_id);
        }

        // Get the personnel count from the loaded detachment model.
        $personnel_count = $detachment->users_count ?? $detachment->users()->count();
        $roles_to_sync = ['assigned officer']; // The base role is always present.

        if ($personnel_count >= 61) {
            $roles_to_sync[] = 'detachment commander';
        } elseif ($personnel_count >= 11 && $personnel_count <= 60) {
            $roles_to_sync[] = 'officer in charge';
        } elseif ($personnel_count >= 3 && $personnel_count <= 10) {
            $roles_to_sync[] = 'head guard';
        }

        // syncRoles will add/remove roles as needed to match the array.
        // If $roles_to_sync only contains 'assigned officer', other secondary roles will be removed.
        $officer->syncRoles($roles_to_sync);
    }
}
