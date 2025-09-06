<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreThirdMonthPerformanceEvaluationForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * We can add specific permission checks here later. For example:
     * return $this->user()->can('create Third Month Performance Evaluation Form');
     */
    public function authorize(): bool
    {
        return Auth::user()->can(config('permit.fill third month performance evaluation form.name'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        // These rules are based on a standard performance evaluation.
        // Adjust them to match the exact fields on your form.
        $rules = [
            // dates
            'period_review_start_date' => 'required|date',
            'period_review_end_date' => 'required|date|after_or_equal:period_review_start_date',

            // Performance Ratings
            'attendance_punctuality_a' => 'required|in:poor,fair,good,excellent',
            'attendance_punctuality_b' => 'required|in:poor,fair,good,excellent',
            'attendance_punctuality_c' => 'required|in:poor,fair,good,excellent',
            'job_knowledge_a' => 'required|in:poor,fair,good,excellent',
            'job_knowledge_b' => 'required|in:poor,fair,good,excellent',
            'job_knowledge_c' => 'required|in:poor,fair,good,excellent',
            'professionalism_ethic_a' => 'required|in:poor,fair,good,excellent',
            'professionalism_ethic_b' => 'required|in:poor,fair,good,excellent',
            'professionalism_ethic_c' => 'required|in:poor,fair,good,excellent',
            'communication_skills_a' => 'required|in:poor,fair,good,excellent',
            'communication_skills_b' => 'required|in:poor,fair,good,excellent',
            'communication_skills_c' => 'required|in:poor,fair,good,excellent',
            'problem_solving_a' => 'required|in:poor,fair,good,excellent',
            'problem_solving_b' => 'required|in:poor,fair,good,excellent',
            'problem_solving_c' => 'required|in:poor,fair,good,excellent',
            'teamwork_interpersonal_a' => 'required|in:poor,fair,good,excellent',
            'teamwork_interpersonal_b' => 'required|in:poor,fair,good,excellent',
            'teamwork_interpersonal_c' => 'required|in:poor,fair,good,excellent',
            'adaptability_flexibility_a' => 'required|in:poor,fair,good,excellent',
            'adaptability_flexibility_b' => 'required|in:poor,fair,good,excellent',

            // Strengths and Improvements
            'strength_1' => 'nullable|string|max:255',
            'strength_2' => 'nullable|string|max:255',
            'strength_3' => 'nullable|string|max:255',
            'improvement_1' => 'nullable|string|max:255',
            'improvement_2' => 'nullable|string|max:255',
            'improvement_3' => 'nullable|string|max:255',

            // Overall Standing and Comments
            'overall_standing' => 'required|in:poor,fair,good,excellent',
            'supervisor_comment' => 'nullable|string|max:2000',
            'security_comment' => 'nullable|string|max:2000',
        ];

        if ($this->isMethod('POST')) {
            $rules = array_merge($rules, [
                'employee_id' => 'required|exists:users,id',
                'employee_number' => 'nullable|string',
                'detachment_id' => 'required|exists:detachments,id',
                'submitted_by' => 'required|exists:users,id',
            ]);
        }

        return $rules;
    }
}
