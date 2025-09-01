<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ThirdMonthPerformanceEvaluationForm extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'evaluation_date',
        'attendance_and_punctuality',
        'quality_and_quantity_of_work',
        'dependability_and_responsibility',
        'knowledge_of_work',
        'attitude_and_cooperation',
        'judgment_and_decision_making',
        'relationship_with_others',
        'initiative_and_resourcefulness',
        'grooming_and_appearance',
        'physical_condition',
        'potential_for_growth',
        'overall_performance_rating',
        'comments',
        'recommendations',
        'evaluated_by_name',
        'evaluated_by_position',
        'evaluated_by_date',
        'acknowledged_by_name',
        'acknowledged_by_position',
        'acknowledged_by_date',
        'submitted_by',
        'detachment_id',
        'job_title',
    ];

    /**
     * Get the submission record associated with the form.
     */
    public function submission(): MorphOne
    {
        return $this->morphOne(Submission::class, 'submittable');
    }

    /**
     * Get the employee (user) that this form is for.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    /**
     * Get the user who submitted the form.
     */
    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * Get the detachment that this form belongs to.
     */
    public function detachment(): BelongsTo
    {
        return $this->belongsTo(Detachment::class, 'detachment_id');
    }
}
