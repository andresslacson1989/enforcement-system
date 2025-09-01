<?php

// In app/Services/NotificationService.php

namespace App\Services;

use App\Jobs\SendAndBroadcastNotification;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class NotificationService
{
    /**
     * Send a notification to a set of users.
     *
     * @param  array|Collection  $users  An array of user IDs or a Collection of User models.
     * @param  string  $title  The title of the notification.
     * @param  string  $message  The main body of the notification.
     * @param  string  $link  The URL the notification should link to.
     */
    public function send(array|Collection $users, string $title, string $message, string $link): void
    {
        // If we received a collection of User models, pluck their IDs.
        $userIds = $users instanceof Collection ? $users->pluck('id')->toArray() : $users;

        // Dispatch the job to the queue for background processing.
        SendAndBroadcastNotification::dispatch($title, $message, $link, $userIds);
    }
}
