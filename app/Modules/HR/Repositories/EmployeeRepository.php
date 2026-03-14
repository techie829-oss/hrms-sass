<?php

namespace App\Modules\HR\Repositories;

use App\Core\BaseRepository;
use App\Modules\HR\Interfaces\EmployeeRepositoryInterface;
use App\Modules\HR\Models\Employee;
use Illuminate\Database\Eloquent\Collection;

class EmployeeRepository extends BaseRepository implements EmployeeRepositoryInterface
{
    public function __construct(Employee $model)
    {
        $this->model = $model;
    }

    public function findByDepartment(int $departmentId): Collection
    {
        return $this->model->where('department_id', $departmentId)->get();
    }

    public function countActive(): int
    {
        return $this->model->where('status', 'active')->count();
    }

    // Explicitly typing return for interface compliance
    public function find(int|string $id): ?Employee
    {
        return parent::find($id);
    }

    public function findOrFail(int|string $id): Employee
    {
        return parent::findOrFail($id);
    }

    public function create(array $data): Employee
    {
        return parent::create($data);
    }

    public function update(int|string $id, array $data): Employee
    {
        return parent::update($id, $data);
    }
}
