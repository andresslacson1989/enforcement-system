<?php

namespace App;

use App\Models\Detachment;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FormClass
{
    public function new($form)
    {
        $form_name = str_replace('-', ' ', $form);
        if ($form_name === 'requirement transmittal form') {
            $user = Auth::user();
            $detachment = Detachment::find($user->detachment_id);

            if (! $user->can('view '.$form_name)) {
                return view('content.pages.pages-misc-error');
            }

            return view('content.forms.'.$form)
                ->with('form_name', $form_name)
                ->with('user', $user)
                ->with('detachment', $detachment)
                ->with('submission', false);

        } elseif ($form_name == 'first month performance evaluation form') {
            $user = Auth::user();
            $roleNames = ['security guard', 'security office', 'head guard'];
            $guards = User::whereHas('roles', function ($query) use ($roleNames) {
                $query->whereIn('name', $roleNames);
            })->get();
            $detachments = Detachment::all();
            $dc = $detachments->pluck('commander')->toArray();
            $dc = User::whereIn('id', $dc)->get();
            if (! $user->can('view '.$form_name)) {
                return view('content.pages.pages-misc-error');
            }

            return view('content.forms.'.$form)
                ->with('form_name', $form_name)
                ->with('user', $user)
                ->with('submission', false)
                ->with('guards', $guards)
                ->with('deployment', $detachments)
                ->with('dc', $dc);
        }

        return view('content.pages.pages-misc-error');
    }

    public function view($form, $id)
    {
      $form_name = str_replace('-', ' ', $form);
        try {
          $user = Auth::user();
          $detachment = Detachment::find($user->detachment_id);

          $submittable = Submission::where('submissions.submittable_id', $id)->first();
          $submission = $submittable->submittable ?? false;
          $submitted_by = $submittable->user;
          $approved_by = User::find($submission->approved_by);
          $form_name = str_replace('-', ' ', $form);

          //choose between forms
          switch ($form) {
            case 'requirement-transmittal-form':
              // check if user have permission to view the form
              if (! $user->can('view requirement transmittal form')) {
                return view('content.pages.pages-misc-error');
              }

              return view('content.forms.requirement-transmittal-form')
                ->with('user', $user)
                ->with('submitted_by', $submitted_by)
                ->with('approved_by', $approved_by)
                ->with('detachment', $detachment)
                ->with('submission', $submission)
                ->with('form_name', $form_name);

            case 'first-month-performance-evaluation-form':
              // check if user have permission to view the form
              if (! $user->can('view first month performance evaluation form')) {
                return view('content.pages.pages-misc-error');
              }

              return view('content.forms.first-month-performance-evaluation-form')
                ->with('user', $user)
                ->with('submitted_by', $submitted_by)
                ->with('submission', $submission)
                ->with('form_name', $form_name);

            default:
              return view('content.pages.pages-misc-error');
              break;
          }
        } catch (\Exception $exception) {
          return view('content.pages.pages-misc-error');
        }
    }
}
