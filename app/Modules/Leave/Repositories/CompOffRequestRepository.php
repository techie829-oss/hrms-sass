<?php

namespace App\Modules\Leave\Repositories;

use App\Core\BaseRepository;
use App\Modules\Leave\Interfaces\CompOffRequestRepositoryInterface;
use App\Modules\Leave\Models\CompOffRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CompOffRequestRepository extends BaseRepository implements CompOffRequestRepositoryInterface
{
    public function __construct(CompOffRequest $model)
    {
        $this->model = $model;
    }

    public function getPaginatedList(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with('employee');
        
        if (isset($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getEarnedCompOffs(string $date, int $tenantId): Collection
    {
        return $this->model->where('worked_on_date', $date)
            ->where('status', 'approved')
            ->where('is_used', false)
            ->where('tenant_id', $tenantId)
            ->get();
    }

    public function getUnsettledCompOffs(int $employeeId, int $limit): Collection
    {
        return $this->model->where('employee_id', $employeeId)
            ->where('status', 'approved')
            ->where('is_used', false)
            ->orderBy('worked_on_date')
            ->limit($limit)
            ->get();
    }

    public function update(int|string $id, array $data): CompOffRequest
    {
        $record = $this->findOrFail($id);
        $record->update($data);
        return $record->fresh();
    }
}
