<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">Timesheets</h2>
                <p class="text-xs font-medium mt-1 opacity-70">Log and track time spent on project tasks.</p>
            </div>
            <div class="flex gap-2">
                <button onclick="log_time_modal.showModal()" class="btn btn-primary btn-sm">
                    <span class="material-symbols-outlined text-base">timer</span>
                    Log Time
                </button>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Summary Stats -->
            <div class="lg:col-span-1 space-y-4">
                <div class="stats stats-vertical shadow w-full border border-base-200">
                    <div class="stat">
                        <div class="stat-title text-xs uppercase font-bold text-base-content/50">Total Hours</div>
                        <div class="stat-value text-3xl">{{ $timesheets->sum('hours') }}</div>
                        <div class="stat-desc">This month</div>
                    </div>
                    <div class="stat">
                        <div class="stat-title text-xs uppercase font-bold text-base-content/50">Active Projects</div>
                        <div class="stat-value text-3xl">{{ $timesheets->whereNotNull('project_id')->unique('project_id')->count() }}</div>
                        <div class="stat-desc">Across all clients</div>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-sm border border-base-200">
                    <div class="card-body">
                        <h3 class="font-bold text-sm uppercase text-base-content/50 mb-4">Filters</h3>
                        <!-- Add filter form controls here -->
                        <div class="space-y-4">
                            <div class="form-control">
                                <label class="label"><span class="label-text">Date Range</span></label>
                                <select class="select select-bordered select-sm w-full">
                                    <option>This Week</option>
                                    <option selected>This Month</option>
                                    <option>Last Month</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timesheet table -->
            <div class="lg:col-span-3">
                <div class="card bg-base-100 shadow-sm border border-base-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr class="bg-base-200/50">
                                    <th class="text-xs uppercase font-bold py-4">Date</th>
                                    <th class="text-xs uppercase font-bold py-4">Time</th>
                                    <th class="text-xs uppercase font-bold py-4">Project/Task</th>
                                    <th class="text-xs uppercase font-bold py-4">Description</th>
                                    <th class="text-xs uppercase font-bold py-4 text-center">Hours</th>
                                    <th class="text-xs uppercase font-bold py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($timesheets as $log)
                                    <tr>
                                        <td>
                                            <div class="font-bold">{{ $log->date->format('M d, Y') }}</div>
                                            <div class="text-[10px] opacity-50 font-bold uppercase">{{ $log->date->format('l') }}</div>
                                        </td>
                                        <td>
                                            <div class="text-xs font-bold">{{ $log->start_time ? $log->start_time->format('H:i') : '--:--' }} - {{ $log->end_time ? $log->end_time->format('H:i') : '--:--' }}</div>
                                        </td>
                                        <td>
                                            @if($log->project)
                                                <div class="font-semibold text-primary">{{ $log->project->name }}</div>
                                            @else
                                                <div class="font-semibold text-base-content/40 italic">General / Internal</div>
                                            @endif
                                            <div class="text-xs opacity-60 italic">{{ $log->task->title ?? 'Daily Update' }}</div>
                                        </td>
                                        <td>
                                            <p class="text-sm line-clamp-1 max-w-xs">{{ $log->description }}</p>
                                        </td>
                                        <td class="text-center font-bold">
                                            {{ number_format($log->hours, 2) }}h
                                        </td>
                                        <td class="text-right">
                                            <button class="btn btn-xs btn-ghost">Edit</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-20 text-center bg-base-200/50">
                                            <span class="material-symbols-outlined text-4xl text-base-content/20 mb-2">work_history</span>
                                            <p class="text-sm opacity-50">No time logs found for this period.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-6">
                    {{ $timesheets->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Log Time Modal -->
    <dialog id="log_time_modal" class="modal">
      <div class="modal-box p-0 overflow-hidden max-w-2xl">
        <div class="p-6 bg-base-200 border-b border-base-300 flex justify-between items-center">
            <h3 class="font-bold text-lg">Daily Sprint Log</h3>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
        </div>
        <div class="p-6">
            <form action="{{ route('operations.timesheets.store') }}" method="POST" id="sprint_log_form">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="form-control">
                        <label class="label text-xs uppercase font-bold">Select Project (Optional)</label>
                        <select name="project_id" class="select select-bordered w-full">
                            <option value="">General / Outside Project</option>
                            @foreach(\App\Modules\Operations\Models\Project::where('tenant_id', tenant('id'))->get() as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label text-xs uppercase font-bold">Date*</label>
                        <input type="date" name="date" class="input input-bordered w-full" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="form-control">
                        <label class="label text-xs uppercase font-bold">Start Time</label>
                        <input type="time" name="start_time" id="start_time" class="input input-bordered w-full">
                    </div>
                    <div class="form-control">
                        <label class="label text-xs uppercase font-bold">End Time</label>
                        <input type="time" name="end_time" id="end_time" class="input input-bordered w-full">
                    </div>
                    <div class="form-control">
                        <label class="label text-xs uppercase font-bold">Total Hours*</label>
                        <input type="number" name="hours" id="total_hours" step="0.01" class="input input-bordered w-full bg-base-200" placeholder="0.00" required>
                    </div>
                </div>

                <div class="form-control mb-6">
                    <label class="label text-xs uppercase font-bold">Daily Update / Description*</label>
                    <textarea name="description" class="textarea textarea-bordered h-24" placeholder="Briefly describe what was accomplished during this sprint..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Submit Sprint Log</button>
            </form>
        </div>
      </div>
    </dialog>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startTimeInput = document.getElementById('start_time');
            const endTimeInput = document.getElementById('end_time');
            const hoursInput = document.getElementById('total_hours');

            function calculateHours() {
                if (startTimeInput.value && endTimeInput.value) {
                    const start = new Date(`1970-01-01T${startTimeInput.value}:00`);
                    const end = new Date(`1970-01-01T${endTimeInput.value}:00`);
                    
                    let diffMs = end - start;
                    if (diffMs < 0) diffMs += 24 * 60 * 60 * 1000; // Handle overnight shifts
                    
                    const hours = (diffMs / (1000 * 60 * 60)).toFixed(2);
                    hoursInput.value = hours;
                }
            }

            startTimeInput.addEventListener('change', calculateHours);
            endTimeInput.addEventListener('change', calculateHours);
        });
    </script>
</x-app-layout>
</x-app-layout>
