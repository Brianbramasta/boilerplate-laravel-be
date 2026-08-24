<?php

use App\Http\Controllers\Master\Account\MasterAccountController;
use Illuminate\Support\Facades\Route;

Route::prefix('account')->controller(MasterAccountController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store')->middleware('role:it,superadmin');
    Route::get('/roles-dropdown', 'rolesDropdown')->middleware('role:it,superadmin');
    Route::get('/{id}', 'show')->middleware('role:it,superadmin');
    Route::patch('/{id}', 'update')->middleware('role:it,superadmin');
    // Route::delete('/{id}', 'destroy')->middleware('role:it,superadmin');
});
