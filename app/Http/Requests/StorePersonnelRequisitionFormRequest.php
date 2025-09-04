<?php

// app/Http/Requests/StorePersonnelRequisitionFormRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePersonnelRequisitionFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * For now, we'll allow any authenticated user. Later, you can add role-based
     * security here, e.g., return Auth::user()->hasRole('operations');
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // Section A
            'detachment_id' => 'required|exists:detachments,id',
            'pr_number' => 'nullable|string|max:100',
            'personnel_types' => 'required|array|min:1',
            'personnel_types.*' => 'string|max:255', // Ensures each item in the array is a string
            'purpose' => 'required|array|min:1',
            'purpose.*' => 'string|max:255',
            'manpower_needed' => 'required|integer|min:1',
            'estimated_date_needed' => 'required|date|after_or_equal:today',

            // Client Requirements (mostly nullable strings)
            'height_male' => 'nullable|string|max:50',
            'height_female' => 'nullable|string|max:50',
            'weight_male' => 'nullable|string|max:50',
            'weight_female' => 'nullable|string|max:50',
            'age_range_male' => 'nullable|string|max:50',
            'age_range_female' => 'nullable|string|max:50',
            'education' => 'nullable|string|max:65535', // Corresponds to TEXT column type
            'training' => 'nullable|string|max:65535',
            'skills_experience' => 'nullable|array|max:65535',
            'years_of_experience' => 'nullable|string|max:100',
            'other_qualifications' => 'nullable|string|max:65535',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'detachment_id.required' => 'The requesting detachment is required.',
            'estimated_date_needed.after_or_equal' => 'The date needed must be today or a future date.',
        ];
    }
}
