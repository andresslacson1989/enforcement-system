<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
    public function getSubmittedForms(mixed $id): Collection
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
