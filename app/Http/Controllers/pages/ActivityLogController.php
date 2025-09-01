<?php

namespace App\Http\Controllers\pages;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function logs(Request $request)
    {
        $user = Auth::user();
        if (! $user->can(config('permit.view logs.name'))) {
            return response()->view('content.pages.pages-misc-error');
        }

        $query = ActivityLog::with('user', 'loggable')
            ->latest(); // Order by most recent first

        // Filter by Model Type
        if ($request->filled('model')) {
            // You would need to map the friendly name to the actual model class
            $modelClass = 'App\\Models\\'.$request->input('model');
            $query->where('loggable_type', $modelClass);
        }

        // Filter by Date Range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $logs = $query->paginate(25);

        // A list of models you want to be able to filter by
        $filterableModels = [
            'Detachment',
            'User',
            'RequirementTransmittalForm',
        ];

        return view('activity-logs.logs', compact('logs', 'filterableModels'));
    }
}
