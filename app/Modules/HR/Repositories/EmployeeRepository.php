<?php

namespace App\Modules\HR\Repositories;

use App\Core\BaseRepository;
use App\Modules\HR\Models\Employee;

class EmployeeRepository extends BaseRepository
{
    public function __construct(Employee $model)
    {
        $this->model = $model;
    }

    /**
     * Find employees by department.
     */
    public function findByDepartment(int $departmentId)
    {
        return $this->model->where('department_id', $departmentId)->get();
    }

    /**
     * Count active employees.
     */
    public function countActive(): int
    {
        return $this->model->where('status', 'active')->count();
    }
}
