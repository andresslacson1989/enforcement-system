<?php

namespace App\Traits;

use App\Models\ActivityLog;
use App\Models\Detachment;
use App\Models\User;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

trait Loggable
{
    private const USER_ID_ATTRIBUTES = ['approved_by', 'submitted_by', 'last_printed_by', 'suspended_by', 'assigned_officer', 'user_id'];

    private const DETACHMENT_ID_ATTRIBUTES = ['detachment_id', 'deployment'];

    /**
     * Log the creation of a model record.
     *
     * This method is triggered when a new model instance is created. It constructs a human-readable
     * message detailing the actor, the type of model created, and a list of its initial attributes and values.
     *
     * @param  Model  $model  The model instance that was created.
     */
    protected function logCreation(Model $model): void
    {
        $actor_name = auth()->user()?->name ?? 'System';
        $model_name = class_basename($model);
        $formatted_model_name = trim(preg_replace('/([a-z])([A-Z])/', '$1 $2', $model_name));

        $data = '';
        foreach ($model->getAttributes() as $attribute => $value) {

            $formatted_value = $this->formatValue($attribute, $value);
            if ($formatted_value instanceof DateTimeInterface) {
                continue;
            }
            $data .= ucwords(str_replace('_', ' ', $attribute)).': <span class="fw-bold">'.$formatted_value.'</span><br>';
        }

        $name = $this->getModelNameString($model);

        $this->logActivity(
            $model,
            'created',
            "<h6 class='mb-0'>{$actor_name} created new <span class='text-primary'>[{$formatted_model_name}]</span> #{$model->id} {$name}</h6><br>".$data
        );
    }

    /**
     * Log the update of a model, detailing all changed attributes.
     *
     * This method is triggered when a model instance is updated. It efficiently pre-loads related data
     * to prevent N+1 query issues, then constructs a message showing the old and new values for each
     * modified attribute, ensuring a clear audit trail of changes.
     *
     * @param  Model  $model  The model instance that was updated.
     */
    protected function logUpdate(Model $model): void
    {
        $actor_name = auth()->user()?->name ?? 'System';
        $model_name = class_basename($model);
        $formatted_model_name = trim(preg_replace('/([a-z])([A-Z])/', '$1 $2', $model_name));
        $changes = [];
        $dirty_attributes = $model->getDirty();

        // Pre-load related models to prevent N+1 query issues.
        $preloaded_data = $this->preloadRelatedData($dirty_attributes, $model->getOriginal());

        foreach ($dirty_attributes as $attribute => $new_value) {

            // We don't need to log the 'updated_at' timestamp change
            if ($attribute === 'updated_at') {
                continue;
            }

            $formatted_new_value = $this->formatValue($attribute, $new_value, $preloaded_data);
            $formatted_old_value = $this->formatValue($attribute, $model->getOriginal($attribute), $preloaded_data);

            if ($formatted_old_value == '' || ! $formatted_old_value) {
                $formatted_old_value = 'N/A';
            }

            $formatted_attribute = trim(ucwords(str_replace('_', ' ', $attribute)));

            $changes[] = "$formatted_attribute: <span class='fw-bold'>'{$formatted_old_value}' <span class='text-danger h5'>→</span> '{$formatted_new_value}'</span><br>";

        }

        if (empty($changes)) {
            return; // No actual changes to log
        }

        $name = $this->getModelNameString($model);

        $details = implode(' ', $changes);
        $message = "<h6 class='mb-0'>{$actor_name} updated <span class='text-primary'>[{$formatted_model_name}]</span> #{$model->id} {$name}</h6> <br> {$details}";

        $this->logActivity($model, 'updated', $message);
    }

    /**
     * Log the deletion of a model record.
     *
     * This method is triggered when a model instance is deleted. It creates a simple, clear
     * log entry indicating which record was removed and by whom.
     *
     * @param  Model  $model  The model instance that was deleted.
     */
    protected function logDeletion(Model $model): void
    {
        $actor_name = auth()->user()?->name ?? 'System';
        $model_name = class_basename($model);
        $formatted_model_name = trim(preg_replace('/([a-z])([A-Z])/', '$1 $2', $model_name));
        $record_name = $this->getModelNameString($model);

        $this->logActivity(
            $model,
            'deleted',
            "<h6>{$actor_name} deleted <span class='text-primary'>[{$formatted_model_name}]</span> #{$model->id} {$record_name}</h6>"
        );
    }

    /**
     * Create and persist the activity log entry in the database.
     *
     * This is the central helper method that takes the formatted log message and saves it
     * to the `activity_logs` table, linking it to the user who performed the action and the
     * model that was affected.
     *
     * @param  Model  $model  The model the action was performed on.
     * @param  string  $action  The type of action (e.g., 'created', 'updated', 'deleted').
     * @param  string  $message  The fully formatted, human-readable log message.
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
     * Generate a descriptive name string for a model instance for use in log messages.
     *
     * This method intelligently inspects the model to find a descriptive name. It checks for
     * an 'employee' relationship, a 'name' attribute, or a 'user' relationship to create a
     * meaningful and often linkable string (e.g., "'Detachment Alpha'" or "for 'John Doe'").
     *
     * @param  Model  $model  The model instance to name.
     * @return string A descriptive string for the model, or an empty string if none is found.
     */
    private function getModelNameString(Model $model): string
    {
        // For forms that are about a specific employee (standardized to 'employee')
        if (method_exists($model, 'employee') && $model->employee) {
            return "for <a href='".route('user-profile', $model->employee->id)."'>{$model->employee->name}</a>";
        }

        // For forms that are about a specific assignedOfficer (standardized to 'employee')
        if (method_exists($model, 'assignedOfficer') && $model->employee) {
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
     * Format an attribute's value into a human-readable string.
     *
     * This method translates foreign key IDs (like `user_id` or `detachment_id`) into the
     * actual names of the related records (e.g., "John Doe[#123]"). It uses pre-loaded data
     * for efficiency during updates and includes a fallback database query for creation logs.
     *
     * @param  string  $attribute  The name of the attribute being formatted.
     * @param  mixed  $value  The value of the attribute.
     * @param  array  $preloaded_data  An array of pre-queried related model data to avoid N+1 issues.
     * @return string|mixed The formatted, human-readable value or the original value if no formatting is needed.
     */
    private function formatValue($attribute, $value, array $preloaded_data = [])
    {
        // Return early if the value is null or empty
        if (is_null($value) || $value === '') {
            return 'N/A';
        }

        if ($attribute === 'primary_role_id') {
            $role_name = $preloaded_data['roles'][$value] ?? null;
            if ($role_name) {
                return ucwords($role_name);
            }

            // Fallback for logCreation where data is not preloaded
            return ucwords(Role::find($value)?->name ?? "Invalid Role [#{$value}]");
        }

        if (in_array($attribute, self::USER_ID_ATTRIBUTES)) {
            $user_name = $preloaded_data['users'][$value] ?? null;
            if ($user_name) {
                return "{$user_name}[#{$value}]";
            }

            // Fallback for logCreation
            return User::find($value)?->name."[#{$value}]" ?? "Deleted User [#{$value}]";
        }

        if (in_array($attribute, self::DETACHMENT_ID_ATTRIBUTES)) {
            $detachment_name = $preloaded_data['detachments'][$value] ?? null;
            if ($detachment_name) {
                return "{$detachment_name}[#{$value}]";
            }

            // Fallback for logCreation
            return Detachment::find($value)?->name."[#{$value}]" ?? "Deleted Detachment [#{$value}]";
        }

        return $value;
    }

    /**
     * Pre-load related model data in bulk to prevent N+1 query problems.
     *
     * Before logging an update, this method inspects all changed attributes, collects all unique
     * foreign key IDs for users, roles, and detachments, and fetches their names in a single
     * query per model type. This is a critical performance optimization.
     *
     * @param  array  $dirty_attributes  The array of attributes that have been modified.
     * @param  array  $original_attributes  The array of the model's original attributes before the update.
     * @return array An associative array containing maps of IDs to names for 'roles', 'users', and 'detachments'.
     */
    private function preloadRelatedData(array $dirty_attributes, array $original_attributes): array
    {
        $ids_to_load = [
            'roles' => [],
            'users' => [],
            'detachments' => [],
        ];

        $all_attributes = array_merge($dirty_attributes, $original_attributes);

        foreach ($all_attributes as $attribute => $value) {
            if (is_null($value)) {
                continue;
            }

            if ($attribute === 'primary_role_id') {
                $ids_to_load['roles'][] = $value;
            }
            if (in_array($attribute, self::USER_ID_ATTRIBUTES)) {
                $ids_to_load['users'][] = $value;
            }
            if (in_array($attribute, self::DETACHMENT_ID_ATTRIBUTES)) {
                $ids_to_load['detachments'][] = $value;
            }
        }

        return [
            'roles' => Role::whereIn('id', array_unique($ids_to_load['roles']))->pluck('name', 'id'),
            'users' => User::whereIn('id', array_unique($ids_to_load['users']))->pluck('name', 'id'),
            'detachments' => Detachment::whereIn('id', array_unique($ids_to_load['detachments']))->pluck('name', 'id'),
        ];
    }
}
