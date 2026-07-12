<?php

namespace App\Modules\Performance\Repositories;

use App\Modules\Performance\Models\Goal;
use Illuminate\Pagination\LengthAwarePaginator;

class GoalRepository implements GoalRepositoryInterface
{
    public function getPaginatedWithRelations(int $perPage = 15): LengthAwarePaginator
    {
        return Goal::with('employee')->paginate($perPage);
    }

    public function create(array $data): Goal
    {
        return Goal::create($data);
    }

    public function update(Goal $goal, array $data): bool
    {
        return $goal->update($data);
    }

    public function getActiveCount(): int
    {
        return Goal::where('status', 'in_progress')->count();
    }
}
