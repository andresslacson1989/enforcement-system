<?php

namespace App\Http\Controllers\pages;

use App\Models\Detachment;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

class HomePage extends Controller
{
  public function index()
  {
    $user = Auth::user();
    $detachment = $user->detachment;
    $forms = Submission::with('submittable')
      ->where('user_id', $user->id)
      ->get();
    $data  = [];
    foreach ($forms as $submission) {
      // This will give you the specific form data for each submission
      $data[] = $submission->submittable;

    }
    return view('content.pages.pages-home')
      ->with('forms', $data)
      ->with('user', $user)
      ->with('detachment', $detachment);
  }
}
