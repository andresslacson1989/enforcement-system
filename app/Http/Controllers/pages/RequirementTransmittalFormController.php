<?php

namespace App\Http\Controllers\pages;

use App\Http\Classes\FormClass;
use App\Jobs\SendAndBroadcastNotification;
use App\Models\RequirementTransmittalForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequirementTransmittalFormController
{
    public function remarks(Request $request)
    {
        foreach ($request->all() as $key => $item) {
            if ($item == 'on') {
                $request->request->set($key, true);
            }
        }

        try {
            $request->validate([
                'form_id' => 'required|integer',
                'qualified_for_loan' => 'sometimes|boolean',
                'complete_requirements' => 'sometimes|boolean',
                'form_type' => 'required|string',
            ]);

            $id = $request->form_id;
            $form_type = $request->form_type;
            $user = Auth::user();

            $form = RequirementTransmittalForm::find($id);
            $form->qualified_for_loan = $request->qualified_for_loan ?? false;
            $form->complete_requirements = $request->complete_requirements ?? false;
            $form->save();

            // parameters to send
            $form_name = 'Requirement Transmittal Form';
            $users_id = [$form->employee_id]; // submitted by and employee
            $form_id = $form->id;
            $title = $form_name;
            $message = ucfirst($user->name)." has updated  $form_type request.";
            $form_type = str_replace(' ', '-', strtolower($form_name));
            $link = "/form/view/$form_type/$form_id";

            // Dispatch the job
            SendAndBroadcastNotification::dispatch($title, $message, $link, $users_id);

        } catch (\Exception $e) {
            return (new FormClass)->sendErrorMessage($e);
        }

        return response()->json(['message' => 'Success', 'text' => 'Successfully updated remarks', 'icon' => 'success', 'form_id' => $form->id, 'form_name' => $form_type]);
    }

    public function qualified()
    {
        dd('test');
    }
}
