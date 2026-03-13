<?php

namespace App\Modules\HR\Services;

use App\Core\BaseService;
use App\Modules\HR\Repositories\EmployeeRepository;

class EmployeeService extends BaseService
{
    public function __construct(EmployeeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get employees by department.
     */
    public function getByDepartment(int $departmentId)
    {
        return $this->repository->findByDepartment($departmentId);
    }

    /**
     * Get active employees count.
     */
    public function getActiveCount(): int
    {
        return $this->repository->countActive();
    }
}
