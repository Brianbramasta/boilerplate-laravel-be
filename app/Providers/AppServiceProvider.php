<?php

namespace App\Providers;

use App\Contracts\Employee\ApprovalHistoryInterface;
use App\Contracts\Employee\LeaveBalanceInterface;
use App\Contracts\Employee\LeaveRequestInterface;
use App\Repositories\Employee\ApprovalHistoryRepository;
use App\Repositories\Employee\LeaveBalanceRepository;
use App\Repositories\Employee\LeaveRequestRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LeaveRequestInterface::class, LeaveRequestRepository::class);
        $this->app->bind(LeaveBalanceInterface::class, LeaveBalanceRepository::class);
        $this->app->bind(ApprovalHistoryInterface::class, ApprovalHistoryRepository::class);
    }

    public function boot(): void
    {
        //
    }
}