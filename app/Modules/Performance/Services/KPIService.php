<?php

namespace App\Modules\Performance\Services;

use App\Modules\Performance\Models\KPI;
use App\Modules\Performance\DTOs\KPIData;
use App\Modules\Performance\Repositories\KPIRepositoryInterface;

class KPIService
{
    public function __construct(
        protected KPIRepositoryInterface $kpiRepository
    ) {}

    public function createKPI(KPIData $data): KPI
    {
        return $this->kpiRepository->create($data->toStoreArray());
    }

    public function getCount(): int
    {
        return $this->kpiRepository->getCount();
    }

    public function getPaginatedWithRelations(int $perPage = 15)
    {
        return $this->kpiRepository->getPaginatedWithRelations($perPage);
    }
}
