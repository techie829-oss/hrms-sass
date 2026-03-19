<?php

namespace App\Modules\HR\Repositories;

use App\Core\BaseRepository;
use App\Modules\HR\Interfaces\DesignationRepositoryInterface;
use App\Modules\HR\Models\Designation;
use Illuminate\Database\Eloquent\Collection;

class DesignationRepository extends BaseRepository implements DesignationRepositoryInterface
{
    public function __construct(Designation $model)
    {
        $this->model = $model;
    }

    public function getAllWithEmployeeCount(): Collection
    {
        return $this->model->with('department')->withCount('employees')->get();
    }

    public function find(int|string $id): ?Designation
    {
        return parent::find($id);
    }

    public function findOrFail(int|string $id): Designation
    {
        return parent::findOrFail($id);
    }

    public function create(array $data): Designation
    {
        return parent::create($data);
    }

    public function update(int|string $id, array $data): Designation
    {
        return parent::update($id, $data);
    }
}
