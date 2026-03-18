<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('operations.projects.index') }}" class="btn btn-ghost btn-sm btn-square">
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-xl font-bold">{{ $project->name }}</h2>
                    <p class="text-xs font-medium mt-1 opacity-70">{{ $project->client->name ?? 'Internal Project' }} &bull; Project Details</p>
                </div>
            </div>
            <div class="flex gap-2 items-center">
                <div class="badge badge-sm {{ $project->status === 'completed' ? 'badge-success' : 'badge-info' }} rounded-md py-3 px-3 font-bold uppercase tracking-wider">
                    {{ str_replace('_', ' ', $project->status) }}
                </div>
                <button class="btn btn-outline btn-sm">
                    <span class="material-symbols-outlined text-base">edit</span>
                    Edit
                </button>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Stats -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="stats shadow border border-base-200">
                        <div class="stat">
                            <div class="stat-title text-xs uppercase font-bold text-base-content/50">Tasks</div>
                            <div class="stat-value text-2xl">{{ $project->tasks->count() }}</div>
                            <div class="stat-desc text-success">{{ $project->tasks->where('status', 'done')->count() }} completed</div>
                        </div>
                    </div>
                    <div class="stats shadow border border-base-200">
                        <div class="stat">
                            <div class="stat-title text-xs uppercase font-bold text-base-content/50">Hours Logged</div>
                            <div class="stat-value text-2xl">{{ $project->timesheets->sum('hours') }}</div>
                            <div class="stat-desc text-info">By {{ $project->timesheets->unique('employee_id')->count() }} team members</div>
                        </div>
                    </div>
                    <div class="stats shadow border border-base-200">
                        <div class="stat">
                            <div class="stat-title text-xs uppercase font-bold text-base-content/50">Budget</div>
                            <div class="stat-value text-2xl">${{ number_format($project->budget, 0) }}</div>
                            <div class="stat-desc text-base-content/60">Estimated</div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="card bg-base-100 shadow-sm border border-base-200">
                    <div class="card-body">
                        <h3 class="font-bold text-lg mb-4">Project Overview</h3>
                        <div class="text-base-content/80 trix-content">
                            {!! $project->description ?: 'No overview provided.' !!}
                        </div>
                        
                        <div class="grid grid-cols-2 gap-8 mt-8 pt-8 border-t border-base-200">
                            <div>
                                <span class="text-xs font-bold uppercase text-base-content/40 block mb-1">Start Date</span>
                                <span class="font-medium">{{ $project->start_date ? $project->start_date->format('M d, Y') : 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-xs font-bold uppercase text-base-content/40 block mb-1">Deadline</span>
                                <span class="font-medium text-error">{{ $project->deadline ? $project->deadline->format('M d, Y') : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tasks Kanban Lite -->
                <div class="card bg-base-100 shadow-sm border border-base-200">
                    <div class="card-body p-0">
                        <div class="flex justify-between items-center p-6 border-b border-base-200">
                            <h3 class="font-bold text-lg">Task Board</h3>
                            <button onclick="task_modal.showModal()" class="btn btn-primary btn-sm">Add Task</button>
                        </div>
                        
                        <div class="flex overflow-x-auto p-6 gap-4 min-h-[400px]">
                            @foreach(['todo', 'in_progress', 'review', 'done'] as $status)
                                <div class="flex-shrink-0 w-72">
                                    <div class="flex items-center gap-2 mb-4 px-2">
                                        <span class="font-bold text-xs uppercase text-base-content/50">{{ str_replace('_', ' ', $status) }}</span>
                                        <span class="badge badge-sm badge-ghost">{{ $project->tasks->where('status', $status)->count() }}</span>
                                    </div>
                                    <div class="space-y-3">
                                        @foreach($project->tasks->where('status', $status) as $task)
                                            <div class="card bg-base-100 shadow-sm border border-base-200 cursor-pointer hover:border-primary/30 transition-colors">
                                                <div class="card-body p-4">
                                                    <div class="flex justify-between mb-2">
                                                        <span class="badge badge-xs text-[10px] {{ 
                                                            $task->priority === 'urgent' ? 'badge-error' : 
                                                            ($task->priority === 'high' ? 'badge-warning' : 'badge-ghost') 
                                                        }} uppercase font-bold">{{ $task->priority }}</span>
                                                    </div>
                                                    <h4 class="font-bold text-sm leading-tight">{{ $task->title }}</h4>
                                                    <div class="flex justify-between items-center mt-4">
                                                        <div class="avatar-group -space-x-4 rtl:space-x-reverse">
                                                            <div class="avatar placeholder border-base-100">
                                                                <div class="bg-primary text-primary-content rounded-full w-6">
                                                                    <span class="text-[10px] font-bold">{{ substr($task->assignee->first_name ?? 'U', 0, 1) }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if($task->due_date)
                                                            <span class="text-[10px] text-base-content/50 flex items-center gap-1">
                                                                <span class="material-symbols-outlined text-[10px]">timer</span>
                                                                {{ $task->due_date->format('M d') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-8">
                <!-- Client Card -->
                <div class="card bg-primary text-primary-content shadow-lg">
                    <div class="card-body">
                        <h3 class="text-xs font-bold uppercase opacity-70">Client Information</h3>
                        @if($project->client)
                            <p class="text-xl font-bold mt-2">{{ $project->client->name }}</p>
                            <p class="opacity-80 text-sm">{{ $project->client->company }}</p>
                            <div class="mt-4 pt-4 border-t border-primary-focus space-y-2">
                                <div class="flex items-center gap-2 text-sm opacity-90">
                                    <span class="material-symbols-outlined text-base">mail</span>
                                    <span>{{ $project->client->email }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm opacity-90">
                                    <span class="material-symbols-outlined text-base">call</span>
                                    <span>{{ $project->client->phone }}</span>
                                </div>
                            </div>
                        @else
                            <p class="text-xl font-bold mt-2 font-display">Internal Project</p>
                            <p class="opacity-80 text-sm">Managed by HRMS Admin</p>
                        @endif
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card bg-base-100 shadow-sm border border-base-200">
                    <div class="card-body pt-6 px-6 pb-2">
                        <h3 class="font-bold text-lg mb-4">Latest Timesheets</h3>
                        <div class="space-y-6">
                            @forelse($project->timesheets->take(5) as $log)
                                <div class="flex gap-4 relative">
                                    <div class="avatar placeholder h-min">
                                        <div class="bg-base-300 text-base-content rounded-full w-8 h-8">
                                            <span class="text-xs">{{ substr($log->employee->first_name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-1 pb-4 border-b border-base-100">
                                        <p class="text-sm font-bold">{{ $log->employee->full_name }} logged {{ $log->hours }} hours</p>
                                        <p class="text-xs text-base-content/50 mt-1">{{ $log->date->format('M d, Y') }} &bull; {{ $log->description }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center py-4 text-base-content/40 text-sm italic">No hours logged yet.</p>
                            @endforelse
                        </div>
                        <div class="card-actions justify-center mt-2">
                             <button class="btn btn-ghost btn-sm btn-block">View All Logs</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Modal -->
    <dialog id="task_modal" class="modal">
      <div class="modal-box p-0 overflow-hidden">
        <div class="p-6 bg-base-200 border-b border-base-300 flex justify-between items-center">
            <h3 class="font-bold text-lg">Assign New Task</h3>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
        </div>
        <div class="p-6">
            <form action="{{ route('operations.projects.tasks.store', $project) }}" method="POST">
                @csrf
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text font-bold text-xs uppercase">Task Summary</span></label>
                    <input type="text" name="title" class="input input-bordered w-full" required placeholder="What needs to be done?">
                </div>
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text font-bold text-xs uppercase">Task Description</span></label>
                    <x-editor name="description" placeholder="Provide more details about this task..." />
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-xs uppercase">Priority</span></label>
                        <select name="priority" class="select select-bordered w-full">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-xs uppercase">Due Date</span></label>
                        <input type="date" name="due_date" class="input input-bordered w-full">
                    </div>
                </div>
                <div class="form-control mb-6">
                    <label class="label"><span class="label-text font-bold text-xs uppercase">Assign To</span></label>
                    <x-rich-select name="assigned_to" placeholder="Search for an employee">
                        <option value="">Unassigned</option>
                        @foreach(\App\Modules\HR\Models\Employee::where('tenant_id', tenant('id'))->get() as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                        @endforeach
                    </x-rich-select>
                </div>
                <div class="card-actions justify-end">
                    <button type="submit" class="btn btn-primary btn-block">Create Task</button>
                </div>
            </form>
        </div>
      </div>
    </dialog>
</x-app-layout>
