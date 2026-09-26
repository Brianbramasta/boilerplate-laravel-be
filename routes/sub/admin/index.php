<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->controller(AdminController::class)->group(function () {
    Route::get('/admin/users', 'index')->middleware('jwt');
    Route::post('/admin/users', 'store')->middleware('jwt');
    Route::put('/admin/users/{id}', 'update')->middleware('jwt');
});