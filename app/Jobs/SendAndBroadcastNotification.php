<?php

namespace App\Jobs;

use App\Events\NotificationSent;
use App\Models\Notification as DbNotification;
use App\Models\User;
use App\Notifications\SendTelegramNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class SendAndBroadcastNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $title;

    protected string $message;

    protected string $link;

    protected array $user_ids;

    public function __construct(string $title, string $message, string $link, array $user_ids)
    {
        $this->title = $title;
        $this->message = $message;
        $this->link = $link;
        $this->user_ids = $user_ids;
    }

    public function handle(): void
    {
        foreach ($this->user_ids as $user_id) {
            $notification = DbNotification::create([
                'title' => $this->title,
                'body' => $this->message,
                'user_id' => $user_id,
                'link' => $this->link,
            ]);
            Log::info($notification);

            // Instead of passing the whole object, pass only its ID.
            NotificationSent::dispatch($notification->id);

            // Send Telegram notification
            $user = User::find($user_id);
            if ($user && $user->telegram_chat_id) {
                // 3. Send the notification directly to the user object.
                NotificationFacade::send($user, new SendTelegramNotification(
                    $this->title,
                    $this->message,
                    $this->link
                ));
            }
        }
    }
}
