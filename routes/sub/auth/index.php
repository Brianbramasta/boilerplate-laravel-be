<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/auth')->controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::get('/me', 'me')->middleware('jwt');
    Route::post('/logout', 'logout')->middleware('jwt');
});