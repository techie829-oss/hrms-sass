<?php

namespace App\Modules\HR\Repositories;

use App\Core\BaseRepository;
use App\Modules\HR\Models\Department;

class DepartmentRepository extends BaseRepository
{
    public function __construct(Department $model)
    {
        $this->model = $model;
    }
}
