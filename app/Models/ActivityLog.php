<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $loggable_type
 * @property int $loggable_id
 * @property string $action
 * @property string $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $loggable
 * @property-read \App\Models\User|null $user
 *
 * @method static Builder<static>|ActivityLog newModelQuery()
 * @method static Builder<static>|ActivityLog newQuery()
 * @method static Builder<static>|ActivityLog query()
 * @method static Builder<static>|ActivityLog whereAction($value)
 * @method static Builder<static>|ActivityLog whereCreatedAt($value)
 * @method static Builder<static>|ActivityLog whereId($value)
 * @method static Builder<static>|ActivityLog whereLoggableId($value)
 * @method static Builder<static>|ActivityLog whereLoggableType($value)
 * @method static Builder<static>|ActivityLog whereMessage($value)
 * @method static Builder<static>|ActivityLog whereUpdatedAt($value)
 * @method static Builder<static>|ActivityLog whereUserId($value)
 *
 * @mixin \Eloquent
 */
class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'loggable_type',
        'loggable_id',
        'action',
        'message',
    ];

    /**
     * Get the user that performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'System', // Show 'System' if user is deleted or action was automated
        ]);
    }

    /**
     * Get the parent loggable model (detachment, user, etc.).
     */
    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }
}
