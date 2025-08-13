<?php

namespace App\Http\Controllers\pages;

use App\Events\NotificationSent;
use App\Http\Classes\UserClass;
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
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RequirementTransmittalFormController
{
    public function store(StoreRequirementTransmittalFormRequest $request): JsonResponse
    {
        $user = Auth::user();
        $data = $request->validated();
        $form_name = 'Requirement Transmittal Form';

        // Create the Employee Profile First
        $employee_name = $data['first_name'].' '.$data['last_name'].' '.($data['suffix'] ?? '');
        $employee_data = [
            'name' => $employee_name,
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'],
            'last_name' => $data['last_name'],
            'suffix' => $data['suffix'],
            'phone_number' => $data['phone_number'],
            'street' => $data['street'],
            'city' => $data['city'],
            'province' => $data['province'],
            'zip_code' => $data['zip_code'],
            'detachment_id' => $data['deployment'],
            'employee_number' => $data['employee_number'],
            'password' => HASH::make('esiai'.$data['employee_number']),
            'email' => $data['email'],
            'email_verified_at' => Carbon::now(),
        ];

        $employee = (new UserClass)->create($employee_data);
        // give role to new employee
        // TODO: make role dynamic
        $employee->assignRole('security guard');

        // Additional info from employee to Request
        $data['employee_number'] = $employee['employee_number'];
        $data['employee_name'] = $employee_name;
        $data['employee_id'] = $employee->id;
        $data['submitted_by'] = $user->id;

        // Create the Requirement Transmittal and associate it with the new employee
        // Create the new form record
        $form = RequirementTransmittalForm::create($data);

        // Create a new submission record that points to the form.
        // The morphOne relationship makes this easy.
        $submission = $form->submission()->create([
            'user_id' => auth()->id(), // Automatically get the logged-in user's ID
        ]);

        // Add Requirement Transmittal Form id to employee
        $employee->requirement_transmittal_form_id = $form->id;
        $employee->save();

        // roles to notify
        $roles = [
            'accounting manager',
            'senior accounting manager',
            'accounting specialist',
            'hr manager',
            'hr specialist',
            'operation manager',
        ];
        $to_notify = User::whereHas('roles', function ($query) use ($roles) {
            $query->whereIn('name', $roles);
        })->pluck('id')->toArray();
        $to_notify[] = $employee->id;

        // parameters to send
        $title = $form_name;
        $message = ucfirst($user->name)." submitted a $form_name of $employee_name.";
        $users_id = $to_notify; // staff
        $form_type = str_replace(' ', '-', strtolower($form_name));
        $form_id = $form->id;
        $link = "/form/view/$form_type/$form_id";
        $formatted_date = Carbon::parse($submission->created_at)->format('Y-m-d, H:i:s');

        // dd(compact('message', 'user_id', 'form_type', 'form_id', 'formatted_date'));

        // send the notification
        event(new NotificationSent($title, $message, $link, $users_id, $form_type, $form_id, $formatted_date));

        // Return a success JSON response for the AJAX call
        return response()->json(['message' => 'Success', 'form_id' => $form->id], 201);

    }

    public function update(StoreRequirementTransmittalFormRequest $request, $type, $id): JsonResponse
    {
        try {
            $data = $request->validated();

            // Find and update the form record
            $form = RequirementTransmittalForm::find($id)->update($data);

            // Return a success JSON response for the AJAX call
            return response()->json(['message' => 'Success', 'form_id' => $id], 201);

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
                'status' => 'required|string',
                'qualified_for_loan' => 'nullable|boolean',
                'complete_requirements' => 'nullable|boolean',
                'form_type' => 'required|string',
            ]);

            $id = $request->id;
            $status = $request->status;
            $qualified_for_loan = $request->qualified_for_loan;
            $complete_requirements = $request->complete_requirements;
            $form_type = $request->form_type;
            $user = Auth::user();

            $form = RequirementTransmittalForm::find($id);
            $form->status = $status;
            $form->qualified_for_loan = $qualified_for_loan;
            $form->complete_requirements = $complete_requirements;
            $form->save();

            // parameters to send
            $message = ucfirst($user->name)." has $status your $form_type request.";
            $users_id = [$form->submitted_by]; // submitted by
            $form_id = $form->id;
            $form_type = str_replace(' ', '-', strtolower($form_type));
            $formatted_date = Carbon::parse(now()->format('Y-m-d, H:i:s'))->format('Y-m-d, H:i:s');

            // send the notification
            event(new NotificationSent($message, $users_id, $form_type, $form_id, $formatted_date));

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Success', 'form_id' => $form->id], 201);
    }

    public function print($id)
    {
        $submittable = Submission::where('submittable_id', $id)->first();
        if (! $submittable) {
            return view('content.pages.pages-misc-error');
        }

        $submission = $submittable->submittable;
        $user = Auth::user();
        $employee = User::find($submission->employee_id);
        $detachment = Detachment::find($employee->detachment_id);
        $roles = Role::where('name', '!=', 'root')
            ->whereNotIn('name', ['root', 'president', 'vice president', 'general manager'])->get();

        return view('content.forms.to_print')
            ->with('user', $user)
            ->with('employee', $employee)
            ->with('submitted_by', $submittable->user)
            ->with('detachment', $detachment)
            ->with('submission', $submission)
            ->with('roles', $roles);
    }

    public function printReport($form, string $id)
    {

        if (! Auth::user()->can('print '.str_replace(' ', '-', strtolower($form)))) {
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
