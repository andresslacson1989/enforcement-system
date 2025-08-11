<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFirstMonthPerformanceEvaluationForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
      return [
        // Employee and General Info
        'employee' => 'required|exists:users,id',
        'employee_number' => 'nullable|string|max:255',
        'job_title' => 'nullable|string|max:255',
        'deployment' => 'required|string|max:255',
        'supervisor' => 'required|string|max:255',

        // Performance Criteria (Radio Buttons)
        'knowledge_understanding_a' => 'nullable|string|in:poor,fair,good,excellent',
        'knowledge_understanding_b' => 'nullable|string|in:poor,fair,good,excellent',
        'attendance_a' => 'nullable|string|in:poor,fair,good,excellent',
        'attendance_b' => 'nullable|string|in:poor,fair,good,excellent',
        'observation_a' => 'nullable|string|in:poor,fair,good,excellent',
        'observation_b' => 'nullable|string|in:poor,fair,good,excellent',
        'communication_a' => 'nullable|string|in:poor,fair,good,excellent',
        'communication_b' => 'nullable|string|in:poor,fair,good,excellent',
        'professionalism_a' => 'nullable|string|in:poor,fair,good,excellent',
        'professionalism_b' => 'nullable|string|in:poor,fair,good,excellent',

        // Areas of Strength and Improvement
        'strength_1' => 'nullable|string|max:255',
        'strength_2' => 'nullable|string|max:255',
        'strength_3' => 'nullable|string|max:255',
        'improvement_1' => 'nullable|string|max:255',
        'improvement_2' => 'nullable|string|max:255',
        'improvement_3' => 'nullable|string|max:255',

        // Overall Standing
        'overall_standing' => 'required|string|in:poor,fair,good,excellent',

        // Comments
        'supervisor_comment' => 'nullable|string',
        'security_comment' => 'nullable|string',
        'meeting_date' => 'required|date',
      ];
    }
}
