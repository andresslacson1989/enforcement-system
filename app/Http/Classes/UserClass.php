<?php

namespace App\Http\Classes;

use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserClass
{
  public function create(array $employee_data){
    $rules = [
      'name' => 'required|string|max:100',
      'first_name' => 'required|string|max:100',
      'middle_name' => 'required|string|max:100',
      'last_name' => 'required|string|max:100',
      'suffix' => 'nullable|string|max:100',
      'email' => 'nullable|email|unique:users,email',
      'employee_number' => 'required|string|unique:users,employee_number',
      'detachment_id' => 'required|integer|exists:detachments,id', // Make sure the department exists
      'password' => 'required|string|min:8',
      'requirement_transmittal_form_id' => 'nullable|integer|exists:requirement_transmittal_forms,id',
    ];

    Validator::make($employee_data, $rules)->validate();
    return User::create($employee_data);

  }
}
