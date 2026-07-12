<?php

namespace App\Modules\Leave\Repositories;

use App\Core\BaseRepository;
use App\Modules\Leave\Interfaces\LeaveBalanceRepositoryInterface;
use App\Modules\Leave\Models\LeaveBalance;
use Illuminate\Database\Eloquent\Collection;

class LeaveBalanceRepository extends BaseRepository implements LeaveBalanceRepositoryInterface
{
    public function __construct(LeaveBalance $model)
    {
        $this->model = $model;
    }

    public function getBalancesForEmployee(int $employeeId, int $year): Collection
    {
        return $this->model->with('leaveType')
            ->where('employee_id', $employeeId)
            ->where('year', $year)
            ->get();
    }
}
