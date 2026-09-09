<?php

namespace App\Providers;

use App\Exceptions\ApiExceptionHandler;
use App\Interface\Auth\AuthInterface;
use App\Interface\Master\Account\MasterAccountInterface;
use App\Interface\Master\PsbAssessment\PsbAssessmentInterface;
use App\Interface\Master\PsbVariable\PsbWoStepInterface;
use App\Interface\WorkOrder\DashboardInterface;
use App\Repositories\Auth\AuthRepository;
use App\Repositories\Dashboard\DashboardRepository;
use App\Repositories\Master\Account\MasterAccountRepository;
use App\Repositories\Master\PsbAssessment\PsbAssessmentRepository;
use App\Repositories\Master\PsbVariable\PsbIntegratedRepository;
use App\Repositories\Master\PsbVariable\PsbJsonRepository;
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
            AuthRepository::class,
        );
        $this->app->bind(
            MasterAccountInterface::class,
            MasterAccountRepository::class,
        );
        // Switch: 'json' (default, lokal) atau 'api' (sistem BE lain, lihat PsbIntegratedRepository).
        $this->app->bind(PsbWoStepInterface::class, function ($app) {
            return match (config('services.psb_wo_step.driver', 'json')) {
                'api' => $app->make(PsbIntegratedRepository::class),
                default => $app->make(PsbJsonRepository::class),
            };
        });
        $this->app->bind(
            PsbAssessmentInterface::class,
            PsbAssessmentRepository::class,
        );
        $this->app->bind(
            DashboardInterface::class,
            DashboardRepository::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom([
            database_path('migrations/audit'),
            database_path('migrations/role_permission'),
            database_path('migrations/master'),
            database_path('migrations/ref'),
            database_path('migrations/assessment_psb'),
        ]);
    }
}
