<?php

namespace App\Modules\Leave\Repositories;

use App\Core\BaseRepository;
use App\Modules\Leave\Interfaces\LeaveRequestRepositoryInterface;
use App\Modules\Leave\Models\LeaveRequest;
use Illuminate\Pagination\LengthAwarePaginator;

class LeaveRequestRepository extends BaseRepository implements LeaveRequestRepositoryInterface
{
    public function __construct(LeaveRequest $model)
    {
        $this->model = $model;
    }

    public function find(int|string $id): ?LeaveRequest
    {
        return parent::find($id);
    }

    public function findOrFail(int|string $id): LeaveRequest
    {
        return parent::findOrFail($id);
    }

    public function create(array $data): LeaveRequest
    {
        return parent::create($data);
    }

    public function update(int|string $id, array $data): LeaveRequest
    {
        return parent::update($id, $data);
    }

    public function getPaginatedList(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with(['employee', 'leaveType']);

        if (isset($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        return $query->latest()->paginate($perPage);
    }
}
