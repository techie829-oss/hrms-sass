<?php

namespace App\Modules\Leave\Listeners;

use App\Modules\HR\Events\EmployeeCreated;
use App\Modules\Leave\Services\LeaveBalanceService;
use Illuminate\Contracts\Queue\ShouldQueue;

class AllocateLeaveBalances implements ShouldQueue
{
    public function __construct(protected LeaveBalanceService $balanceService)
    {
    }

    public function handle(EmployeeCreated $event): void
    {
        $this->balanceService->allocateDefaultBalances($event->employee);
    }
}
