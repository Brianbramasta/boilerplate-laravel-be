<?php

use Illuminate\Support\Facades\Route;

Route::prefix('open-api/v1/')->group(function () {
    Route::middleware('api.key')
        ->group(function () {
            $masterRoutes = glob(__DIR__ . '/routes/*.php');
            sort($masterRoutes);
            foreach ($masterRoutes as $routeFile) {
                require $routeFile;
            }
        });
    require __DIR__ . '/PublicRoute.php';
});
