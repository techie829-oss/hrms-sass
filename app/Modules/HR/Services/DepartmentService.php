<?php

namespace App\Modules\HR\Services;

use App\Core\BaseService;
use App\Modules\HR\Repositories\DepartmentRepository;

class DepartmentService extends BaseService
{
    public function __construct(DepartmentRepository $repository)
    {
        $this->repository = $repository;
    }
}
