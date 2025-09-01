<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property int $id
 * @property string $name
 * @property int|null $employee_id
 * @property int|null $submitted_by
 * @property string|null $employee_number
 * @property string|null $job_title
 * @property int $detachment_id
 * @property string|null $meeting_date
 * @property string $status
 * @property int $printed
 * @property int $times_printed
 * @property string|null $date_last_printed
 * @property int|null $last_printed_by
 * @property string|null $knowledge_understanding_a
 * @property string|null $knowledge_understanding_b
 * @property string|null $attendance_a
 * @property string|null $attendance_b
 * @property string|null $observation_a
 * @property string|null $observation_b
 * @property string|null $communication_a
 * @property string|null $communication_b
 * @property string|null $professionalism_a
 * @property string|null $professionalism_b
 * @property string|null $strength_1
 * @property string|null $strength_2
 * @property string|null $strength_3
 * @property string|null $improvement_1
 * @property string|null $improvement_2
 * @property string|null $improvement_3
 * @property string|null $overall_standing
 * @property string|null $supervisor_comment
 * @property string|null $security_comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Detachment $detachment
 * @property-read \App\Models\Submission|null $submission
 * @property-read \App\Models\User|null $submittedBy
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereAttendanceA($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereAttendanceB($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereCommunicationA($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereCommunicationB($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereDateLastPrinted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereDetachmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereEmployeeNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereImprovement1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereImprovement2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereImprovement3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereJobTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereKnowledgeUnderstandingA($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereKnowledgeUnderstandingB($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereLastPrintedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereMeetingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereObservationA($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereObservationB($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereOverallStanding($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm wherePrinted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereProfessionalismA($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereProfessionalismB($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereSecurityComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereStrength1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereStrength2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereStrength3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereSubmittedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereSupervisorComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereTimesPrinted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FirstMonthPerformanceEvaluationForm whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FirstMonthPerformanceEvaluationForm extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'employee_number',
        'submitted_by', // supervisor
        'job_title',
        'detachment_id',
        'knowledge_understanding_a',
        'knowledge_understanding_b',
        'attendance_a',
        'attendance_b',
        'observation_a',
        'observation_b',
        'communication_a',
        'communication_b',
        'professionalism_a',
        'professionalism_b',
        'strength_1',
        'strength_2',
        'strength_3',
        'improvement_1',
        'improvement_2',
        'improvement_3',
        'overall_standing',
        'supervisor_comment',
        'security_comment',
        'meeting_date',
    ];

    public function submission(): MorphOne
    {
        // This tells Laravel that this form is "submittable"
        return $this->morphOne(Submission::class, 'submittable');
    }

    /**
     * Get the user that owns the form.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    /**
     * Get the user that owns the form.
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
        // This defines that a form belongs to one Detachment.
        // We specify 'deployment' as the foreign key on this model's table.
        return $this->belongsTo(Detachment::class, 'detachment_id', 'id');
    }
}
