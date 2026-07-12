<?php

namespace App\Modules\Performance\Repositories;

use App\Modules\Performance\Models\Goal;
use Illuminate\Pagination\LengthAwarePaginator;

interface GoalRepositoryInterface
{
    public function getPaginatedWithRelations(int $perPage = 15): LengthAwarePaginator;
    public function create(array $data): Goal;
    public function update(Goal $goal, array $data): bool;
    public function getActiveCount(): int;
}
