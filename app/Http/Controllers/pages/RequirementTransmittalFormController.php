<?php

namespace App\Http\Controllers\pages;

use App\Events\NotificationSent;
use App\Http\Requests\StoreRequirementTransmittalFormRequest;
use App\Models\Detachment;
use App\Models\Form;
use App\Models\RequirementTransmittalForm;
use App\Models\Submission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class RequirementTransmittalFormController
{


  public function store(StoreRequirementTransmittalFormRequest $request): JsonResponse
  {
    try {
      $user = Auth::user();
      $data = $request->validated();
      $data['employee_name'] = $user->first_name . ' ' . $user->middle_name ?? '' . ' ' . $user->last_name . ' ' . $user->suffix ?? '';
      $data['employee_number'] = $user->employee_number;
      $data['deployment'] = $user->detachment->id;
      $data['employee_id'] = $user->id;
      $data['submitted_by'] = $user->id;
      $form_name = 'Requirement Transmittal Form';
      $user = Auth::user();

      // Step 1: Create the new form record
      $form = RequirementTransmittalForm::create($data);

      // Step 2: Create a new submission record that points to the form.
      // The morphOne relationship makes this easy.
      $submission = $form->submission()->create([
        'user_id' => auth()->id(), // Automatically get the logged-in user's ID
      ]);

      //roles to notify
      $roles = [
        'accounting manager',
        'senior accounting manager',
        'accounting specialist',
        'hr manager',
        'hr specialist'
      ];
      $to_notify = User::whereHas('roles', function ($query) use ($roles) {
        $query->whereIn('name', $roles);
      })->pluck('id')->toArray();
      $to_notify[] = Auth::id();

      //parameters to send
      $message = ucfirst($user->name) . " submitted a $form_name.";
      $users_id = $to_notify; // staff
      $form_type = str_replace(' ', '-', strtolower($form_name));
      $form_id = $form->id;
      $formatted_date = Carbon::parse($submission->created_at)->format('Y-m-d, H:i:s');

      //dd(compact('message', 'user_id', 'form_type', 'form_id', 'formatted_date'));

      //send the notification
      event(new NotificationSent($message, $users_id, $form_type, $form_id, $formatted_date));

      // Return a success JSON response for the AJAX call
      return response()->json(['message' => 'Success'], 201);

    } catch (\Exception $e) {
      // Return an error JSON response
      return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
    }
  }

  public function update(StoreRequirementTransmittalFormRequest $request, $type, $id): JsonResponse
  {
    try {
      $data = $request->validated();

      // Step 1: Create the new form record
      $form = RequirementTransmittalForm::find($id)->update($data);

      // Return a success JSON response for the AJAX call
      return response()->json(['message' => 'Success'], 201);

    } catch (\Exception $e) {
      // Return an error JSON response
      return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
    }
  }

  public function approve(Request $request)
  {
    try {
      $request->validate([
        'id' => 'required|integer',
        'status' => "required|string",
        'denial_reason' => 'nullable|string',
        'qualified_for_loan' => 'nullable|boolean',
        'complete_requirements' => 'nullable|boolean',
        'form_type' => 'required|string',
      ]);

      $id = $request->id;
      $status = $request->status;
      $qualified_for_loan = $request->qualified_for_loan;
      $complete_requirements = $request->complete_requirements;
      $form_type = $request->form_type;

      $form = RequirementTransmittalForm::find($id);

      if($form->submitted_by == Auth::id()){
        return response()->json(['message' => 'Error', 'error' => 'You cannot deny/approve your own form.'], 401);
      }

      $user = Auth::user();
      if($status == 'approved'){
        $form->approved_by = $user->id;
        $form->date_approved = Carbon::now();
        $form->denied_by = null;
        $form->date_denied = null;
        $denial_reason = null;
      } else{
        $form->approved_by = null;
        $form->date_approved = null;
        $form->denied_by = $user->id;
        $form->date_denied = Carbon::now();
        $denial_reason = $request->denial_reason;
      }

      $form->denial_reason = $denial_reason;
      $form->status = $status;
      $form->qualified_for_loan = $qualified_for_loan;
      $form->complete_requirements = $complete_requirements;
      $form->save();

      //parameters to send
      $message = ucfirst($user->name) . " has $status your $form_type request.";
      $users_id = [ $form->submitted_by ]; // submitted by
      $form_id = $form->id;
      $form_type = str_replace(' ', '-', strtolower($form_type));
      $formatted_date = Carbon::parse( now()->format('Y-m-d, H:i:s'))->format('Y-m-d, H:i:s');

      //send the notification
      event(new NotificationSent($message, $users_id, $form_type, $form_id, $formatted_date));

    } catch (\Exception $e) {
      return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
    }

    return response()->json(['message' => 'Success'], 201);
  }

  public function print($id)
  {
    $submittable = Submission::where('submittable_id', $id)->first();
    if(!$submittable){
      return view('content.pages.pages-misc-error');
    }

    $submission = $submittable->submittable;
    $user = $submittable->user;
    $approved_by = User::find($submission->approved_by);
    $denied_by = User::find($submission->denied_by);
    $detachment = Detachment::find($user->detachment_id);
    $roles = Role::where('name', '!=', 'root')
      ->whereNotIn('name', ['root', 'president', 'vice president', 'general manager'])->get();

    return view('content.forms.to_print')
      ->with('user', $user)
      ->with('submitted_by', $submittable->user)
      ->with('approved_by', $approved_by)
      ->with('denied_by', $denied_by)
      ->with('detachment', $detachment)
      ->with('submission', $submission)
      ->with('roles', $roles);
  }

  public function printReport($form, string $id)
  {

    if(!Auth::user()->can('print '. str_replace(' ', '-', strtolower($form)))){
      return response()->json(['message' => 'Error', 'error' => 'You do not have permission to print the form.'], 401);
    }

    $submission = Submission::where('submittable_id', $id)->first();
    $form = $submission->submittable;
    $form->date_last_printed = now();
    $form->printed = true;
    $form->last_printed_by = Auth::id();
    $form->times_printed++;
    $form->save();
    return true;
  }
}
