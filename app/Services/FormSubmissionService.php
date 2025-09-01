<?php

// app/Services/FormSubmissionService.php

namespace App\Services;

use App\Jobs\SendAndBroadcastNotification;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FormSubmissionService
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handles storing a form, its submission, and notifying users.
     *
     * @param  string  $formModelClass  The class name of the form model (e.g., FirstMonthPerformanceEvaluationForm::class)
     * @param  array  $validatedData  The validated data from the request.
     * @param  User  $submitter  The user who is submitting the form.
     * @param  array  $usersToNotifyIds  An array of user IDs to be notified.
     * @param  string  $formName  A user-friendly name for the form.
     * @return Model The created form model instance.
     */
    public function handle(string $formModelClass, array $validatedData, User $submitter, array $usersToNotifyIds, string $formName): Model
    {

        // 1. Create the form
        $form = $formModelClass::create($validatedData);

        // 2. Create the polymorphic submission record
        $form->submission()->create([
            'submitted_by' => $submitter->id,
        ]);

        // 3. Invalidate cache for the submitting user
        Cache::forget('user_submissions:'.$submitter->id);

        // 4. Send notifications
        if (! empty($usersToNotifyIds)) {
            $this->notifyUsers($form, $submitter, $usersToNotifyIds, $formName);
        }

        return $form;
    }

    private function notifyUsers(Model $form, User $submitter, array $userIds, string $formName): void
    {
        $uniqueUserIds = array_unique($userIds);
        $usersToNotify = User::whereIn('id', $uniqueUserIds)->pluck('id')->toArray();

        if (empty($usersToNotify)) {
            return;
        }

        $title = "New: $formName";
        $message = "{$submitter->name} has submitted a new $formName for your attention.";
        $formTypeSlug = str_replace(' ', '-', strtolower($formName));
        $link = "/form/view/{$formTypeSlug}/{$form->id}";

        // Using the job is fine, or you could use your NotificationService directly.
        // Let's stick with the job for consistency as it's used elsewhere.
        SendAndBroadcastNotification::dispatch($title, $message, $link, $usersToNotify);
    }
}
