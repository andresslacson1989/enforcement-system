<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled in the controller via Gates or Policies
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            // Personal Info
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'gender' => 'sometimes|required|in:male,female',
            'birthdate' => 'sometimes|required|date',
            'phone_number' => ['sometimes', 'required', 'string', 'max:20', Rule::unique('users')->ignore($userId)],
            'telegram_chat_id' => 'nullable|string|max:255',

            // Address
            'street' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:255',
            'province' => 'sometimes|required|string|max:255',
            'zip_code' => 'sometimes|required|string|max:10',

            // Government IDs
            'sss_number' => 'sometimes|required|string|max:255',
            'philhealth_number' => 'sometimes|required|string|max:255',
            'pagibig_number' => 'sometimes|required|string|max:255',

            // System Info
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'password' => 'nullable|string|min:6|confirmed',
            'employee_number' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('users')->ignore($userId)],
            'primary_role_id' => 'sometimes|required|exists:roles,id',
            'status' => 'sometimes|required|string',
        ];
    }
}
