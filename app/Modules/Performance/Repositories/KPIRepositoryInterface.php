<?php

namespace App\Modules\Performance\Repositories;

use App\Modules\Performance\Models\KPI;
use Illuminate\Pagination\LengthAwarePaginator;

interface KPIRepositoryInterface
{
    public function getPaginatedWithRelations(int $perPage = 15): LengthAwarePaginator;
    public function create(array $data): KPI;
    public function update(KPI $kpi, array $data): bool;
    public function getCount(): int;
}
