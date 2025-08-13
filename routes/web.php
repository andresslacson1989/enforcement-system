<?php

use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\ActivityBoard;
use App\Http\Controllers\pages\FirstMonthPerformanceEvaluationFormController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\Page2;
use App\Http\Controllers\pages\Access;
use App\Http\Controllers\pages\RequirementTransmittalFormController;
use App\Http\Controllers\pages\UsersController;
use Illuminate\Support\Facades\Route;


// locale
  Route::get('/lang/{locale}', [LanguageController::class, 'swap']);
  Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

// authentication
  Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
  Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');



Route::middleware(['auth:web'])->group(function () {
  // Main Page Route
  Route::get('/', [HomePage::class, 'index'])->name('pages-home');
  Route::get('/page-2', [Page2::class, 'index'])->name('pages-page-2');

  Route::get('users', [UsersController::class , 'index'])->name('users-index');
  Route::get('user/{id}', [UsersController::class , 'usersData'])->name('users-index');

//Access Routes
  //Roles
  Route::get('/roles', [Access::class, 'roles'])->name('access-pages-roles');
  Route::post('/form/roles', [Access::class, 'form_roles']);
  Route::post('/form/update-roles', [Access::class, 'form_update_roles']);
  Route::post('/table/roles', [Access::class, 'table_roles']);
  Route::post('/see-permissions', [Access::class, 'see_permissions']);
  Route::get('/see-permissions', [Access::class, 'see_permissions']);

  //Permissions
  Route::get('/permissions', [Access::class, 'permissions'])->name('access-pages-permissions');
  Route::post('/form/permissions', [Access::class, 'form_permissions']);
  Route::post('/form/update-permission', [Access::class, 'form_update_permissions']);
  Route::post('/table/permissions', [Access::class, 'permissions']);

  //Activity Board
  Route::get('/activity-board', [ActivityBoard::class, 'activities'])->name('activity-board');

  //Form Routes
  Route::get('/form/new/{form}', [ActivityBoard::class, 'formNew']);
  Route::get('/form/view/{form}/{id}', [ActivityBoard::class, 'formView']);

  //Requirement Transmittal Form
  Route::post('/form/requirement-transmittal-form/store/', [RequirementTransmittalFormController::class, 'store']);
  Route::put('/form/requirement-transmittal-form/{form}/{id}', [RequirementTransmittalFormController::class, 'update']);
  Route::patch('/form/requirement-transmittal-form/approve', [RequirementTransmittalFormController::class, 'approve']);
  Route::get('/form/requirement-transmittal-form/print/{id}', [RequirementTransmittalFormController::class, 'print']);
  Route::post('/form/print-report/{id}', [RequirementTransmittalFormController::class, 'printReport']);

  //First Month Performance Evaluation Form
  Route::post('/form/first-month-performance-evaluation-form/store/', [FirstMonthPerformanceEvaluationFormController::class, 'store']);

});
