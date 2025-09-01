<?php

namespace App\Http\Requests;

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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // These rules are based on a standard performance evaluation.
        // Adjust them to match the exact fields on your form.
        return [
            'employee_id' => 'required|exists:users,id',
            'evaluation_date' => 'required|date',
            'attendance_and_punctuality' => 'required|integer|min:1|max:5',
            'quality_and_quantity_of_work' => 'required|integer|min:1|max:5',
            'dependability_and_responsibility' => 'required|integer|min:1|max:5',
            'knowledge_of_work' => 'required|integer|min:1|max:5',
            'attitude_and_cooperation' => 'required|integer|min:1|max:5',
            'judgment_and_decision_making' => 'required|integer|min:1|max:5',
            'relationship_with_others' => 'required|integer|min:1|max:5',
            'initiative_and_resourcefulness' => 'required|integer|min:1|max:5',
            'grooming_and_appearance' => 'required|integer|min:1|max:5',
            'physical_condition' => 'required|integer|min:1|max:5',
            'potential_for_growth' => 'required|integer|min:1|max:5',
            'overall_performance_rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string|max:2000',
            'recommendations' => 'nullable|string|max:2000',
            'evaluated_by_name' => 'required|string|max:255',
            'evaluated_by_position' => 'required|string|max:255',
            'evaluated_by_date' => 'required|date',
            'acknowledged_by_name' => 'required|string|max:255',
            'acknowledged_by_position' => 'required|string|max:255',
            'acknowledged_by_date' => 'required|date',
        ];
    }
}