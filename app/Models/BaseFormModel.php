<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

abstract class BaseFormModel extends Model
{
    use Loggable;

    /**
     * The "booted" method of the model.
     *
     * This method registers model event listeners to automatically log
     * creation, update, and deletion events for any model that extends this class.
     */
    protected static function booted(): void
    {
        parent::booted();

        static::created(function (self $model) {
            $model->logCreation($model);
        });

        static::updating(function (self $model) {
            $model->logUpdate($model);
        });

        static::deleted(function (self $model) {
            $model->logDeletion($model);
        });
    }
}
