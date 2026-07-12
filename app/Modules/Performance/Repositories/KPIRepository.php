<?php

namespace App\Modules\Performance\Repositories;

use App\Modules\Performance\Models\KPI;
use Illuminate\Pagination\LengthAwarePaginator;

class KPIRepository implements KPIRepositoryInterface
{
    public function getPaginatedWithRelations(int $perPage = 15): LengthAwarePaginator
    {
        return KPI::with('department')->paginate($perPage);
    }

    public function create(array $data): KPI
    {
        return KPI::create($data);
    }

    public function update(KPI $kpi, array $data): bool
    {
        return $kpi->update($data);
    }

    public function getCount(): int
    {
        return KPI::count();
    }
}
