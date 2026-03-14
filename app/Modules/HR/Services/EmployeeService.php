<?php

namespace App\Modules\HR\Services;

use App\Core\BaseService;
use App\Modules\HR\Interfaces\EmployeeRepositoryInterface;

class EmployeeService extends BaseService
{
    public function __construct(EmployeeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all employees with pagination and filters.
     */
    public function all(array $filters = [])
    {
        return $this->repository->paginate(15, $filters);
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
