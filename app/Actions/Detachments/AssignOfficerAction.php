<?php

namespace App\Actions\Detachments;

use App\Models\Detachment;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignOfficerAction
{
    public function execute(Detachment $detachment, ?int $newOfficerId): void
    {
        $oldOfficerId = $detachment->getOriginal('assigned_officer');

        // Step 1: Demote the OLD officer (if there was one, and they are changing)
        if ($oldOfficerId && $oldOfficerId !== $newOfficerId) {
            $formerOfficer = User::find($oldOfficerId);
            if ($formerOfficer) {
                $formerOfficer->removeRole('assigned officer');

                // Sensibly reset their primary role to their next highest role
                $firstRoleName = $formerOfficer->getRoleNames()->first();
                $formerOfficer->primary_role_id = $firstRoleName ? Role::findByName($firstRoleName)->id : null;
                $formerOfficer->save();
            }
        }

        // Step 2: Promote the NEW officer (if one is being assigned)
        if ($newOfficerId) {
            $newOfficer = User::find($newOfficerId);
            if ($newOfficer) {
                $assignedOfficerRole = Role::findByName('assigned officer');

                $roles = [$assignedOfficerRole->name];

                // Determine additional roles based on detachment category
                if ($detachment->category == 'Large Detachment') {
                    $roles[] = 'detachment commander';
                } elseif ($detachment->category == 'Medium Detachment') {
                    $roles[] = 'officer in charge';
                } elseif ($detachment->category == 'Small Team') {
                    $roles[] = 'head guard';
                }

                $newOfficer->syncRoles($roles);
                $newOfficer->primary_role_id = $assignedOfficerRole->id;

                // Ensure the new officer is assigned to this detachment
                if ($newOfficer->detachment_id !== $detachment->id) {
                    $newOfficer->detachment_id = $detachment->id;
                }

                $newOfficer->save();
            }
        }
    }
}
