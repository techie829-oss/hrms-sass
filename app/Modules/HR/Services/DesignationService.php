<?php

namespace App\Modules\HR\Services;

use App\Core\BaseService;
use App\Modules\HR\Interfaces\DesignationRepositoryInterface;

class DesignationService extends BaseService
{
    public function __construct(DesignationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all designations with employee counts and department.
     */
    public function getAllWithCounts()
    {
        return $this->repository->getAllWithEmployeeCount();
    }
}
