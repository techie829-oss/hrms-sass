<?php

namespace App\Modules\Operations\Controllers;

use App\Core\BaseController;
use App\Modules\Operations\Models\Task;
use App\Modules\Operations\Models\Project;
use App\Modules\HR\Models\Employee;
use Illuminate\Http\Request;
use App\Modules\Operations\Requests\StoreTaskRequest;
use App\Modules\Operations\Requests\UpdateTaskRequest;
use App\Modules\Operations\DTOs\TaskData;
use App\Modules\Operations\Services\TaskService;

class TaskController extends BaseController
{
    public function __construct(
        protected TaskService $taskService
    ) {
        $this->authorizeResource(Task::class, 'task');
    }

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

    public function store(StoreTaskRequest $request, ?Project $project = null)
    {
        $validated = $request->validated();
        if ($project) {
            $validated['project_id'] = $project->id;
        }
        $validated['status'] = 'todo';

        $dto = TaskData::fromArray($validated, saas_tenant('id'));
        $this->taskService->createTask($dto);

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

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validated = $request->validated();
        
        if (isset($validated['status']) && $validated['status'] === 'done' && $task->status !== 'done') {
            $validated['completed_at'] = now()->toDateTimeString();
        } elseif ($task->status === 'done' && (!isset($validated['status']) || $validated['status'] === 'done')) {
            $validated['completed_at'] = $task->completed_at;
        }

        $dto = TaskData::fromArray($validated, saas_tenant('id'));
        $this->taskService->updateTask($task, $dto);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Task updated.']);
        }

        return redirect()->route('operations.tasks.index')->with('success', 'Task updated.');
    }

    public function destroy(Task $task)
    {
        $this->taskService->deleteTask($task);
        return redirect()->route('operations.tasks.index')->with('success', 'Task deleted.');
    }
}
