<?php

namespace App\Modules\Leave\Providers;

use App\Modules\HR\Events\EmployeeCreated;
use App\Modules\Leave\Listeners\AllocateLeaveBalances;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class LeaveEventServiceProvider extends ServiceProvider
{
    protected $listen = [
        EmployeeCreated::class => [
            AllocateLeaveBalances::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
