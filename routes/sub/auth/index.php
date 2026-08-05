<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::get('/me', 'me')->middleware('jwt');
    Route::post('/refresh', 'refresh');
    Route::patch('/change-password', 'changePassword')->middleware('jwt');
});
