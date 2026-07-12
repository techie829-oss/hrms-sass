<?php

namespace App\Modules\Leave\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Leave\Interfaces\LeaveRequestRepositoryInterface;
use App\Modules\Leave\Repositories\LeaveRequestRepository;
use App\Modules\Leave\Interfaces\LeaveTypeRepositoryInterface;
use App\Modules\Leave\Repositories\LeaveTypeRepository;
use App\Modules\Leave\Interfaces\LeaveBalanceRepositoryInterface;
use App\Modules\Leave\Repositories\LeaveBalanceRepository;
use App\Modules\Leave\Interfaces\CompOffRequestRepositoryInterface;
use App\Modules\Leave\Repositories\CompOffRequestRepository;
use App\Modules\Leave\Interfaces\HolidayRepositoryInterface;
use App\Modules\Leave\Repositories\HolidayRepository;

class LeaveServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LeaveRequestRepositoryInterface::class, LeaveRequestRepository::class);
        $this->app->bind(LeaveTypeRepositoryInterface::class, LeaveTypeRepository::class);
        $this->app->bind(LeaveBalanceRepositoryInterface::class, LeaveBalanceRepository::class);
        $this->app->bind(CompOffRequestRepositoryInterface::class, CompOffRequestRepository::class);
        $this->app->bind(HolidayRepositoryInterface::class, HolidayRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
