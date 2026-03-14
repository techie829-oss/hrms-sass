<?php

namespace App\Modules\HR\Repositories;

use App\Core\BaseRepository;
use App\Modules\HR\Interfaces\DepartmentRepositoryInterface;
use App\Modules\HR\Models\Department;
use Illuminate\Database\Eloquent\Collection;

class DepartmentRepository extends BaseRepository implements DepartmentRepositoryInterface
{
    public function __construct(Department $model)
    {
        $this->model = $model;
    }

    public function getAllWithMemberCount(): Collection
    {
        return $this->model->withCount('employees')->get();
    }

    public function find(int|string $id): ?Department
    {
        return parent::find($id);
    }

    public function findOrFail(int|string $id): Department
    {
        return parent::findOrFail($id);
    }

    public function create(array $data): Department
    {
        return parent::create($data);
    }

    public function update(int|string $id, array $data): Department
    {
        return parent::update($id, $data);
    }
}
