<?php

use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\pages\Access;
use App\Http\Controllers\pages\ActivityLogController;
use App\Http\Controllers\pages\DetachmentController;
use App\Http\Controllers\pages\FirstMonthPerformanceEvaluationFormController;
use App\Http\Controllers\pages\FormController;
use App\Http\Controllers\pages\FormLibrary;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\RequirementTransmittalFormController;
use App\Http\Controllers\pages\RoleController;
use App\Http\Controllers\pages\SearchController;
use App\Http\Controllers\pages\TagController;
use App\Http\Controllers\pages\TelegramController;
use App\Http\Controllers\pages\TrainingCertificateController;
use App\Http\Controllers\pages\UsersController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

// locale
Route::get('/lang/{locale}', [LanguageController::class, 'swap']);
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

// authentication
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');

// Telegram Routes
Route::post('/telegram/webhook', [TelegramController::class, 'webhook'])->withoutMiddleware(VerifyCsrfToken::class);
Route::get('/telegram/login-callback', [TelegramController::class, 'loginCallback'])->name('telegram.login.callback');
Route::get('/telegram/webhook', [TelegramController::class, 'webhook']);

Route::middleware(['auth:web'])->group(function () {

    // Search routes
    Route::get('/search-routes', [SearchController::class, 'search'])->name('search-routes');

    // Logging Route
    Route::get('/activity-logs', [ActivityLogController::class, 'logs'])->name('activity-logs');

    // Form Library Routes
    Route::get('/', [FormLibrary::class, 'index'])->name('form-library');
    Route::get('/form-library', [FormLibrary::class, 'index'])->name('form-library');

    // Users Routes used by My Profile Page
    Route::get('/users', [UsersController::class, 'index'])->name('users-index');
    Route::get('/user/my-profile', [UsersController::class, 'profile'])->name('my-profile');
    Route::get('/user/profile/{id}', [UsersController::class, 'profile'])->name('user-profile');
    Route::get('/user/{id}', [UsersController::class, 'show'])->name('user-show');
    Route::get('/user/{id}/files', [UsersController::class, 'getUserFiles'])->name('user-files.get');
    Route::post('/user/upload-file', [UsersController::class, 'uploadFile'])->name('user-files.upload');
    Route::post('/user/profile-photo', [UsersController::class, 'updateProfilePhoto'])->name('user-profile-photo.update');
    Route::patch('/user/{id}', [UsersController::class, 'update'])->name('user-update');
    Route::delete('/user/{id}', [UsersController::class, 'delete'])->name('user->delete');

    // In routes/web.php, inside the auth middleware group
    Route::get('/profile/complete-profile', [UsersController::class, 'showCompletionForm'])->name('profile.completion.form');
    Route::post('/profile/complete-profile', [UsersController::class, 'completeProfile'])->name('profile.completion.submit');
    // Staff Routes Used by Personnel page, Staff Page and Detachment Profile page
    Route::get('/staffs', [UsersController::class, 'staffs_index'])->name('staffs');
    Route::post('/staffs/store', [UsersController::class, 'store']);
    Route::get('/staffs/table', [UsersController::class, 'staffsTable']);
    Route::get('/staffs/{id}', [UsersController::class, 'show']);

    Route::put('/staffs/update/{id}', [UsersController::class, 'update']);
    Route::delete('/staffs/delete/{id}', [UsersController::class, 'delete']);
    Route::patch('/staffs/remove/{id}', [UsersController::class, 'remove']);

    // This is the correct route for the file deletion AJAX call
    Route::delete('/user-files/{file}', [UsersController::class, 'deleteFile'])->name('user-files.delete');
    Route::post('/staffs/suspend/', [UsersController::class, 'suspend']);
    Route::post('/staffs/unsuspend/', [UsersController::class, 'unsuspend']);

    // Personnel Routes
    Route::get('/personnel', [UsersController::class, 'personnel_index'])->name('personnel');
    Route::get('/personnel/table', [UsersController::class, 'personnelTable']);
    Route::get('/personnel/detachment-personnel-table', [UsersController::class, 'detachmentPersonnelTable']);
    Route::patch('/personnel/update-role/{id}', [Access::class, 'updateRole']);

    // Access Routes
    // Roles
    Route::get('/roles', [Access::class, 'index'])->name('access-pages-roles');
    Route::post('/form/roles', [Access::class, 'form_roles']);
    Route::post('/form/update-roles', [Access::class, 'form_update_roles']);

    // Form Universal Routes
    Route::get('/form/create/{form_slug}', [FormController::class, 'create'])->name('forms.create');
    Route::get('/form/view/{form_slug}/{id}', [FormController::class, 'view'])->name('forms.view');

    // Form Controller Universal route for storing forms
    Route::post('/forms/store/{form_slug}', [FormController::class, 'store'])->name('forms.store');
    Route::put('/forms/update/{form_slug}/{id}', [FormController::class, 'update'])->name('forms.update');
    Route::get('/forms/print/{form_slug}/{id}', [FormController::class, 'print'])->name('forms.print');

    // report a form print
    Route::put('/forms/print-report/{fromType}/{id}', [FormController::class, 'printReport']);

    // Requirement Transmittal Form
    Route::patch('/form/remarks/requirement-transmittal-form/', [RequirementTransmittalFormController::class, 'remarks']);
    Route::get('/form/print/requirement-transmittal-form/{id}', [RequirementTransmittalFormController::class, 'print']);

    // First Month Performance Evaluation Form
    Route::patch('/form/approve/first-month-performance-evaluation-form/{id}', [FirstMonthPerformanceEvaluationFormController::class, 'approve']);
    Route::patch('/form/print/first-month-performance-evaluation-form/{id}', [FirstMonthPerformanceEvaluationFormController::class, 'print']);

    // Personnel Requisition Form

    // Detachments Route
    Route::get('/detachments', [DetachmentController::class, 'index'])->name('detachments');
    Route::post('/detachments/store', [DetachmentController::class, 'store']);
    Route::post('/detachments/add-personnel', [DetachmentController::class, 'addPersonnel']);
    Route::get('/detachments/view/{id}', [DetachmentController::class, 'view'])->name('detachment-profile');
    Route::get('/detachments/profile', [DetachmentController::class, 'profile'])->name('my-detachment');
    Route::put('/detachments/update/{id}', [DetachmentController::class, 'update']);
    Route::patch('/detachments/approve/{id}', [DetachmentController::class, 'approve']);
    Route::get('/detachments/table', [DetachmentController::class, 'detachmentTable']);

    // This single route will handle marking any notification as read
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->middleware('auth:sanctum');

    // Roles Route
    Route::get('/get-roles/{category}', [RoleController::class, 'getRoles'])->name('get-roles');

    // Tags Search Route (for Select2)
    Route::get('/tags/search', [TagController::class, 'search'])->name('tags.search');

    // Training Certificates Route
    Route::get('/training-certificates', [TrainingCertificateController::class, 'view'])->name('training-certificates');
    Route::get('/training-certificates/table', [TrainingCertificateController::class, 'table'])->name('training-certificates');
    Route::post('/training-certificates/store', [TrainingCertificateController::class, 'store'])->name('training-certificates.store');

    // Test
    Route::get('/test', function () {
        $user = Auth::user();
        $message_text = "*Telegram Linked*\nYour Telegram account has been linked to your ".config('app.name')." Account.\nYou will now receive notifications in this Telegram Account.";
        Telegram::sendMessage([
            'chat_id' => $user->telegram_chat_id,
            'text' => $message_text,
            'parse_mode' => 'Markdown',
        ]);
    });

});
