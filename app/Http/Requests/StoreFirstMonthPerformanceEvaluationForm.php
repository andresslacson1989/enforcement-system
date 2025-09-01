<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreFirstMonthPerformanceEvaluationForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        if ($user->isAssignedOfficer()) {
            return true;
        }

        return $user->can(config('permit.fill first month performance evaluation form.name'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // --- 1. Define the base rules that apply to BOTH store and update. ---
        // These are the fields that can be changed during an edit, like ratings and comments.
        $rules = [
            'overall_standing' => 'required|in:poor,fair,good,excellent',
            'knowledge_understanding_a' => 'required|in:poor,fair,good,excellent',
            'knowledge_understanding_b' => 'required|in:poor,fair,good,excellent',
            'attendance_a' => 'required|in:poor,fair,good,excellent',
            'attendance_b' => 'required|in:poor,fair,good,excellent',
            'observation_a' => 'required|in:poor,fair,good,excellent',
            'observation_b' => 'required|in:poor,fair,good,excellent',
            'communication_a' => 'required|in:poor,fair,good,excellent',
            'communication_b' => 'required|in:poor,fair,good,excellent',
            'professionalism_a' => 'required|in:poor,fair,good,excellent',
            'professionalism_b' => 'required|in:poor,fair,good,excellent',
            'strength_1' => 'nullable|string|max:255',
            'strength_2' => 'nullable|string|max:255',
            'strength_3' => 'nullable|string|max:255',
            'improvement_1' => 'nullable|string|max:255',
            'improvement_2' => 'nullable|string|max:255',
            'improvement_3' => 'nullable|string|max:255',
            'supervisor_comment' => 'nullable|string',
            'security_comment' => 'nullable|string',
        ];

        // --- 2. Add rules that ONLY apply when CREATING a new record (store). ---
        if ($this->isMethod('POST')) {

            // This merges the employee-related rules into our main rules array only on POST requests.
            $rules = array_merge($rules, [
                'employee_id' => 'required|exists:users,id',
                'employee_number' => 'nullable|string|max:255',
                'job_title' => 'nullable|string|max:255',
                // It's better to validate that the deployment ID exists in the detachments table.
                'detachment_id' => 'required|exists:detachments,id',
                'submitted_by' => 'required|exists:users,id',
            ]);
        }

        // --- 3. The 'update' (PUT/PATCH) request will now only use the base rules. ---

        return $rules;
    }
}
