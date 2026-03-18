<?php

namespace App\Modules\Operations\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Operations\Models\Timesheet;
use App\Modules\Operations\Models\Project;
use App\Modules\Operations\Models\Task;
use Illuminate\Http\Request;

class TimesheetController extends Controller
{
    public function index()
    {
        $timesheets = Timesheet::where('tenant_id', tenant('id'))
            ->with(['employee', 'project', 'task'])
            ->latest()
            ->paginate(15);

        $projects = Project::where('tenant_id', tenant('id'))->get();

        return view('operations::timesheets.index', compact('timesheets', 'projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'hours' => 'required|numeric|min:0.01|max:24',
            'description' => 'required|string',
        ]);

        $validated['tenant_id'] = tenant('id');
        $validated['employee_id'] = auth()->user()->employee->id ?? null;

        if (!$validated['employee_id']) {
            return back()->with('error', 'Only employees can log timesheets.');
        }

        Timesheet::create($validated);

        return back()->with('success', 'Daily sprint log submitted successfully.');
    }
}
