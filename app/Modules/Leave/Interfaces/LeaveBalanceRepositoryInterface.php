<?php

namespace App\Modules\Leave\Interfaces;

use App\Core\BaseRepositoryInterface;
use App\Modules\Leave\Models\LeaveBalance;
use Illuminate\Database\Eloquent\Collection;

interface LeaveBalanceRepositoryInterface extends BaseRepositoryInterface
{
    public function getBalancesForEmployee(int $employeeId, int $year): Collection;
}
