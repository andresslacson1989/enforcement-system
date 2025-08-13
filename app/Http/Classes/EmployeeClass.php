<?php

namespace App\Http\Classes;

use App\Http\Requests\StoreEmployeeRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeClass
{
  /**
   * Create a new employee from validated data.
   *
   * @param array $request The validated data.
   * @return User The newly created employee model.
   */
  public function store(StoreEmployeeRequest $request): User
  {
    // Prepare the final data for creation
    $employeeData = array_merge($request, [
      'name' => trim($request['first_name'] . ' ' . $request['last_name'] . ' ' . ($request['suffix'] ?? '')),
      'first_name' => $request['first_name'],
      'middle_name' => $request['middle_name'],
      'last_name' => $request['last_name'],
      'suffix' => $request['suffix'],
      'detachment_id' => $request['deployment'],
      'employee_number' => $request['employee_number'],
      'password' => Hash::make('esiai' . $request['employee_number']),
    ]);

    return User::create($employeeData);
  }
}
