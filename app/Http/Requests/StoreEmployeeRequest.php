<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can(config('permit.add personnel.name'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:100',
            'middle_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'suffix' => 'nullable|string|max:100',
            'gender' => 'required|string|in:male,female',
            'primary_role_id' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'employee_number' => 'required|string|unique:users,employee_number',
            'detachment_id' => 'sometimes|integer|exists:detachments,id', // Make sure the department exists
            'password' => 'nullable|string|min:8|confirmed',
            'street' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'zip_code' => 'required|string|max:100',
            'phone_number' => 'required|string|max:100',
            'status' => 'required|string|in:hired,re_hired,floating,on_leave,resigned,preventive_suspension',
        ];
    }
}
