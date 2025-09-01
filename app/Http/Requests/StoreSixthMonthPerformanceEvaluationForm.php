<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSixthMonthPerformanceEvaluationForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // It's good practice to check for permissions here.
        // This can be adjusted based on your exact permission names.
        if ($this->isMethod('POST')) {
            return $this->user()->can('fill Sixth Month Performance Evaluation Form');
        }

        // For updates (PUT/PATCH)
        return $this->user()->can('edit Sixth Month Performance Evaluation Form');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $ratingRule = 'required|in:poor,fair,good,excellent';
        $textRule = 'nullable|string|max:255';

        $rules = [
            // Performance Criteria
            'attendance_punctuality_a' => $ratingRule,
            'attendance_punctuality_b' => $ratingRule,
            'attendance_punctuality_c' => $ratingRule,
            'job_knowledge_a' => $ratingRule,
            'job_knowledge_b' => $ratingRule,
            'job_knowledge_c' => $ratingRule,
            'professionalism_ethic_a' => $ratingRule,
            'professionalism_ethic_b' => $ratingRule,
            'professionalism_ethic_c' => $ratingRule,
            'communication_skills_a' => $ratingRule,
            'communication_skills_b' => $ratingRule,
            'communication_skills_c' => $ratingRule,
            'problem_solving_a' => $ratingRule,
            'problem_solving_b' => $ratingRule,
            'problem_solving_c' => $ratingRule,
            'teamwork_interpersonal_a' => $ratingRule,
            'teamwork_interpersonal_b' => $ratingRule,
            'teamwork_interpersonal_c' => $ratingRule,
            'adaptability_flexibility_a' => $ratingRule,
            'adaptability_flexibility_b' => $ratingRule,

            // Strengths and Improvements
            'strength_1' => $textRule,
            'strength_2' => $textRule,
            'strength_3' => $textRule,
            'improvement_1' => $textRule,
            'improvement_2' => $textRule,
            'improvement_3' => $textRule,

            // Overall Standing
            'overall_standing' => $ratingRule,

            // Comments
            'supervisor_comment' => 'nullable|string',
            'security_comment' => 'nullable|string',
        ];

        // Add rules that ONLY apply when CREATING a new record (store).
        if ($this->isMethod('POST')) {
            $rules['detachment_id'] = 'required|exists:detachments,id';
            $rules['submitted_by'] = 'required|exists:users,id';
            $rules['employee_number'] = 'nullable|string|max:255';
        }

        // This rule is needed for both create and update, but must be first.
        return [
            'employee_id' => 'required|exists:users,id',
        ] + $rules;
    }
}
