<?php

namespace App\Modules\HR\Services;

use App\Core\BaseService;
use App\Modules\HR\Interfaces\DepartmentRepositoryInterface;

class DepartmentService extends BaseService
{
    public function __construct(DepartmentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all departments with employee counts.
     */
    public function getAllWithCounts()
    {
        return $this->repository->getAllWithMemberCount();
    }
}
