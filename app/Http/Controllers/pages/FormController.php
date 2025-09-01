<?php

namespace App\Http\Controllers\pages;

use App\Helpers\NotificationHelper;
use App\Http\Classes\UserClass;
use App\Models\User;
use App\Services\FormSubmissionService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class FormController
{
    protected FormSubmissionService $formSubmissionService;

    public function __construct(FormSubmissionService $formSubmissionService)
    {
        $this->formSubmissionService = $formSubmissionService;
    }

    public function create(string $formSlug)
    {
        // 1. Find the form's configuration using the slug
        $formConfig = Config::get("forms.types.{$formSlug}");

        // 2. If the slug or handler doesn't exist, it's an invalid form type
        if (! $formConfig || ! isset($formConfig['handler'])) {
            abort(404, 'Form type not found or handler not configured.');
        }

        // 3. Create an instance of the handler from our config
        $handler = App::make($formConfig['handler']);

        // 4. Delegate the work to the handler's create method
        return $handler->create();
    }

    public function view(string $formSlug, int $id)
    {
        // 1. Find the form's configuration using the slug
        $formConfig = Config::get("forms.types.{$formSlug}");

        // 2. If the slug or handler doesn't exist, it's an invalid form type
        if (! $formConfig || ! isset($formConfig['handler'])) {
            abort(404, 'Form type not found or handler not configured.');
        }

        // 3. Create an instance of the handler from our config
        $handler = App::make($formConfig['handler']);

        // 4. Delegate the work to the handler's view method
        return $handler->view($id);
    }

    public function store(Request $request, string $formSlug)
    {

        // 1. Find the form's configuration using the slug
        $formConfig = Config::get("forms.types.{$formSlug}");

        // If the slug doesn't exist in the config, it's an invalid form type
        if (! $formConfig) {
            abort(404, 'Form type not found.');
        }
        $user = auth()->user();

        // 2. Dynamically use the correct Form Request for validation
        // We create an instance of the validation class from our config.
        $validationRequest = App::make($formConfig['request']);

        $validatedData = $request->validate($validationRequest->rules());

        $employee = null;
        // If this is the form for new employees, create the user first.
        if ($formSlug === 'requirement-transmittal-form') {
            $employee = $this->createUserFromRequirementTransmittalFormData($validatedData);
            $validatedData['employee_id'] = $employee->id;
        }

        // For the ID Application Form, we need to fetch the job title from the employee's current role
        // and add it to the data to be saved, since it's not submitted from the form.
        if ($formSlug === 'id-application-form') {
            $employeeForForm = User::find($validatedData['employee_id']);
            $validatedData['job_title'] = $employeeForForm?->PrimaryRole?->name ?? 'N/A';
        }

        // Check for existing form if it's a one-to-one type
        if (! $formConfig['more_than_one_form']) {
            $employee = User::find($validatedData['employee_id']);
            $formName = $formConfig['name'];
            $relationship = Str::camel($formSlug);
            $existingFormResponse = $this->checkForExistingForm($employee, $relationship, $formName);
            if ($existingFormResponse) {
                return $existingFormResponse;
            }
        }
        $usersToNotifyIds = (new NotificationHelper)->determineRecipients($formSlug, $validatedData, $user, $employee);

        // 3. Call the service with the dynamic values from the config
        $validatedData['submitted_by'] = $user->id;
        $form = $this->formSubmissionService->handle(
            $formConfig['model'], // <-- Dynamically loaded from config
            $validatedData,
            $user,
            $usersToNotifyIds,
            $formConfig['name']// <-- Dynamically loaded from config
        );

        return response()->json([
            'message' => 'Success',
            'icon' => 'success',
            'text' => 'Form submitted successfully!',
            'form_id' => $form->id,
        ]);
    }

    /**
     * @throws \Throwable
     */
    public function update(Request $request, string $formType, int $id)
    {
        // Step 1: Look up the configuration for this form type
        $formConfig = config("forms.types.{$formType}");
        if (! $formConfig) {
            abort(404, 'Form type not found.');
        }

        // translates 'on' to boolean manually
        foreach ($request->all() as $key => $item) {
            if ($item == 'on') {
                $request->request->set($key, true);
            }
        }

        // Step 2: Dynamically get the correct FormRequest class and its rules
        $formRequestClass = $formConfig['request'];
        $formRequest = app($formRequestClass);

        // Step 3: THIS IS THE CRITICAL PART
        // Create a validator instance and get the validated (and transformed) data.
        // This is what correctly converts "on" to true.
        $validator = Validator::make($request->all(), $formRequest->rules());
        $validatedData = $validator->validated();

        // Add specific logic for the ID Application Form HR processing
        if ($formType === 'id-application-form') {
            $hr_fields = ['is_info_complete', 'has_id_picture', 'is_for_filing', 'is_encoded', 'is_card_done', 'is_delivered'];
            $is_hr_update = false;
            // Check if any HR-specific fields were submitted
            foreach ($hr_fields as $field) {
                if (array_key_exists($field, $validatedData)) {
                    $is_hr_update = true;
                    break;
                }
            }

            if ($is_hr_update) {
                // If HR is updating, stamp the current user as the one who completed it.
                $validatedData['completed_by'] = Auth::id();
            }
        }

        // Step 4: Proceed with the update logic using the clean data
        $form = $formConfig['model']::findOrFail($id);
        $user = User::findOrFail($form->employee_id);

        DB::transaction(function () use ($user, $form, $validatedData) {
            $userData = Arr::only($validatedData, $user->getFillable());
            $formData = Arr::only($validatedData, $form->getFillable());

            $user->update($userData);
            $form->update($formData);
        });

        return response()->json([
            'message' => 'Success',
            'icon' => 'success',
            'text' => 'Form updated successfully!',
            'form_id' => $form->id,
        ]);
    }

    /**R
     * Handles the special logic of creating a user from transmittal form data.
     * This keeps the store() method clean.
     */
    private function createUserFromRequirementTransmittalFormData(array $data): User
    {
        $employee_name = trim($data['first_name'].' '.$data['last_name'].' '.($data['suffix'] ?? ''));
        $employee_data = [
            'name' => $employee_name,
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'],
            'last_name' => $data['last_name'],
            'suffix' => $data['suffix'],
            'gender' => $data['gender'],
            'phone_number' => $data['phone_number'],
            'street' => $data['street'],
            'city' => $data['city'],
            'province' => $data['province'],
            'zip_code' => $data['zip_code'],
            'detachment_id' => $data['detachment_id'],
            'employee_number' => $data['employee_number'],
            'password' => HASH::make('esiai'.$data['employee_number']),
            'email' => $data['email'],
            'email_verified_at' => Carbon::now(),
        ];
        $employee = (new UserClass)->create($employee_data);

        // Assign roles, etc.
        if ($data['gender'] == 'male') {
            $employee->assignRole('security guard');
        } else {
            $employee->assignRole('lady guard');
        }

        return $employee;
    }

    public function printReport(string $formSlug, int $id)
    {
        $formConfig = config("forms.types.{$formSlug}");
        if (! $formConfig) {
            return response()->json(['message' => 'Error', 'error' => 'Form type not found.'], 404);
        }

        if (! Auth::user()->can('print '.$formConfig['name'])) {
            return response()->json(['message' => 'Error', 'error' => 'You do not have permission to print the form.'], 403);
        }

        try {
            $form = $formConfig['model']::findOrFail($id);

            $form->date_last_printed = now();
            $form->printed = true;
            $form->last_printed_by = Auth::id();
            $form->increment('times_printed'); // Atomic and safer
            $form->save();

            return response()->json(['message' => 'Success'], 200);

        } catch (\Exception $e) {
            // It's good practice to log the actual error for debugging
            // \Log::error("Print report failed: " . $e->getMessage());
            return response()->json(['message' => 'Error', 'error' => 'Could not update print status.'], 500);
        }

    }

    public function print(string $formSlug, int $id)
    {
        // 1. Find the form's configuration using the slug
        $formConfig = Config::get("forms.types.{$formSlug}");

        // 2. If the slug or handler doesn't exist, it's an invalid form type
        if (! $formConfig || ! isset($formConfig['handler'])) {
            abort(404, 'Form type not found or handler not configured.');
        }

        // 3. Create an instance of the handler from our config
        $handler = App::make($formConfig['handler']);

        // 4. Delegate the work to the handler's print method
        return $handler->print($id);
    }

    /**
     * Checks if a user already has a specific one-to-one form.
     *
     * @param  User  $employee  The user to check.
     * @param  string  $relationship  The name of the relationship method on the User model.
     * @param  string  $formName  The user-friendly name of the form.
     * @return JsonResponse|null
     */
    private function checkForExistingForm(User $employee, string $relationship, string $formName)
    {
        if ($employee->{$relationship}()->exists()) {
            return response()->json([
                'message' => 'Error',
                'icon' => 'error',
                'text' => "This employee already has a {$formName}.",
            ], 409); // 409 Conflict is a good HTTP status code for this.
        }

        return null;
    }
}
