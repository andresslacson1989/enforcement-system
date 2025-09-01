<?php

namespace App\Http\Handlers\PerformanceEvaluationForms;

use App\Http\Classes\UserClass;
use App\Interfaces\FormHandlerInterface;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

abstract class BasePerformanceEvaluationFormHandler implements FormHandlerInterface
{
    protected string $form_slug;

    protected string $form_name;

    public function create(): View
    {
        $user = Auth::user();
        $roleNames = (new UserClass)->listPersonnelBaseRoles();

        $guardsQuery = User::whereHas('roles', function ($query) use ($roleNames) {
            $query->whereIn('name', $roleNames);
        })
            ->where('detachment_id', '!=', null);

        $unrestrictedRoles = ['hr manager', 'hr specialist', 'operation manager', 'general manager', 'president', 'vice president', 'root'];
        if (! $user->hasAnyRole($unrestrictedRoles) && $user->detachment_id) {
            $guardsQuery->where('detachment_id', $user->detachment_id);
        }

        $guards = $guardsQuery->get();

        $detachment = $user->detachment;
        $dc = $detachment ? User::find($detachment->assigned_officer) : null;

        if (! $user->can('view '.$this->form_name)) {
            abort(403, 'You do not have permission to view this form.');
        }

        return view('content.forms.'.$this->form_slug)
            ->with('form_name', $this->form_name)
            ->with('user', $user)
            ->with('submission', false)
            ->with('guards', $guards)
            ->with('deployment', $detachment)
            ->with('dc', $dc);
    }

    public function view(int $id): View
    {
        $user = Auth::user();

        if (! $user->can('view '.$this->form_name)) {
            abort(403, 'You do not have permission to view this form.');
        }

        $formModelClass = config('forms.types.'.$this->form_slug.'.model');
        $submission = $formModelClass::findOrFail($id);

        $submissionRecord = Submission::where('submittable_id', $id)
            ->where('submittable_type', $formModelClass)
            ->with('submittedBy')
            ->firstOrFail();

        $employee = User::find($submission->employee_id);

        return view('content.forms.'.$this->form_slug)
            ->with('submission', $submission)
            ->with('submitted_by', $submissionRecord->submittedBy)
            ->with('employee', $employee)
            ->with('form_name', $this->form_name);
    }

    public function print(int $id): View
    {
        $user = Auth::user();

        if (! $user->can('print '.$this->form_name)) {
            abort(403, 'You do not have permission to print this form.');
        }

        $formModelClass = config('forms.types.'.$this->form_slug.'.model');
        $submission = $formModelClass::findOrFail($id);

        $submissionRecord = Submission::where('submittable_id', $id)
            ->where('submittable_type', $formModelClass)
            ->with('submittedBy')
            ->firstOrFail();

        $employee = User::findOrFail($submission->employee_id);

        return view('content.forms.to-print')
            ->with('form_type', $this->form_slug)
            ->with('submission', $submission)
            ->with('employee', $employee)
            ->with('user', $user) // Pass the authenticated user
            ->with('submitted_by', $submissionRecord->submittedBy);
    }
}
