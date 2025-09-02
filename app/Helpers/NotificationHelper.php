<?php

namespace App\Helpers;

use App\Models\Detachment;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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
        $formConfig = config("forms.types.{$formSlug}");
        $notificationConfig = $formConfig['notifications'] ?? [];
        $usersToNotifyIds = [];

        // 1. Notify users based on roles defined in the config
        if (isset($notificationConfig['roles'])) {
            $usersToNotifyIds = array_merge($usersToNotifyIds, $this->getIds($notificationConfig['roles']));
        }

        // 2. Handle special logic cases defined in the config
        if (isset($notificationConfig['special_logic'])) {
            $logicHandler = '_handle_'.Str::camel($notificationConfig['special_logic']).'Recipients';
            if (method_exists($this, $logicHandler)) {
                $usersToNotifyIds = array_merge($usersToNotifyIds, $this->{$logicHandler}($validatedData, $submitted_by));
            }
        }

        // 3. Notify the employee who is the subject of the form, if configured
        if (($notificationConfig['notify_employee'] ?? false) && $employee) {
            $usersToNotifyIds[] = $employee->id;
        }

        // Ensure the list is unique and return
        return array_unique($usersToNotifyIds);
    }

    /**
     * Handles the complex notification logic for all performance evaluation forms.
     */
    private function _handlePerformanceEvaluationRecipients(array $validatedData, User $submitted_by): array
    {
        $detachmentId = $validatedData['detachment_id'] ?? null;
        if (! $detachmentId) {
            return [];
        }

        // If the submitter is the assigned officer of the detachment, notify HR/Ops.
        if ($submitted_by->detachment_id == $detachmentId && $submitted_by->isAssignedOfficer()) {
            return $this->getIds(['hr manager', 'hr specialist', 'operation manager']);
        } else {
            $detachment = Detachment::find($detachmentId);
            $assigned_officer_id = $detachment->assigned_officer;
            if ($assigned_officer_id) {
                return [$assigned_officer_id];
            } elseif ($detachment->category == 'Single Post') {
                return $this->getIds(['hr manager', 'hr specialist', 'operation manager']);
            }
        }

        return [];
    }

    /**
     * Fetches user IDs based on an array of role names.
     */
    private function getIds(array $roles): array
    {
        return User::whereHas('roles', function ($query) use ($roles) {
            $query->whereIn('name', $roles);
        })->pluck('id')->toArray();
    }
}
