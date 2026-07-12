<?php

namespace App\Modules\Performance\Repositories;

use App\Modules\Performance\Models\Appraisal;
use Illuminate\Pagination\LengthAwarePaginator;

interface AppraisalRepositoryInterface
{
    public function getPaginatedWithRelations(int $perPage = 15): LengthAwarePaginator;
    public function create(array $data): Appraisal;
    public function update(Appraisal $appraisal, array $data): bool;
    public function getPendingCount(): int;
    public function getRecentAppraisals(int $limit = 5);
}
