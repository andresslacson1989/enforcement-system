<?php

namespace App\Http\Classes;

use App\Models\Detachment;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FormClass
{
    public function new($form)
    {
        $form_name = str_replace('-', ' ', $form);
        if ($form_name === 'requirement transmittal form') {
            $user = Auth::user();
            $detachments = Detachment::all();

            if (! $user->can('view '.$form_name)) {
                return view('content.pages.pages-misc-error');
            }

            return view('content.forms.'.$form)
                ->with('form_name', $form_name)
                ->with('user', $user)
                ->with('detachments', $detachments)
                ->with('submission', false);

        } elseif ($form_name == 'first month performance evaluation form') {
            $user = Auth::user();
            $roleNames = ['security guard', 'security office', 'head guard'];
            $guards = User::whereHas('roles', function ($query) use ($roleNames) {
                $query->whereIn('name', $roleNames);
            })->where('detachment_id', $user->detachment_id)->get();
            $detachment = $user->detachment;
            $dc = User::find($detachment->assigned_officer);

            if (! $user->can('view '.$form_name)) {
                return view('content.pages.pages-misc-error');
            }

            return view('content.forms.'.$form)
                ->with('form_name', $form_name)
                ->with('user', $user)
                ->with('submission', false)
                ->with('guards', $guards)
                ->with('deployment', $detachment)
                ->with('dc', $dc);
        }

        return view('content.pages.pages-misc-error');
    }

    public function view($form, $id)
    {
        try {
            $user = Auth::user();
            $detachments = Detachment::all();
            $submittable = Submission::where('submissions.submittable_id', $id)->first();
            $submission = $submittable->submittable ?? false;
            $employee = User::find($submission->employee_id);
            $submitted_by = $submittable->user;
            $form_name = str_replace('-', ' ', $form);

            // choose between forms
            switch ($form) {
                case 'requirement-transmittal-form':
                    // check if user have permission to view the form
                    if (! $user->can('view requirement transmittal form')) {
                        return view('content.pages.pages-misc-error');
                    }

                    return view('content.forms.requirement-transmittal-form')
                        ->with('user', $user)
                        ->with('employee', $employee)
                        ->with('submitted_by', $submitted_by)
                        ->with('detachments', $detachments)
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
                        ->with('form_name', $form_name)
                        ->with('employee', $employee);

                default:
                    return view('content.pages.pages-misc-error');
                    break;
            }
        } catch (\Exception $exception) {
            return view('content.pages.pages-misc-error');
        }
    }

    public function sendErrorMessage(\Exception $e): JsonResponse
    {
        Log::error($e->getMessage());

        return response()->json(['message' => 'Failed', 'icon' => 'error', 'text' => 'Something went wrong.'], 500);
    }
}
