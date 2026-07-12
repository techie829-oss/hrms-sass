<?php

namespace App\Modules\Leave\Interfaces;

use App\Core\BaseRepositoryInterface;
use App\Modules\Leave\Models\LeaveType;
use Illuminate\Database\Eloquent\Collection;

interface LeaveTypeRepositoryInterface extends BaseRepositoryInterface
{
    public function getActiveLeaveTypes(): Collection;
}
