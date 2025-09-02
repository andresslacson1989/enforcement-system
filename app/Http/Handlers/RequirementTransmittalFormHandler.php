<?php

namespace App\Http\Handlers;

use App\Interfaces\FormHandlerInterface;
use App\Models\Detachment;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class RequirementTransmittalFormHandler implements FormHandlerInterface
{
    protected string $form_slug = 'requirement-transmittal-form';

    // It's better to use the consistent name from the config file.
    protected string $form_name = 'Requirement Transmittal Form';

    public function create(): View
    {
        $user = Auth::user();

        // Using the property now, which is consistent with the config.
        if (! $user->can('view '.$this->form_name)) {
            // It's better to use Laravel's authorization.
            // We can refactor this to a FormRequest or Gate later.
            abort(403, 'You do not have permission to view this form.');
        }

        $detachments = Detachment::all();

        return view('content.forms.'.$this->form_slug)
            ->with('form_name', $this->form_name)
            ->with('user', $user)
            ->with('detachments', $detachments)
            ->with('submission', false);
    }

    public function view(int $id): View
    {
        $user = Auth::user();

        if (! $user->can('view '.$this->form_name)) {
            abort(403, 'You do not have permission to view this form.');
        }

        // Find the specific form model by its ID, and fail if not found.
        $formModelClass = config('forms.types.'.$this->form_slug.'.model');
        $submission = $formModelClass::findOrFail($id);

        // Eager load relationships for better performance (prevents N+1 query problems)
        $submissionRecord = Submission::where('submittable_id', $id)
            ->where('submittable_type', $formModelClass)
            ->with('submittedBy') // Eager load the user who submitted
            ->firstOrFail();

        $employee = User::find($submission->employee_id);
        $detachments = Detachment::all();

        return view('content.forms.'.$this->form_slug)
            ->with('submission', $submission)
            ->with('submitted_by', $submissionRecord->submittedBy)
            ->with('detachments', $detachments)
            ->with('employee', $employee)
            ->with('form_name', $this->form_name)
            ->with('user', $user);
    }

    public function print(int $id): View
    {
        $user = Auth::user();

        // You should have a specific permission for printing
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
        $detachment = Detachment::find($employee->detachment_id);

        $roles = Role::whereNotIn('name', ['root', 'president', 'vice president', 'general manager'])->get();

        return view('content.forms.to-print')
            ->with('user', $user)
            ->with('employee', $employee)
            ->with('submitted_by', $submissionRecord->submittedBy)
            ->with('detachment', $detachment)
            ->with('submission', $submission)
            ->with('roles', $roles)
            ->with('form_type', $this->form_slug);
    }
}
