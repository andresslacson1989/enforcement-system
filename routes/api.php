<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\pages\TelegramController;
use Illuminate\Support\Facades\Route;

// Open Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::get('/logout', [AuthController::class, 'logout']);
});

Route::post('/telegram/webhook', [TelegramController::class, 'webhook'])->name('telegram.webhook');
