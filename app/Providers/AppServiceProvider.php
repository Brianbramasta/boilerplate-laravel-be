<?php

namespace App\Providers;

use App\Exceptions\ApiExceptionHandler;
use App\Interface\Auth\AuthInterface;
use App\Repositories\Auth\AuthRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('api.exception', function () {
            return new ApiExceptionHandler();
        });

        $this->app->bind(
            AuthInterface::class,
            AuthRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
