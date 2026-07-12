<?php

namespace App\Modules\Performance\Repositories;

use App\Modules\Performance\Models\Appraisal;
use Illuminate\Pagination\LengthAwarePaginator;

class AppraisalRepository implements AppraisalRepositoryInterface
{
    public function getPaginatedWithRelations(int $perPage = 15): LengthAwarePaginator
    {
        return Appraisal::with(['employee', 'evaluator'])->paginate($perPage);
    }

    public function create(array $data): Appraisal
    {
        return Appraisal::create($data);
    }

    public function update(Appraisal $appraisal, array $data): bool
    {
        return $appraisal->update($data);
    }

    public function getPendingCount(): int
    {
        return Appraisal::where('status', 'pending')->count();
    }

    public function getRecentAppraisals(int $limit = 5)
    {
        return Appraisal::with('employee')
            ->latest()
            ->take($limit)
            ->get();
    }
}
