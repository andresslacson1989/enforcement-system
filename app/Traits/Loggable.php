<?php

namespace App\Traits;

use App\Models\ActivityLog;
use App\Models\Detachment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

trait Loggable
{
    private const USER_ID_ATTRIBUTES = ['approved_by', 'submitted_by', 'last_printed_by', 'suspended_by', 'assigned_officer', 'user_id'];

    private const DETACHMENT_ID_ATTRIBUTES = ['detachment_id', 'deployment'];

    /**
     * Log the creation of a model.
     */
    protected function logCreation(Model $model): void
    {
        $actor = auth()->user()?->name ?? 'System';
        $modelName = class_basename($model);
        $formattedModelName = trim(preg_replace('/([a-z])([A-Z])/', '$1 $2', $modelName));

        $data = '';
        foreach ($model->getAttributes() as $attribute => $value) {

            $value = $this->getName($attribute, $value);
            $data .= ucwords(str_replace('_', ' ', $attribute)).': <span class="fw-bold">'.$value.'</span><br>';
        }

        $name = $this->name($model);

        $this->logActivity(
            $model,
            'created',
            "<h6 class='mb-0'>{$actor} created new <span class='text-primary'>[{$formattedModelName}]</span> #{$model->id} {$name}</h6><br>".$data
        );
    }

    /**
     * Log the update of a model, detailing all changes on separate lines.
     */
    protected function logUpdate(Model $model): void
    {
        $actor = auth()->user()?->name ?? 'System';
        $modelName = class_basename($model);
        $formattedModelName = trim(preg_replace('/([a-z])([A-Z])/', '$1 $2', $modelName));
        $changes = [];

        foreach ($model->getDirty() as $attribute => $newValue) {

            // We don't need to log the 'updated_at' timestamp change
            if ($attribute === 'updated_at') {
                continue;
            }

            $newValue = $this->getName($attribute, $newValue);
            $oldValue = $this->getName($attribute, $model->getOriginal($attribute));

            if ($oldValue == '' || ! $oldValue) {
                $oldValue = 'N/A';
            }

            $formatted_attribute = trim(ucwords(str_replace('_', ' ', $attribute)));

            $changes[] = "$formatted_attribute: <span class='fw-bold'>'{$oldValue}' <span class='text-danger h5'>→</span> '{$newValue}'</span><br>";

        }

        if (empty($changes)) {
            return; // No actual changes to log
        }

        $name = $this->name($model);

        $details = implode(' ', $changes);
        $message = "<h6 class='mb-0'>{$actor} updated <span class='text-primary'>[{$formattedModelName}]</span> #{$model->id} {$name}</h6> <br> {$details}";

        $this->logActivity($model, 'updated', $message);
    }

    /**
     * Log the deletion of a model.
     */
    protected function logDeletion(Model $model): void
    {
        $actor = auth()->user()?->name ?? 'System';
        $modelName = class_basename($model);
        $formattedModelName = trim(preg_replace('/([a-z])([A-Z])/', '$1 $2', $modelName));
        $recordName = $this->name($model);

        $this->logActivity(
            $model,
            'deleted',
            "<h6>{$actor} deleted <span class='text-primary'>[{$formattedModelName}]</span> #{$model->id} {$recordName}</h6>"
        );
    }

    /**
     * Helper function to create the activity log entry.
     */
    private function logActivity(Model $model, string $action, string $message): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'loggable_type' => get_class($model),
            'loggable_id' => $model->id,
            'action' => $action,
            'message' => $message,
        ]);
    }

    /**
     * Helper function to determine Model and attach corresponding name instead of just giving the id
     */
    private function name(Model $model): string
    {

        // For forms that are about a specific employee (standardized to 'employee')
        if (method_exists($model, 'employee') && $model->employee) {
            return "for <a href='".route('user-profile', $model->employee->id)."'>{$model->employee->name}</a>";
        }

        // For models with a 'name' property like User, Detachment, Role
        if (isset($model->name)) {
            // Add quotes for clarity in the log message
            return "'{$model->name}'";
        }

        // Fallback for any models that might still use the 'user' relationship name
        if (method_exists($model, 'user') && $model->user) {
            return 'For '.$model->user->name;
        }

        return ''; // Return an empty string by default
    }

    /**
     * Helper function to give name to ids
     */
    private function getName($attribute, $newValue)
    {
        // Return early if the value is null or empty
        if (is_null($newValue) || $newValue === '') {
            return 'N/A';
        }

        // For future performance improvements, consider collecting all IDs
        // from the model's attributes/dirty attributes first, then querying
        // the related models in bulk to avoid N+1 query issues.

        if ($attribute === 'primary_role_id') {
            return ucwords(Role::find($newValue)?->name ?? "Invalid Role [#{$newValue}]");
        }

        if (in_array($attribute, self::USER_ID_ATTRIBUTES)) {
            return User::find($newValue)?->name . "[#{$newValue}]" ?? "Deleted User [#{$newValue}]";
        }

        if (in_array($attribute, self::DETACHMENT_ID_ATTRIBUTES)) {
            return Detachment::find($newValue)?->name . "[#{$newValue}]" ?? "Deleted Detachment [#{$newValue}]";
        }

        return $newValue;
    }
}
