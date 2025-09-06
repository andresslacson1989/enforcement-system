<?php

namespace App\Http\Handlers;

use App\Models\PersonnelLeaveApplicationForm;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;

class PersonnelLeaveApplicationFormHandler
{
    protected string $form_slug = 'personnel-leave-application-form';

    protected string $form_name = 'Personnel Leave Application Form';

    /**
     * Show the form creation view.
     */
    public function create(): View|Factory
    {
        // Authorization check
        Gate::authorize('fill personnel leave application form');

        $user = auth()->user();
        $relievers = collect();

        // Fetch colleagues from the same detachment, excluding the user themselves.
        if ($user->detachment_id) {
            $relievers = User::where('detachment_id', $user->detachment_id)
                ->where('id', '!=', $user->id)
                ->where('status', 'hired') // Only show active personnel
                ->orderBy('name')
                ->get();
        }

        return view('content.forms.personnel-leave-application-form')
            ->with('relievers', $relievers)
            ->with('form_name', $this->form_name)
            ->with('submission', false);
    }

    /**
     * Show a specific submitted form instance.
     */
    public function view(int $id): View|Factory
    {
        $form = PersonnelLeaveApplicationForm::with(['user', 'submission.submitter', 'reliever'])->findOrFail($id);

        // Authorization check (e.g., can the user view this specific submission?)
        // Gate::authorize('view', $form);

        $relievers = collect();
        // In view mode, we still need to populate the list in case it needs to be displayed,
        // though the field will be disabled.
        if ($form->user->detachment_id) {
            $relievers = User::where('detachment_id', $form->user->detachment_id)
                ->where('id', '!=', $form->user_id)
                ->orderBy('name')
                ->get();
        }

        return view('content.forms.personnel-leave-application-form', compact('form', 'relievers'));
    }

    /**
     * Show the printable version of the form.
     */
    public function print(int $id): View|Factory
    {
        $form = PersonnelLeaveApplicationForm::with(['user'])->findOrFail($id);

        // Authorization check
        // Gate::authorize('print', $form);

        // You would create a specific print view for this form
        // return view('content.prints.personnel-leave-application-form-print', compact('form'));
        abort(501, 'Print view for this form is not yet implemented.');
    }
}
