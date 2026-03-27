<?php

namespace App\Modules\Operations\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Operations\Models\Task;
use App\Modules\Operations\Models\Project;
use App\Modules\HR\Models\Employee;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::where('tenant_id', saas_tenant('id'))
            ->with(['project', 'assignee']);

        // Filtering
        if ($request->filled('project_id')) {
            if ($request->project_id === 'none') {
                $query->whereNull('project_id');
            } else {
                $query->where('project_id', $request->project_id);
            }
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }

        $tasks = $query->latest()->paginate(20);
        $employees = Employee::where('tenant_id', saas_tenant('id'))->get();
        $projects = Project::where('tenant_id', saas_tenant('id'))->get();

        return view('operations::tasks.index', compact('tasks', 'employees', 'projects'));
    }

    public function create()
    {
        $employees = Employee::where('tenant_id', saas_tenant('id'))->get();
        $projects = Project::where('tenant_id', saas_tenant('id'))->get();
        return view('operations::tasks.create', compact('employees', 'projects'));
    }

    public function store(Request $request, Project $project = null)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'project_id' => 'nullable|exists:projects,id',
            'assigned_to' => 'nullable|exists:employees,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $validated['tenant_id'] = saas_tenant('id');
        if ($project) {
            $validated['project_id'] = $project->id;
        }
        $validated['status'] = 'todo';

        Task::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Task assigned successfully.']);
        }

        return redirect()->route('operations.tasks.index')->with('success', 'Task assigned successfully.');
    }

    public function edit(Task $task)
    {
        $employees = Employee::where('tenant_id', saas_tenant('id'))->get();
        $projects = Project::where('tenant_id', saas_tenant('id'))->get();
        return view('operations::tasks.edit', compact('task', 'employees', 'projects'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'project_id' => 'nullable|exists:projects,id',
            'assigned_to' => 'sometimes|nullable|exists:employees,id',
            'status' => 'sometimes|required|in:todo,in_progress,review,done',
            'priority' => 'sometimes|required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        if (isset($validated['status']) && $validated['status'] === 'done' && $task->status !== 'done') {
            $validated['completed_at'] = now();
        }

        $task->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Task updated.']);
        }

        return redirect()->route('operations.tasks.index')->with('success', 'Task updated.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('operations.tasks.index')->with('success', 'Task deleted.');
    }
}
