<?php

namespace App\Http\Controllers\pages;

use App\Jobs\SendAndBroadcastNotification;
use App\Models\RequirementTransmittalForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequirementTransmittalFormController
{
    public function remarks(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer',
                'qualified_for_loan' => 'nullable|boolean',
                'complete_requirements' => 'nullable|boolean',
                'form_type' => 'required|string',
            ]);

            $id = $request->id;
            $qualified_for_loan = $request->qualified_for_loan;
            $complete_requirements = $request->complete_requirements;
            $form_type = $request->form_type;
            $user = Auth::user();

            $form = RequirementTransmittalForm::find($id);
            $form->qualified_for_loan = $qualified_for_loan;
            $form->complete_requirements = $complete_requirements;
            $form->save();

            // parameters to send
            $form_name = 'Requirement Transmittal Form';
            $users_id = [$form->submitted_by, $form->employee_id]; // submitted by and employee
            $form_id = $form->id;
            $title = $form_name;
            $message = ucfirst($user->name)." has updated  $form_type request.";
            $form_type = str_replace(' ', '-', strtolower($form_name));
            $link = "/form/view/$form_type/$form_id";

            // Dispatch the job
            SendAndBroadcastNotification::dispatch($title, $message, $link, $users_id);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Success', 'form_id' => $form->id]);
    }
}
