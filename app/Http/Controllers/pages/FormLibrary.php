<?php

namespace App\Http\Controllers\pages;

use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class FormLibrary extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $detachment = $user->detachment;

        // Get the form configurations from our updated file
        $formTypes = Config::get('forms.types');

        // Dynamically create the permission map using the config slugs.
        $permissionMap = [];
        foreach ($formTypes as $slug => $config) {
            // The key of our map is the model's class name
            $modelClass = $config['model'];

            // The value is the permission string, built directly from the slug.
            // This turns 'first-month-performance-evaluation' into 'view first month performance evaluation form'.
            $permissionString = 'view '.str_replace('-', ' ', $slug).' form';

            $permissionMap[$modelClass] = $permissionString;
        }

        $allowedTypes = [];
        foreach ($permissionMap as $type => $permission) {
            if ($user->can(config('permit.'.$permission.'name'))) { // <-- This part remains the same
                $allowedTypes[] = $type;
            }
        }

        $cacheKey = 'user_submissions:'.$user->id;
        $forms = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($user, $allowedTypes) {
            return Submission::with([
                'submittable',
                'submittable.submittedBy' => function ($query) {
                    $query->withTrashed();
                },
            ])
                ->where('submitted_by', $user->id)
                ->whereIn('submittable_type', $allowedTypes)
                ->latest()
                ->get();
        });

        // Add a user-friendly form type name to each submission for the view
        $forms->each(function ($forms) {
            $forms->form_type_name = Str::of(class_basename($forms->submittable_type))
                ->replace('Form', '')
                ->snake(' ')
                ->title();
        });

        return view('content.pages.form-library')
            ->with('forms', $forms)
            ->with('user', $user)
            ->with('detachment', $detachment);
    }
}
