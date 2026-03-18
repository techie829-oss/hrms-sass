<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold uppercase tracking-tight">Global Tasks</h2>
                <p class="text-xs font-medium mt-1 opacity-70">Monitor and manage work across all projects.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('operations.tasks.create') }}" class="btn btn-primary btn-sm">
                    <span class="material-symbols-outlined text-base">add_task</span>
                    Create New Task
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Filters -->
        <div class="lg:col-span-1">
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body p-4 lg:p-6">
                    <h3 class="font-bold text-xs uppercase text-base-content/50 mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">filter_list</span>
                        Advanced Filters
                    </h3>
                    
                    <form action="{{ route('operations.tasks.index') }}" method="GET" class="space-y-6">
                        <div class="form-control">
                            <label class="label text-[10px] uppercase font-bold text-base-content/60 py-1">Project</label>
                            <select name="project_id" class="select select-sm select-bordered w-full text-xs">
                                <option value="">All Projects</option>
                                <option value="none" {{ request('project_id') == 'none' ? 'selected' : '' }}>No Project (General)</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label text-[10px] uppercase font-bold text-base-content/60 py-1">Assignee</label>
                            <select name="assigned_to" class="select select-sm select-bordered w-full text-xs">
                                <option value="">All Team Members</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ request('assigned_to') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label text-[10px] uppercase font-bold text-base-content/60 py-1">Status</label>
                            <select name="status" class="select select-sm select-bordered w-full text-xs">
                                <option value="">All Statuses</option>
                                <option value="todo" {{ request('status') == 'todo' ? 'selected' : '' }}>To Do</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>Review</option>
                                <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label text-[10px] uppercase font-bold text-base-content/60 py-1">Due Date</label>
                            <input type="date" name="due_date" value="{{ request('due_date') }}" class="input input-sm input-bordered w-full text-xs">
                        </div>

                        <div class="flex flex-col gap-2 pt-4">
                            <button type="submit" class="btn btn-primary btn-sm btn-block">Apply Filters</button>
                            <a href="{{ route('operations.tasks.index') }}" class="btn btn-ghost btn-xs btn-block opacity-50">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Task List -->
        <div class="lg:col-span-3">
            <div class="card bg-base-100 shadow-sm border border-base-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full whitespace-nowrap">
                        <thead>
                            <tr class="bg-base-200/50">
                                <th class="text-[10px] uppercase font-bold py-4 pl-6">Task Details</th>
                                <th class="text-[10px] uppercase font-bold py-4">Assigned To</th>
                                <th class="text-[10px] uppercase font-bold py-4">Project</th>
                                <th class="text-[10px] uppercase font-bold py-4">Status</th>
                                <th class="text-[10px] uppercase font-bold py-4 text-right pr-6">Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
                                <tr class="hover:bg-base-200/30 transition-colors cursor-pointer group" onclick="window.location='{{ route('operations.tasks.edit', $task) }}'">
                                    <td class="py-4 pl-6">
                                        <div class="font-bold text-sm group-hover:text-primary transition-colors">{{ $task->title }}</div>
                                        <div class="flex items-center gap-2 mt-1">
                                            @php
                                                $priorityColors = [
                                                    'low' => 'bg-info/10 text-info',
                                                    'medium' => 'bg-warning/10 text-warning',
                                                    'high' => 'bg-error/10 text-error',
                                                    'urgent' => 'bg-error text-error-content'
                                                ];
                                            @endphp
                                            <span class="badge badge-ghost border-none text-[8px] uppercase font-bold {{ $priorityColors[$task->priority] ?? 'bg-base-200' }}">
                                                {{ $task->priority }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($task->assignee)
                                            <div class="flex items-center gap-2">
                                                <div class="avatar placeholder">
                                                    <div class="bg-primary/10 text-primary rounded-full w-6 h-6">
                                                        <span class="text-[10px] font-bold">{{ substr($task->assignee->first_name, 0, 1) }}</span>
                                                    </div>
                                                </div>
                                                <div class="text-xs font-medium">{{ $task->assignee->full_name }}</div>
                                            </div>
                                        @else
                                            <span class="text-xs opacity-30 italic">Unassigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($task->project)
                                            <span class="text-xs font-semibold text-primary/80">{{ $task->project->name }}</span>
                                        @else
                                            <span class="text-xs opacity-40 italic">Standalone</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'todo' => 'badge-ghost opacity-50',
                                                'in_progress' => 'badge-info',
                                                'review' => 'badge-warning',
                                                'done' => 'badge-success'
                                            ];
                                        @endphp
                                        <span class="badge {{ $statusColors[$task->status] ?? 'badge-ghost' }} badge-sm font-bold text-[9px] uppercase">
                                            {{ str_replace('_', ' ', $task->status) }}
                                        </span>
                                    </td>
                                    <td class="text-right pr-6">
                                        @if($task->due_date)
                                            <div class="text-xs font-bold {{ $task->due_date->isPast() && $task->status !== 'done' ? 'text-error' : '' }}">
                                                {{ $task->due_date->format('M d, Y') }}
                                            </div>
                                            <div class="text-[9px] opacity-40 font-bold uppercase">{{ $task->due_date->diffForHumans() }}</div>
                                        @else
                                            <span class="text-xs opacity-20">No deadline</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-20 text-center bg-base-200/20">
                                        <div class="flex flex-col items-center gap-2 opacity-30">
                                            <span class="material-symbols-outlined text-5xl">inventory_2</span>
                                            <p class="text-sm font-medium">No tasks found matching your filters.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-6">
                {{ $tasks->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
