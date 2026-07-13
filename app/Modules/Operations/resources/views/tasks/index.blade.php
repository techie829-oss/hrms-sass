<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-on-surface tracking-tight">Global Tasks</h2>
                <p class="text-xs font-medium mt-0.5 text-on-surface-variant">Monitor and manage work across all projects.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('operations.tasks.create') }}" class="btn btn-primary btn-sm rounded-xl px-5 shadow-sm shadow-primary/20">
                    <span class="material-symbols-outlined text-base">add_task</span>
                    Create New Task
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Main Tasks Directory Card Box -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <!-- Card Header -->
            <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-white">
                <div class="flex items-center gap-2.5">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900">Task Directory</h3>
                    <span class="bg-gray-100 text-gray-700 text-xs font-bold px-2.5 py-0.5 rounded-full border border-gray-200">{{ $tasks->total() }}</span>
                </div>
            </div>

            <!-- Inline Filter Bar -->
            <div class="p-4 sm:p-5 border-b border-gray-100 bg-gray-50/50">
                <form action="{{ route('operations.tasks.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                    <div class="lg:col-span-3">
                        <select name="project_id" onchange="this.form.submit()" class="w-full py-2 px-3 text-xs sm:text-sm border border-gray-200 rounded-xl bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none">
                            <option value="">All Projects</option>
                            <option value="none" {{ request('project_id') == 'none' ? 'selected' : '' }}>No Project (General)</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="lg:col-span-3">
                        <select name="assigned_to" onchange="this.form.submit()" class="w-full py-2 px-3 text-xs sm:text-sm border border-gray-200 rounded-xl bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none">
                            <option value="">All Team Members</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ request('assigned_to') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="lg:col-span-3">
                        <select name="status" onchange="this.form.submit()" class="w-full py-2 px-3 text-xs sm:text-sm border border-gray-200 rounded-xl bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none">
                            <option value="">All Statuses</option>
                            <option value="todo" {{ request('status') == 'todo' ? 'selected' : '' }}>To Do</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>Review</option>
                            <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <div class="lg:col-span-3 flex items-center gap-2">
                        <input type="date" name="due_date" value="{{ request('due_date') }}" onchange="this.form.submit()" class="w-full py-2 px-3 text-xs sm:text-sm border border-gray-200 rounded-xl bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none">
                        @if(request()->anyFilled(['project_id', 'assigned_to', 'status', 'due_date']))
                            <a href="{{ route('operations.tasks.index') }}" class="px-3 py-2 text-xs font-semibold text-gray-600 hover:text-gray-900 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors shrink-0">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Desktop Table View --}}
            <div class="hidden lg:block overflow-x-auto">
                <table class="table w-full whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50/50 text-[10px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-200">
                            <th class="py-3.5 pl-6">Task Details</th>
                            <th class="py-3.5">Assigned To</th>
                            <th class="py-3.5">Project</th>
                            <th class="py-3.5">Status</th>
                            <th class="py-3.5 text-right pr-6">Due Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($tasks as $task)
                            <tr class="hover:bg-gray-50/60 transition-colors cursor-pointer group" onclick="window.location='{{ route('operations.tasks.edit', $task) }}'">
                                <td class="py-4 pl-6">
                                    <div class="font-bold text-sm text-gray-900 group-hover:text-blue-600 transition-colors">{{ $task->title }}</div>
                                    <div class="flex items-center gap-2 mt-1">
                                        @php
                                            $priorityColors = [
                                                'low' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'medium' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'high' => 'bg-red-50 text-red-700 border-red-200',
                                                'urgent' => 'bg-red-600 text-white border-red-600 font-bold'
                                            ];
                                        @endphp
                                        <span class="px-2 py-0.5 rounded-md text-[10px] uppercase font-bold border {{ $priorityColors[$task->priority] ?? 'bg-gray-100 text-gray-700 border-gray-200' }}">
                                            {{ $task->priority }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    @if($task->assignee)
                                        @php
                                            $colors = ['bg-blue-600', 'bg-indigo-600', 'bg-violet-600', 'bg-purple-600', 'bg-teal-600', 'bg-emerald-600', 'bg-cyan-600', 'bg-sky-600'];
                                            $colorClass = $colors[$task->assignee->id % count($colors)];
                                        @endphp
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-full {{ !empty($task->assignee->profile_photo) ? 'bg-gray-100 text-gray-800' : $colorClass . ' text-white' }} font-bold text-[10px] flex items-center justify-center shrink-0 overflow-hidden shadow-sm">
                                                @if(!empty($task->assignee->profile_photo))
                                                    <img src="{{ asset('storage/' . $task->assignee->profile_photo) }}" alt="" class="w-full h-full object-cover">
                                                @else
                                                    {{ strtoupper(substr($task->assignee->first_name, 0, 1) . substr($task->assignee->last_name, 0, 1)) }}
                                                @endif
                                            </div>
                                            <div class="text-xs font-semibold text-gray-800">{{ $task->assignee->full_name }}</div>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Unassigned</span>
                                    @endif
                                </td>
                                <td>
                                    @if($task->project)
                                        <span class="text-xs font-semibold text-blue-600">{{ $task->project->name }}</span>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Standalone</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'todo' => 'bg-gray-100 text-gray-700 border-gray-200',
                                            'in_progress' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'review' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'done' => 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] uppercase font-bold border {{ $statusColors[$task->status] ?? 'bg-gray-100 text-gray-700 border-gray-200' }}">
                                        {{ str_replace('_', ' ', $task->status) }}
                                    </span>
                                </td>
                                <td class="py-4 text-right pr-6">
                                    @if($task->due_date)
                                        <span class="text-xs font-semibold {{ $task->due_date->isPast() && $task->status !== 'done' ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                                            {{ $task->due_date->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400 italic">No date</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-2 text-gray-400">
                                        <span class="material-symbols-outlined text-4xl">inventory_2</span>
                                        <p class="text-sm font-medium">No tasks found matching your filters.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card Stack View --}}
            <div class="lg:hidden p-4 space-y-3 bg-gray-50/50">
                @forelse($tasks as $task)
                    @php
                        $priorityColors = [
                            'low' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'medium' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'high' => 'bg-red-50 text-red-700 border-red-200',
                            'urgent' => 'bg-red-600 text-white border-red-600 font-bold'
                        ];
                        $statusColors = [
                            'todo' => 'bg-gray-100 text-gray-700 border-gray-200',
                            'in_progress' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'review' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'done' => 'bg-emerald-50 text-emerald-700 border-emerald-200'
                        ];
                    @endphp
                    <div onclick="window.location='{{ route('operations.tasks.edit', $task) }}'" class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm space-y-3 cursor-pointer">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <div class="font-bold text-sm text-gray-900">{{ $task->title }}</div>
                                <span class="inline-block mt-1 px-2 py-0.5 rounded-md text-[10px] uppercase font-bold border {{ $priorityColors[$task->priority] ?? 'bg-gray-100 text-gray-700 border-gray-200' }}">
                                    {{ $task->priority }}
                                </span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] uppercase font-bold border {{ $statusColors[$task->status] ?? 'bg-gray-100 text-gray-700 border-gray-200' }} shrink-0">
                                {{ str_replace('_', ' ', $task->status) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between border-t border-gray-100 pt-3 text-xs">
                            <div class="flex items-center gap-2">
                                @if($task->assignee)
                                    @php
                                        $colors = ['bg-blue-600', 'bg-indigo-600', 'bg-violet-600', 'bg-purple-600', 'bg-teal-600', 'bg-emerald-600', 'bg-cyan-600', 'bg-sky-600'];
                                        $colorClass = $colors[$task->assignee->id % count($colors)];
                                    @endphp
                                    <div class="w-6 h-6 rounded-full {{ !empty($task->assignee->profile_photo) ? 'bg-gray-100 text-gray-800' : $colorClass . ' text-white' }} font-bold text-[9px] flex items-center justify-center shrink-0 overflow-hidden">
                                        @if(!empty($task->assignee->profile_photo))
                                            <img src="{{ asset('storage/' . $task->assignee->profile_photo) }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            {{ strtoupper(substr($task->assignee->first_name, 0, 1) . substr($task->assignee->last_name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <span class="font-medium text-gray-700">{{ $task->assignee->full_name }}</span>
                                @else
                                    <span class="text-gray-400 italic">Unassigned</span>
                                @endif
                            </div>
                            <div class="text-right">
                                @if($task->due_date)
                                    <span class="font-bold {{ $task->due_date->isPast() && $task->status !== 'done' ? 'text-red-600' : 'text-gray-600' }}">
                                        {{ $task->due_date->format('M d') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center bg-white border border-gray-200 rounded-xl">
                        <span class="material-symbols-outlined text-4xl text-gray-400 mb-2">inventory_2</span>
                        <p class="font-bold text-xs text-gray-500">No tasks found matching your filters.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($tasks->hasPages())
            <div class="px-5 py-3 border-t border-gray-100 bg-gray-50/50">
                {{ $tasks->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
