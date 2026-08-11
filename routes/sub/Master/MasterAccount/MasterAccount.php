<?php

use App\Http\Controllers\Master\Account\MasterAccountController;
use Illuminate\Support\Facades\Route;


Route::prefix('master-account')->controller(MasterAccountController::class)->group(function () {
    Route::get('/', 'index')->middleware(['jwt', 'role:it,superadmin']);
    Route::post('/', 'store')->middleware(['jwt', 'role:it,superadmin']);
    Route::get('/roles-dropdown', 'rolesDropdown')->middleware(['jwt', 'role:it,superadmin']);
    Route::get('/{id}', 'show')->middleware(['jwt', 'role:it,superadmin']);
    Route::patch('/{id}', 'update')->middleware(['jwt', 'role:it,superadmin']);
    // Route::delete('/{id}', 'destroy')->middleware(['jwt', 'role:it,superadmin']);
});