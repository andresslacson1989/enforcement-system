<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreIdApplicationFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // We can add a specific permission check later if needed.
        // e.g., return $this->user()->can('fill id application form');
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Rules for the initial submission by the employee
        if ($this->isMethod('POST')) {
            return [
                'employee_id' => 'required|exists:users,id',
                'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Add validation for the photo
                'emergency_contact_name' => 'required|string|max:255',
                'emergency_contact_relation' => 'required|string|max:255',
                'emergency_contact_address' => 'required|string|max:1000',
                'emergency_contact_number' => 'required|string|max:20',
            ];
        }

        // Rules for the update by HR
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return [
                'is_info_complete' => 'nullable|boolean',
                'has_id_picture' => 'nullable|boolean',
                'is_for_filing' => 'nullable|boolean',
                'is_encoded' => 'nullable|boolean',
                'is_card_done' => 'nullable|boolean',
                'is_delivered' => 'nullable|boolean',
                'status' => 'nullable|string|in:pending,processed,approved,denied',
                'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ];
        }

        return [];
    }
}
