<?php

namespace App\Http\Handlers;

use App\Http\Classes\UserClass;
use App\Interfaces\FormHandlerInterface;
use App\Models\Detachment;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class IdApplicationFormHandler implements FormHandlerInterface
{
    protected string $form_slug = 'id-application-form';
    protected string $form_name = 'ID Application Form';

    public function create(): View
    {
        $user = Auth::user();
        $roleNames = (new UserClass)->listPersonnelBaseRoles();

        $guardsQuery = User::whereHas('roles', function ($query) use ($roleNames) {
            $query->whereIn('name', $roleNames);
        })->where('detachment_id', '!=', null);

        $unrestrictedRoles = ['hr manager', 'hr specialist', 'operation manager', 'general manager', 'president', 'vice president', 'root'];
        if (! $user->hasAnyRole($unrestrictedRoles) && $user->detachment_id) {
            $guardsQuery->where('detachment_id', $user->detachment_id);
        }

        $guards = $guardsQuery->get();
        $detachment = $user->detachment;

        return view('content.forms.'.$this->form_slug)
            ->with('form_name', $this->form_name)
            ->with('user', $user)
            ->with('submission', false)
            ->with('guards', $guards)
            ->with('deployment', $detachment);
    }

    public function view(int $id): View
    {
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
        $formModelClass = config('forms.types.'.$this->form_slug.'.model');
        $submission = $formModelClass::findOrFail($id);

        $submissionRecord = Submission::where('submittable_id', $id)
            ->where('submittable_type', $formModelClass)
            ->with('submittedBy')
            ->firstOrFail();

        $employee = User::findOrFail($submission->employee_id);
        $detachment = Detachment::find($employee->detachment_id);

        return view('content.forms.to-print')
            ->with('user', $user)
            ->with('employee', $employee)
            ->with('submitted_by', $submissionRecord->submittedBy)
            ->with('detachment', $detachment)
            ->with('submission', $submission)
            ->with('form_type', $this->form_slug);
    }
}