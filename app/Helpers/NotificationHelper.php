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

        // 3. Conditionally notify the employee who is the subject of the form
        $shouldNotifyEmployee = $notificationConfig['notify_employee'] ?? false;
        if ($shouldNotifyEmployee && $employee) {
            $usersToNotifyIds[] = $employee->id;
        }

        // 4. Remove the original submitter from the list, unless they are also the employee
        // being notified. This prevents users from getting notifications for their own actions.
        if ($employee === null || $submitted_by->id !== $employee->id) {
            $usersToNotifyIds = array_filter($usersToNotifyIds, function ($id) use ($submitted_by) {
                return $id !== $submitted_by->id;
            });
        }

        // 5. Ensure the list is unique and return
        return array_unique($usersToNotifyIds);
    }

    /**
     * Handles the complex notification logic for all performance evaluation forms.
     */
    private function _handle_performanceEvaluationRecipients(array $validatedData, User $submitted_by): array
    {
        $detachmentId = $validatedData['detachment_id'] ?? null;
        $detachment = $detachmentId ? Detachment::find($detachmentId) : null;

        if (! $detachment) {
            return [];
        }

        $staffs = ['hr manager', 'hr specialist', 'operation manager'];

        // If the submitter is the assigned officer of the detachment, notify HR/Ops.
        if ($submitted_by->id === $detachment->assigned_officer) {
            return $this->getIds($staffs);
        } else {
            // If there is an assigned officer, notify them.
            if ($detachment->assigned_officer) {
                return [$detachment->assigned_officer];
            } elseif ($detachment->category == 'Single Post') {
                // If it's a single post with no officer, notify staffs.
                return $this->getIds($staffs);
            } else {
                // Fallback: If no specific officer is assigned, and it's not a single post,
                // default to notifying the staffs to ensure the form is seen.
                return $this->getIds($staffs);
            }
        }
    }

    /**
     * Fetches user IDs based on an array of role names.
     */
    private function getIds(array $roles): array
    {
        return User::whereHas('roles', function ($query) use ($roles) {
            $query->whereIn('name', $roles);
        })
            ->where('status', 'hired') // notify the hired only
            ->pluck('id')
            ->toArray();
    }
}
