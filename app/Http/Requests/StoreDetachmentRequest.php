<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreDetachmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->can(config('permit.add detachment.name'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'assigned_officer' => 'nullable|numeric',

            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',

            'hours_per_shift' => 'required|integer|min:0',
            'max_hrs_duty' => 'required|integer|min:0',
            'max_ot' => 'required|integer|min:0',

            'hr_rate' => 'required|numeric|min:0',
            'ot_rate' => 'required|numeric|min:0',
            'nd_rate' => 'required|numeric|min:0',
            'rdd_rate' => 'required|numeric|min:0',
            'rdd_ot_rate' => 'required|numeric|min:0',
            'hol_rate' => 'required|numeric|min:0',
            'hol_ot_rate' => 'required|numeric|min:0',
            'sh_rate' => 'required|numeric|min:0',
            'sh_ot_rate' => 'required|numeric|min:0',
            'rd_hol_rate' => 'required|numeric|min:0',
            'rd_hol_ot_rate' => 'required|numeric|min:0',
            'rd_sh_rate' => 'required|numeric|min:0',
            'rd_sh_ot_rate' => 'required|numeric|min:0',

            'cash_bond' => 'required|numeric|min:0',
            'sil' => 'required|numeric|min:0',
            'ecola' => 'required|numeric|min:0',
            'retirement_pay' => 'required|string|max:255',
            'thirteenth_month_pay' => 'required|string|max:255',
        ];
    }
}
