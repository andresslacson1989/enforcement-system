<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $reason
 * @property string $start_date
 * @property string|null $end_date
 * @property int $suspended_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $suspendedBy
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suspension newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suspension newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suspension query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suspension whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suspension whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suspension whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suspension whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suspension whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suspension whereSuspendedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suspension whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suspension whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Suspension whereUserId($value)
 * @mixin \Eloquent
 */
class Suspension extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'reason',
        'start_date',
        'end_date',
        'suspended_by',
    ];

    /**
     * Get the user that owns the suspension.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who created the suspension.
     */
    public function suspendedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'suspended_by');
    }
}
