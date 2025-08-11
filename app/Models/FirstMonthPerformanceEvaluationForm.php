<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    'job_title',
    'deployment',
    'supervisor',
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
    'meeting_date'
  ];

  public function submission()
  {
    // This tells Laravel that this form is "submittable"
    return $this->morphOne(Submission::class, 'submittable');
  }

  /**
   * Get the user that owns the form.
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
