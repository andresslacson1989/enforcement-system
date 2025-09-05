<?php

namespace App\Http\Handlers;

use App\Models\Detachment;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Tags\Tag;

class PersonnelRequisitionFormHandler
{
    protected string $form_slug = 'personnel-requisition-form';

    // It's better to use the consistent name from the config file.
    protected string $form_name = 'Personnel Requisition Form';

    public array $personnel_types = ['Security Officer', 'Guard', 'EMT/First Aiders', 'Protection Agents', 'CCTV Operator', 'Security Escort'];

    public array $purposes = ['New Client', 'Additional Posting', 'Seasonal/Augmentation', 'Reliever', 'Replacement', 'Special Project'];

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = Auth::user();

        // Using the property now, which is consistent with the config.
        if (! $user->can(config("permit.fill  $this->form_name.name"))) {
            // It's better to use Laravel's authorization.
            // We can refactor this to a FormRequest or Gate later.
            abort(403, 'You do not have permission to view this form.');
        }
        // Fetch all detachments to populate the client dropdown
        $detachments = Detachment::all();

        // Fetch all existing tags to populate the skills dropdown
        $all_tags = Tag::all();

        // populate types of personnel

        return view('content.forms.personnel-requisition-form')
            ->with('form_name', $this->form_name)
            ->with('detachments', $detachments)
            ->with('all_tags', $all_tags)
            ->with('purposes', $this->purposes)
            ->with('personnel_types', $this->personnel_types)
            ->with('submission', false);
    }

    public function view(int $id): View
    {
        $user = Auth::user();

        if (! $user->can(config("permit.view  $this->form_name.name"))) {
            // It's better to use Laravel's authorization.
            // We can refactor this to a FormRequest or Gate later.
            return view('content.pages.pages-misc-error');
        }

        // Find the specific form model by its ID, and fail if not found.
        $formModelClass = config('forms.types.'.$this->form_slug.'.model');
        $submission = $formModelClass::findOrFail($id);

        // Eager load relationships for better performance (prevents N+1 query problems)
        $submissionRecord = Submission::where('submittable_id', $id)
            ->where('submittable_type', $formModelClass)
            ->with('submittedBy') // Eager load the user who submitted
            ->firstOrFail();
        $all_tags = Tag::all();

        $employee = User::find($submission->employee_id);
        $detachments = Detachment::all();

        return view('content.forms.'.$this->form_slug)
            ->with('submission', $submission)
            ->with('purposes', $this->purposes)
            ->with('all_tags', $all_tags)
            ->with('personnel_types', $this->personnel_types)
            ->with('submitted_by', $submissionRecord->submittedBy)
            ->with('detachments', $detachments)
            ->with('employee', $employee)
            ->with('form_name', $this->form_name)
            ->with('user', $user);
    }
}
