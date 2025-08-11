<?php

namespace App\Http\Controllers\pages;

use App\Models\Detachment;
use App\Models\User;

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
}
