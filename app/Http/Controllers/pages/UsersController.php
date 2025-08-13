<?php

namespace App\Http\Controllers\pages;

use App\Http\Requests\StoreEmployeeRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UsersController
{
    //
    public function index() {}

    public function usersData(string $id)
    {
        $user = User::find($id);
        $data = $user->toArray();
        $dc = User::find($user->detachment->commander);
        $job_title = $user->getRoleNames()[0];
        $data['job_title'] = ucwords($job_title);
        $data['deployment'] = ucwords($user->detachment->name);
        $data['detachment_commander'] = $dc->id;

        return response()->json($data);
    }

    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        try {
            // 1. Get the already validated data from the request.
            $data = $request->validated();

            // 2. Prepare the final data array for creating the model.
            $data->name = $data->first_name.' '.$data->last_name.' '.$data->suffix;
            $data->passowrd = Hash::make('esiai'.$data->employee_number);

            // 3. Create the employee.
            $employee = User::create($data);

            // 4. Return a success response.
            return response()->json([
                'message' => 'Employee created successfully!',
                'data' => $employee,
            ], 201);
        } catch (\Exception $e) {

            // Return an error JSON response
            return response()->json(['message' => $e->getMessage(), 'error' => 'Error'], 500);
        }

    }
}
