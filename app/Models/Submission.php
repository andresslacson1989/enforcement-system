<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int|null $submitted_by
 * @property string $submittable_type
 * @property int $submittable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $user
 * @property-read Model|\Eloquent $submittable
 * @property-read \App\Models\User|null $submittedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission whereSubmittableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission whereSubmittableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission whereSubmittedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Submission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Submission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'submitted_by',
        'submittable_id',
        'submittable_type',
    ];

    /**
     * Get the owning submittable model.
     */
    public function submittable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who submitted the form.
     */
    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * Gets all forms related to a user.
     *
     * @param  int  $id  The user_id of either the subject (employee_id) or the submitter (submitted_by).
     */
    public function getSubmittedForms(int $id): Collection
    {
        // 1. Get the new 'types' array from the config file.
        $formTypes = config('forms.types');

        // 2. Use array_column() to extract just the 'model' class names into a simple array.
        $submittableModels = array_column($formTypes, 'model');

        return Submission::whereHasMorph(
            'submittable',
            $submittableModels,
            function ($query) use ($id) {
                $query->where('employee_id', $id)
                    ->orWhere('submitted_by', $id);
            }
        )->with(['submittable', 'submittedBy'])
            ->latest()
            ->get();
    }
}
