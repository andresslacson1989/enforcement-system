<?php

namespace App\Http\Controllers\pages;

use App\Helpers\NotificationHelper;
use App\Http\Classes\UserClass;
use App\Jobs\SendAndBroadcastNotification;
use App\Models\IdApplicationForm;
use App\Models\User;
use App\Services\FormSubmissionService;
use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class FormController
{
    protected FormSubmissionService $formSubmissionService;

    /**
     * FormController constructor.
     *
     * @param  FormSubmissionService  $formSubmissionService  The service responsible for handling form submissions.
     */
    public function __construct(FormSubmissionService $formSubmissionService)
    {
        $this->formSubmissionService = $formSubmissionService;
    }

    /**
     * Displays the form creation view based on the provided slug.
     *
     * @param  string  $form_slug  The unique identifier for the form from the configuration.
     */
    public function create(string $form_slug): View|Factory
    {

        // 1. Find the form's configuration using the slug
        $form_config = Config::get("forms.types.{$form_slug}");

        // 2. If the slug or handler doesn't exist, it's an invalid form type
        if (! $form_config || ! isset($form_config['handler'])) {
            abort(404, 'Form type not found or handler not configured.');
        }

        // 3. Create an instance of the handler from our config
        $handler = App::make($form_config['handler']);

        // 4. Delegate the work to the handler's create method
        return $handler->create();
    }

    /**
     * Displays a specific submitted form instance for viewing or editing.
     *
     * @param  string  $form_slug  The unique identifier for the form from the configuration.
     * @param  int  $id  The ID of the specific form submission to view.
     */
    public function view(string $form_slug, int $id): View|Factory
    {
        // 1. Find the form's configuration using the slug
        $form_config = Config::get("forms.types.{$form_slug}");

        // 2. If the slug or handler doesn't exist, it's an invalid form type
        if (! $form_config || ! isset($form_config['handler'])) {
            abort(404, 'Form type not found or handler not configured.');
        }

        // 3. Create an instance of the handler from our config
        $handler = App::make($form_config['handler']);

        // 4. Delegate the work to the handler's view method
        return $handler->view($id);
    }

    /**
     * Handles the submission and storage of a new form.
     *
     * @param  Request  $request  The incoming HTTP request.
     * @param  string  $form_slug  The unique identifier for the form being submitted.
     */
    public function store(Request $request, string $form_slug): JsonResponse
    {
        $employee = null;
        foreach ($request->all() as $key => $item) {
            if ($item == 'on') {
                $request->request->set($key, true);
            }
        }

        // 1. Find the form's configuration using the slug
        $form_config = Config::get("forms.types.{$form_slug}");

        // If the slug doesn't exist in the config, it's an invalid form type
        if (! $form_config) {
            abort(404, 'Form type not found.');
        }
        $user = auth()->user();

        // 2. Dynamically use the correct Form Request for validation
        // We create an instance of the validation class from our config.
        $validation_request = App::make($form_config['request']);
        $validated_data = $request->validate($validation_request->rules());

        // 3. Handle one-to-one form checks BEFORE creating any models.
        $is_one_to_one = $form_config['one_to_one'] ?? false;
        if ($is_one_to_one) {

            // Special check for the onboarding form, as the user doesn't exist yet.
            if ($form_slug === 'requirement-transmittal-form') {
                $existing_user = User::where('employee_number', $validated_data['employee_number'])
                    ->orWhere('email', $validated_data['email'])
                    ->first();
                if ($existing_user) {
                    return response()->json([
                        'message' => 'Error',
                        'icon' => 'error',
                        'text' => 'A user with this Employee Number or Email already exists.',
                    ], 409);
                }
                // 4. Handle User creation for onboarding form
                $employee = $this->_handle_create_user_from_requirement_transmittal_form_data($validated_data);
                $validated_data['employee_id'] = $employee->id;
            }
            // Standard check for all other one-to-one forms for existing users.
            elseif (isset($validated_data['employee_id'])) {
                $employee = User::find($validated_data['employee_id']);
                $relationship = Str::camel($form_slug); // e.g., 'firstMonthPerformanceEvaluationForm'
                $form_name = $form_config['name'];

                $existing_form_response = $this->checkForExistingForm($employee, $relationship, $form_name);
                if ($existing_form_response) {
                    return $existing_form_response;
                }
            }
        }

        // 5. Add any additional data needed before saving
        if ($form_slug === 'id-application-form') {
            $validated_data = $this->_handle_id_application_form_data($request, $validated_data);
        }

        // For leave applications, the employee is the one submitting the form.
        if ($form_slug === 'personnel-leave-application-form') {
            $validated_data['user_id'] = $user->id;
            $validated_data['employee_id'] = $user->id;
        }

        // 6. Determine notification recipients
        $users_to_notify_ids = (new NotificationHelper)->determineRecipients($form_slug, $validated_data, $user, $employee);

        // 7. Call the service to handle submission
        $validated_data['submitted_by'] = $user->id;
        $form = $this->formSubmissionService->handle(
            $form_config['model'], // <-- Dynamically loaded from config
            $validated_data,
            $user,
            $users_to_notify_ids,
            $form_config['name']// <-- Dynamically loaded from config
        );

        return response()->json([
            'message' => 'Success',
            'icon' => 'success',
            'text' => 'Form submitted successfully!',
            'form_id' => $form->id,
            'form_name' => $form_slug,
            'employee_id' => $employee->id ?? '',
        ]);
    }

    /**
     * @param  Request  $request  The incoming HTTP request.
     * @param  string  $form_type  The unique identifier for the form being updated.
     * @param  int  $id  The ID of the form submission to update. // This was a snake_case violation
     *
     * @throws Throwable
     */
    public function update(Request $request, string $form_type, int $id): JsonResponse
    {
        // Step 1: Look up the configuration for this form type
        $form_config = config("forms.types.{$form_type}");
        if (! $form_config) {
            abort(404, 'Form type not found.');
        }

        // translates 'on' to boolean manually
        foreach ($request->all() as $key => $item) {
            if ($item == 'on') {
                $request->request->set($key, true);
            }
        }

        // Step 2: Dynamically get the correct FormRequest class and its rules
        $validation_request = App::make($form_config['request']);

        // Step 3: Validate the request using the dynamically loaded Form Request.
        // This ensures the correct rules are applied for the specific form being updated.
        $validated_data = $request->validate($validation_request->rules());

        // Add specific logic for the ID Application Form HR processing
        if ($form_type === 'id-application-form') {
            $validated_data = $this->_handle_id_application_form_data($request, $validated_data, $id);
        }

        // Step 4: Proceed with the update logic using the clean data
        $form = $form_config['model']::findOrFail($id);
        $employee = User::findOrFail($form->employee_id); // make sure to double-check this sometimes employee id is the staff.

        DB::transaction(function () use ($employee, $form, $validated_data) {
            $user_data = Arr::only($validated_data, $employee->getFillable());
            $form_data = Arr::only($validated_data, $form->getFillable());

            // --- This is the fix ---
            // The 'status' field belongs to the form's submission, not the user.
            // We ensure it's in the form_data and explicitly remove it from user_data.
            if (isset($validated_data['status'])) {
                $form_data['status'] = $validated_data['status'];
                unset($user_data['status']);
            }

            $employee->update($user_data);
            $form->update($form_data);
        });

        return response()->json([
            'message' => 'Success',
            'icon' => 'success',
            'text' => 'Form updated successfully!',
            'form_name' => $form_type,
            'employee_id' => $employee->id,
            'form_id' => $form->id,
        ]);
    }

    /**R
     * Handles the special logic of creating a new user from the Requirement Transmittal Form data.
     * This keeps the store() method clean.
     *
     * @param  array  $data  The validated data from the form.
     * @return User The newly created user instance.
     */
    private function _handle_create_user_from_requirement_transmittal_form_data(array $data): User
    {
        $default_role = 'guard';
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
        $employee->assignRole($default_role);
        $employee->setPrimaryRole($default_role);

        return $employee;
    }

    /**
     * Updates the print status and count for a given form.
     *
     * @param  string  $form_slug  The unique identifier for the form.
     * @param  int  $id  The ID of the form submission.
     */
    public function printReport(string $form_slug, int $id): JsonResponse
    {
        $form_config = config("forms.types.{$form_slug}");
        if (! $form_config) {
            return response()->json(['message' => 'Error', 'error' => 'Form type not found.'], 404);
        }

        if (! Auth::user()->can('print '.$form_config['name'])) {
            return response()->json(['message' => 'Error', 'error' => 'You do not have permission to print the form.'], 403);
        }

        try {
            $form = $form_config['model']::findOrFail($id);

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

    /**
     * Generates a printable view of a specific form submission.
     *
     * @param  string  $form_slug  The unique identifier for the form.
     * @param  int  $id  The ID of the form submission to print.
     *
     * @throws BindingResolutionException
     */
    public function print(string $form_slug, int $id): mixed
    {
        // 1. Find the form's configuration using the slug
        $form_config = Config::get("forms.types.{$form_slug}");

        // 2. If the slug or handler doesn't exist, it's an invalid form type
        if (! $form_config || ! isset($form_config['handler'])) {
            abort(404, 'Form type not found or handler not configured.');
        }

        // 3. Create an instance of the handler from our config
        $handler = App::make($form_config['handler']);

        // 4. Delegate the work to the handler's print method
        return $handler->print($id);
    }

    /**
     * Checks if a user already has a specific one-to-one form.
     *
     * @param  User  $employee  The user to check.
     * @param  string  $relationship  The name of the relationship method on the User model. // This was a snake_case violation
     * @param  string  $form_name  The user-friendly name of the form.
     * @return JsonResponse|null
     */
    private function checkForExistingForm(User $employee, string $relationship, string $form_name)
    {

        if ($employee->{$relationship}()->exists()) {
            return response()->json([
                'message' => 'Error',
                'icon' => 'error',
                'text' => "This employee already has a {$form_name}.",
            ], 409); // 409 Conflict is a good HTTP status code for this.
        }

        return null;
    }

    /**
     * Handles form-specific logic for the ID Application Form, such as photo uploads and HR processing.
     *
     * @param  Request  $request  The incoming HTTP request.
     * @param  array  $validated_data  The validated data from the form.
     * @param  int|null  $form_id  The ID of the form being updated, if applicable.
     * @return array The modified validated data.
     */
    private function _handle_id_application_form_data(Request $request, array $validated_data, ?int $form_id = null): array
    {
        // Set the job title based on the employee's primary role, but only on creation.
        if (! $form_id) {
            $employee = User::find($validated_data['employee_id']);
            $validated_data['job_title'] = $employee?->PrimaryRole?->name ?? 'N/A';
        }

        // If a new photo is being uploaded...
        if ($request->hasFile('photo')) {
            // If this is an update, find the existing form and delete the old photo.
            if ($form_id) {
                $current_form = IdApplicationForm::findOrFail($form_id);
                if ($current_form && $current_form->photo_path) {
                    Storage::disk('public')->delete($current_form->photo_path);
                }
            }

            // Store the new photo and add its path to the data.
            $validated_data['photo_path'] = $request->file('photo')->store('id-applications', 'public');

        }

        // Handle HR Processing section updates
        $hr_fields = ['is_card_done', 'is_delivered'];
        // Check if any of the HR fields were part of the submitted form data.
        $is_hr_update = Arr::hasAny($request->all(), $hr_fields);

        // We must always check for the presence of the checkbox fields in the request.
        // If they are not present, it means they were unchecked, and we must set them to false.
        $validated_data['is_card_done'] = $request->has('is_card_done');
        $validated_data['is_delivered'] = $request->has('is_delivered');

        if ($is_hr_update) {

            // --- New Notification Logic ---
            // Find the original submission record to get the submitter's ID
            $submission = IdApplicationForm::findOrFail($form_id)->submission;
            $submitter_id = $submission->submitted_by;

            // Check if the 'is_card_done' status has just been changed to true
            if ($validated_data['is_card_done'] && ! $submission->submittable->is_card_done) {

                // change status to processing
                $validated_data['status'] = 'processing';

                // prepare and send notification to submitter
                $title = 'ID Card Ready for Pickup';
                $employee_name = $submission->submittable->employee->name;
                $message = "The ID card for {$employee_name} is now ready for pickup.";
                $link = route('forms.view', ['form_slug' => 'id-application-form', 'id' => $form_id]);

                // Dispatch the notification job to the original submitter
                SendAndBroadcastNotification::dispatch($title, $message, $link, [$submitter_id]);
            }

            if ($validated_data['is_delivered']) {
                $validated_data['completed_by'] = Auth::id();
                $validated_data['status'] = 'processed';
            }
        }

        return $validated_data;
    }
}
