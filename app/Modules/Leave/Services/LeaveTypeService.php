<?php

namespace App\Modules\Leave\Services;

use App\Modules\Leave\Models\LeaveType;
use App\Modules\Leave\DTOs\LeaveTypeData;
use App\Modules\Leave\Interfaces\LeaveTypeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class LeaveTypeService
{
    public function __construct(
        protected LeaveTypeRepositoryInterface $repository
    ) {
    }

    public function getActiveLeaveTypes(): Collection
    {
        return $this->repository->getActiveLeaveTypes();
    }

    public function createLeaveType(LeaveTypeData $dto): LeaveType
    {
        return $this->repository->create($dto->toArray());
    }
}
