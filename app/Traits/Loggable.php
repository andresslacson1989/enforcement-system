<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

trait Loggable
{
  /**
   * Log the creation of a model.
   */
  protected function logCreation(Model $model): void
  {
    $actor = auth()->user()?->name ?? 'System';
    $modelName = class_basename($model);

    $this->logActivity(
      $model,
      'created',
      "{$actor} created new {$modelName} #{$model->id}."
    );
  }

  /**
   * Log the update of a model, detailing all changes on separate lines.
   */
  protected function logUpdate(Model $model): void
  {
    $actor = auth()->user()?->name ?? 'System';
    $modelName = class_basename($model);
    $changes = [];

    foreach ($model->getDirty() as $attribute => $newValue) {
      // We don't need to log the 'updated_at' timestamp change
      if ($attribute === 'updated_at') {
        continue;
      }
      $formatted_attribute = trim(ucwords(str_replace('_', ' ', $attribute)));

      $oldValue = $model->getOriginal($attribute);
      // Format each change with bolding and an arrow for clarity
      $changes[] = "<br><span class='text-gray'>{$formatted_attribute}</span>: <span class='fw-bold text-danger'>'{$oldValue}'</span> → <span class='fw-bold text-success'>'{$newValue}'</span>";
    }

    if (empty($changes)) {
      return; // No actual changes to log
    }

    // Combine the changes with a newline for readability
    $details = implode("\n", $changes);
    $message = "<h5>{$actor} updated {$modelName} #{$model->id}:<br>{$details}</h5>";

    $this->logActivity($model, 'updated', $message);
  }

  /**
   * Log the deletion of a model.
   */
  protected function logDeletion(Model $model): void
  {
    $actor = auth()->user()?->name ?? 'System';
    $modelName = class_basename($model);
    // Extract the name to a variable to avoid complex expression in string
    $recordName = $model->name ?? '';

    $this->logActivity(
      $model,
      'deleted',
      "{$actor} deleted {$modelName} #{$model->id} '{$recordName}'."
    );
  }

  /**
   * Helper function to create the activity log entry.
   */
  private function logActivity(Model $model, string $action, string $message): void
  {
    ActivityLog::create([
      'user_id'       => auth()->id(),
      'loggable_type' => get_class($model),
      'loggable_id'   => $model->id,
      'action'        => $action,
      'message'       => $message,
    ]);
  }
}
