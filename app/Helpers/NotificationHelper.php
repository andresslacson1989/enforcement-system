<?php

namespace App\Helpers;

use App\Http\Classes\UserClass;
use App\Models\Detachment;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use LaravelIdea\Helper\App\Models\_IH_Notification_C;

class NotificationHelper
{
    public static function getLatestNotifications(): array|_IH_Notification_C|Collection
    {
        if (Auth::check()) {
            return Notification::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }

        return collect();
    }

    public static function getUnreadNotificationCount(): int
    {
        if (Auth::check()) {
            return Notification::where('user_id', Auth::id())
                ->whereNull('read_at')
                ->count();
        }

        return 0;
    }

    public function determineRecipients(string $formSlug, array $validatedData, $submitted_by, $employee = null): array
    {
        $usersToNotifyIds = [];
        if ($formSlug === 'requirement-transmittal-form') {
            // roles to notify
            $roles = (new UserClass)->listStaffRoles();
            $usersToNotifyIds = User::whereHas('roles', function ($query) use ($roles) {
                $query->whereIn('name', $roles);
            })->pluck('id')->toArray();
            if ($employee) {
                $usersToNotifyIds[] = $employee->id;
            }
        } elseif ($formSlug === 'first-month-performance-evaluation') {
            if ($submitted_by->detachment_id == $validatedData['deployment'] && $submitted_by->isAssignedOfficer()) {
                $usersToNotifyIds = $this->getIds(['hr manager', 'hr specialist', 'operation manager']);
            } else {
                $detachment = Detachment::find($validatedData['deployment']);
                $assigned_officer_id = $detachment->assigned_officer;
                if ($assigned_officer_id) {
                    $usersToNotifyIds[] = $assigned_officer_id;
                } elseif ($detachment->category == 'Single Post') {
                    $usersToNotifyIds = $this->getIds(['hr manager', 'hr specialist', 'operation manager']);
                } else {
                    return [];
                }
            }
        }

        return $usersToNotifyIds;
    }

    private function getIds(array $roles): array
    {
        return User::whereHas('roles', function ($query) use ($roles) {
            $query->whereIn('name', $roles);
        })->pluck('id')->toArray();
    }
}
