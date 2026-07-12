<?php

namespace App\Modules\Performance\Controllers;

use App\Core\BaseController;
use App\Modules\Performance\Models\Goal;
use App\Modules\HR\Services\EmployeeService;
use App\Modules\Performance\Requests\StoreGoalRequest;
use App\Modules\Performance\Requests\UpdateGoalRequest;
use App\Modules\Performance\DTOs\GoalData;
use App\Modules\Performance\Services\GoalService;

class GoalController extends BaseController
{
    public function __construct(
        protected GoalService $goalService,
        protected EmployeeService $employeeService
    ) {
        $this->authorizeResource(Goal::class, 'goal');
    }

    public function index()
    {
        $goals = $this->goalService->getPaginatedWithRelations(15);
        $employees = $this->employeeService->getActiveEmployees();
        return view('performance::goals.index', compact('goals', 'employees'));
    }

    public function store(StoreGoalRequest $request)
    {
        $dto = GoalData::fromStoreRequest($request->validated());
        $this->goalService->createGoal($dto);

        return redirect()->route('performance.goals.index')
            ->with('success', 'Goal created successfully.');
    }

    public function update(UpdateGoalRequest $request, Goal $goal)
    {
        $dto = GoalData::fromUpdateRequest($request->validated());
        $this->goalService->updateGoal($goal, $dto);

        return redirect()->route('performance.goals.index')
            ->with('success', 'Goal progress updated.');
    }
}
