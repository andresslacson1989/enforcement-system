<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\pages\TelegramController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Open Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::get('/logout', [AuthController::class, 'logout']);
});

// This route will be called by your Telegram bot to link a user's account.
Route::post('/telegram/link-account', [TelegramController::class, 'linkAccount'])->name('telegram.link');
