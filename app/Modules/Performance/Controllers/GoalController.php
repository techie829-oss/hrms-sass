<?php

namespace App\Modules\Performance\Controllers;

use App\Core\BaseController;
use App\Modules\Performance\Models\Goal;
use App\Modules\HR\Models\Employee;
use Illuminate\Http\Request;

class GoalController extends BaseController
{
    public function index()
    {
        $goals = Goal::with('employee')->paginate(15);
        $employees = Employee::active()->get();
        return view('modules.performance.goals.index', compact('goals', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        Goal::create($validated);

        return redirect()->route('performance.goals.index')
            ->with('success', 'Goal created successfully.');
    }

    public function update(Request $request, Goal $goal)
    {
        $validated = $request->validate([
            'progress_percentage' => ['required', 'integer', 'min:0', 'max:100'],
            'status' => ['required', 'in:in_progress,completed,cancelled'],
        ]);

        $goal->update($validated);

        return redirect()->route('performance.goals.index')
            ->with('success', 'Goal progress updated.');
    }
}
