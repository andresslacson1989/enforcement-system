<?php

namespace App\Http\Controllers\pages;

use App\Http\Classes\FormClass;
use App\Models\Detachment;
use App\Models\Form;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class ActivityBoard
{

  public function formNew($form)
  {
    return (new FormClass())->new($form);
  }

  public function formView($form, $id)
  {
    return (new FormClass())->view($form, $id);
  }
}
