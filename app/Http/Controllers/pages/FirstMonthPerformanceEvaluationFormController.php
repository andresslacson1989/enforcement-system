<?php

namespace App\Http\Controllers\pages;

use App\Events\NotificationSent;
use App\Http\Requests\StoreFirstMonthPerformanceEvaluationForm;
use App\Models\FirstMonthPerformanceEvaluationForm;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FirstMonthPerformanceEvaluationFormController
{
    public function store(StoreFirstMonthPerformanceEvaluationForm $request)
    {
        // The request has already been validated by the StoreEvaluationRequest class.
        // We can access the validated data directly.
        $validatedData = $request->validated();
        try {
            // Create a new Evaluation record
            $form = FirstMonthPerformanceEvaluationForm::create([
                'employee_id' => $validatedData['employee'],
                'employee_number' => $validatedData['employee_number'] ?? null,
                'job_title' => $validatedData['job_title'] ?? null,
                'deployment' => $validatedData['deployment'],
                'supervisor' => $validatedData['supervisor'],
                'overall_standing' => $validatedData['overall_standing'],
                'knowledge_understanding_a' => $validatedData['knowledge_understanding_a'] ?? null,
                'knowledge_understanding_b' => $validatedData['knowledge_understanding_b'] ?? null,
                'attendance_a' => $validatedData['attendance_a'] ?? null,
                'attendance_b' => $validatedData['attendance_b'] ?? null,
                'observation_a' => $validatedData['observation_a'] ?? null,
                'observation_b' => $validatedData['observation_b'] ?? null,
                'communication_a' => $validatedData['communication_a'] ?? null,
                'communication_b' => $validatedData['communication_b'] ?? null,
                'professionalism_a' => $validatedData['professionalism_a'] ?? null,
                'professionalism_b' => $validatedData['professionalism_b'] ?? null,
                'strength_1' => $validatedData['strength_1'] ?? null,
                'strength_2' => $validatedData['strength_2'] ?? null,
                'strength_3' => $validatedData['strength_3'] ?? null,
                'improvement_1' => $validatedData['improvement_1'] ?? null,
                'improvement_2' => $validatedData['improvement_2'] ?? null,
                'improvement_3' => $validatedData['improvement_3'] ?? null,
                'supervisor_comment' => $validatedData['supervisor_comment'] ?? null,
                'security_comment' => $validatedData['security_comment'] ?? null,
            ]);

            $submission = $form->submission()->create([
                'user_id' => auth()->id(),
            ]);
            $roles_to_notify = User::whereHas('roles', function ($query) {
              $query->whereIn('name', ['hr manager', 'hr specialist', 'operation manager']);
            })->get()->pluck('id')->toArray();
            $roles_to_notify[] = $form->employee_id;
            $user = Auth::user();
            $form_name = 'First Month Performance Evaluation Form';

            // parameters to send
            $message = ucfirst($user->name)." has submitted your $form_name.";
            $users_id = $roles_to_notify; // employee under evaluation
            $form_type = str_replace(' ', '-', strtolower($form_name));
            $form_id = $form->id;
            $formatted_date = Carbon::parse($submission->created_at)->format('Y-m-d, H:i:s');

            // send the notification
            event(new NotificationSent($message, $users_id, $form_type, $form_id, $formatted_date));

            return response()->json(['message' => 'Evaluation submitted successfully!'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while saving the evaluation.', 'error' => $e->getMessage()], 500);
        }
    }
}
