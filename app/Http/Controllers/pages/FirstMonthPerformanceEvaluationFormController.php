<?php

namespace App\Http\Controllers\pages;

use App\Http\Classes\FormClass;
use App\Models\FirstMonthPerformanceEvaluationForm;
use App\Models\Submission;
use App\Models\User;
use App\Services\FormSubmissionService;
use Illuminate\Support\Facades\Auth;

class FirstMonthPerformanceEvaluationFormController
{
    protected FormSubmissionService $formSubmissionService;

    public function __construct(FormSubmissionService $formSubmissionService)
    {
        $this->formSubmissionService = $formSubmissionService;
    }

    public function approve(string $id)
    {
        if (! Auth::user()->can(config('permit.approve first month performance evaluation form.name'))) {
            return response('Unauthorized.', 401);
        }

        try {
            $form = FirstMonthPerformanceEvaluationForm::find($id);
            $form->status = 'approved';
            $form->save();

            return response()->json(['message' => 'Success', 'text' => 'Evaluation approved successfully', 'icon' => 'success']);

        } catch (\Exception $e) {
            return (new FormClass)->sendErrorMessage($e);
        }
    }

    public function print(string $id)
    {
        $submittable = Submission::where('submittable_id', $id)->first();
        if (! $submittable) {
            return view('content.pages.pages-misc-error');
        }
        $submission = $submittable->submittable;
        $user = Auth::user();
        $employee = User::find($submission->employee_id);

        return view('content.forms.to-print');
    }
}
