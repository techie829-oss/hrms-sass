<?php

namespace App\Modules\Performance\Services;

use App\Modules\Performance\Models\Goal;
use App\Modules\Performance\DTOs\GoalData;
use App\Modules\Performance\Repositories\GoalRepositoryInterface;

class GoalService
{
    public function __construct(
        protected GoalRepositoryInterface $goalRepository
    ) {}

    public function createGoal(GoalData $data): Goal
    {
        return $this->goalRepository->create($data->toStoreArray());
    }

    public function updateGoal(Goal $goal, GoalData $data): Goal
    {
        $this->goalRepository->update($goal, $data->toUpdateArray());
        return $goal->fresh();
    }

    public function getActiveCount(): int
    {
        return $this->goalRepository->getActiveCount();
    }

    public function getPaginatedWithRelations(int $perPage = 15)
    {
        return $this->goalRepository->getPaginatedWithRelations($perPage);
    }
}
