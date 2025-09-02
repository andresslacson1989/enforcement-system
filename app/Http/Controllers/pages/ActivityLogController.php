<?php

namespace App\Http\Controllers\pages;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ActivityLogController extends Controller
{
    public function logs(Request $request)
    {
        $user = Auth::user();
        // Corrected the permission name to match your permit.php config
        if (! $user->can(config('permit.view activity logs menu.name'))) {
            return response()->view('content.pages.pages-misc-error');
        }

        // Since DataTables is handling filtering on the client-side,
        // we can simplify the query to just fetch all logs.
        // Eager load the 'user' relationship to prevent N+1 query issues.
        $logs = ActivityLog::with('user')->latest()->get();

        // --- DYNAMICALLY BUILD THE FILTERABLE MODELS LIST ---

        // 1. Start with the non-form models that have logs
        $filterableModels = [
            'User',
            'Detachment',
            'Role',
        ];

        // 2. Get all form models from the configuration
        $formTypes = config('forms.types');

        // 3. Loop through and add the base name of each form model
        foreach ($formTypes as $form) {
            if (isset($form['model'])) {
                $filterableModels[] = Str::headline(class_basename($form['model']));
            }
        }

        // 4. Ensure the list is unique and sorted alphabetically
        $filterableModels = array_unique($filterableModels);
        // sort($filterableModels);

        return view('activity-logs.activity-logs', compact('logs', 'filterableModels'));
    }
}
