<?php

namespace App\Modules\Performance\Services;

use App\Modules\Performance\Models\Appraisal;
use App\Modules\Performance\DTOs\AppraisalData;
use App\Modules\Performance\Repositories\AppraisalRepositoryInterface;

class AppraisalService
{
    public function __construct(
        protected AppraisalRepositoryInterface $appraisalRepository
    ) {}

    public function createAppraisal(AppraisalData $data): Appraisal
    {
        return $this->appraisalRepository->create($data->toStoreArray());
    }

    public function updateAppraisal(Appraisal $appraisal, AppraisalData $data): Appraisal
    {
        $this->appraisalRepository->update($appraisal, $data->toUpdateArray());
        return $appraisal->fresh();
    }

    public function getPendingCount(): int
    {
        return $this->appraisalRepository->getPendingCount();
    }

    public function getRecentAppraisals(int $limit = 5)
    {
        return $this->appraisalRepository->getRecentAppraisals($limit);
    }

    public function getPaginatedWithRelations(int $perPage = 15)
    {
        return $this->appraisalRepository->getPaginatedWithRelations($perPage);
    }
}
