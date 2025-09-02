<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class SixthMonthPerformanceEvaluationForm extends BaseFormModel
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Relationships
        'employee_id',
        'submitted_by',
        'detachment_id',

        // Employee Info
        'employee_number',
        'job_title',

        // Performance Criteria
        'attendance_punctuality_a',
        'attendance_punctuality_b',
        'attendance_punctuality_c',
        'job_knowledge_a',
        'job_knowledge_b',
        'job_knowledge_c',
        'professionalism_ethic_a',
        'professionalism_ethic_b',
        'professionalism_ethic_c',
        'communication_skills_a',
        'communication_skills_b',
        'communication_skills_c',
        'problem_solving_a',
        'problem_solving_b',
        'problem_solving_c',
        'teamwork_interpersonal_a',
        'teamwork_interpersonal_b',
        'teamwork_interpersonal_c',
        'adaptability_flexibility_a',
        'adaptability_flexibility_b',

        // Strengths, Improvements, and Comments
        'strength_1',
        'strength_2',
        'strength_3',
        'improvement_1',
        'improvement_2',
        'improvement_3',
        'overall_standing',
        'supervisor_comment',
        'security_comment',
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
}