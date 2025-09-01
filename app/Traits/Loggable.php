<?php

namespace App\Traits;

use App\Models\ActivityLog;
use App\Models\Detachment;
use App\Models\FirstMonthPerformanceEvaluationForm;
use App\Models\RequirementTransmittalForm;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

trait Loggable
{
    /**
     * Log the creation of a model.
     */
    protected function logCreation(Model $model): void
    {
        $actor = auth()->user()?->name ?? 'System';
        $modelName = class_basename($model);
        $formattedModelName = trim(preg_replace('/([a-z])([A-Z])/', '$1 $2', $modelName));

        $data = '';
        foreach ($model->toArray() as $attribute => $value) {

            $value = $this->attribute($attribute, $value);
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

            $newValue = $this->attribute($attribute, $newValue);
            $oldValue = $this->attribute($attribute, $model->getOriginal($attribute));

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
        // Extract the name to a variable to avoid complex expression in string
        $recordName = $model->name ?? '';

        $this->logActivity(
            $model,
            'deleted',
            "<h6>{$actor} deleted <span class='text-primary'>[{$formattedModelName}]</span> #{$model->id} '{$recordName}'</h6>"
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
    private function name($model)
    {

        if ($model instanceof User || $model instanceof Detachment) {
            return $model->name;
        }

        if ($model instanceof RequirementTransmittalForm) {
            // Use the employee_name field for this form
            return 'For '.$model->employee_name;
        }

        if ($model instanceof FirstMonthPerformanceEvaluationForm) {

            // For this form, we can reference the employee ID
            // or load the relationship if we want the name
            return 'For '.$model->user->name;
        }

        return ''; // Return an empty string by default
    }

    /**
     * Helper function to give name to ids
     */
    private function attribute($attribute, $newValue)
    {
        // Return early if the value is null or empty
        if (is_null($newValue) || $newValue === '') {
            return 'N/A';
        }

        if ($attribute === 'primary_role_id') {
            // Use null-safe operator and provide a fallback
            $newValue = ucwords(Role::findById($newValue)?->name ?? "Invalid Role [#{$newValue}]");

        } elseif (in_array($attribute, ['approved_by', 'submitted_by', 'last_printed_by', 'suspended_by', 'assigned_officer', 'user_id'])) {
            // Use null-safe operator and provide a fallback
            $newValue = User::find($newValue)?->name."[#{$newValue}]" ?? "Deleted User [#{$newValue}]";

        } elseif (in_array($attribute, ['detachment_id', 'deployment'])) {
            // Use null-safe operator and provide a fallback
            $newValue = Detachment::find($newValue)?->name."[#{$newValue}]" ?? "Deleted Detachment [#{$newValue}]";
        }

        return $newValue;
    }
}
