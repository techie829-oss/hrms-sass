<?php

namespace App\Modules\Leave\Repositories;

use App\Core\BaseRepository;
use App\Modules\Leave\Interfaces\LeaveTypeRepositoryInterface;
use App\Modules\Leave\Models\LeaveType;
use Illuminate\Database\Eloquent\Collection;

class LeaveTypeRepository extends BaseRepository implements LeaveTypeRepositoryInterface
{
    public function __construct(LeaveType $model)
    {
        $this->model = $model;
    }

    public function getActiveLeaveTypes(): Collection
    {
        return $this->model->where('is_active', true)->get();
    }
}
