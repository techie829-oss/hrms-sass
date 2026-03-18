<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('attendance.index') }}" class="btn btn-ghost btn-sm btn-square">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="text-xl font-bold">Attendance Record</h2>
                <p class="text-sm opacity-70 mt-1">
                    {{ $log->employee->first_name }} {{ $log->employee->last_name }} &mdash; {{ $log->date->format('F d, Y') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body p-0">
                    <div class="p-6 border-b border-base-200 bg-base-200/50">
                        <h3 class="text-sm font-bold uppercase tracking-widest">Time Details</h3>
                    </div>
                    <div class="p-6 grid grid-cols-2 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-[10px] font-bold opacity-60 uppercase tracking-wider mb-1">Check In</p>
                            <p class="text-2xl font-bold text-success">{{ $log->check_in ? $log->check_in->format('H:i') : '--:--' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold opacity-60 uppercase tracking-wider mb-1">Check Out</p>
                            <p class="text-2xl font-bold text-error">{{ $log->check_out ? $log->check_out->format('H:i') : '--:--' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold opacity-60 uppercase tracking-wider mb-1">Hours Worked</p>
                            <p class="text-2xl font-bold text-primary">{{ $log->worked_hours ?? '0.00' }}h</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold opacity-60 uppercase tracking-wider mb-1">Status</p>
                            @php
                                $statusClasses = [
                                    'present' => 'badge-success',
                                    'absent' => 'badge-error',
                                    'half_day' => 'badge-warning',
                                    'late' => 'badge-info',
                                    'on_leave' => 'badge-ghost',
                                ];
                                $statusBadge = $statusClasses[$log->status] ?? 'badge-ghost';
                            @endphp
                            <span class="badge {{ $statusBadge }} font-bold uppercase">{{ str_replace('_', ' ', $log->status) }}</span>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold opacity-60 uppercase tracking-wider mb-1">Late?</p>
                            <p class="text-sm font-semibold">
                                {{ $log->is_late ? '⚠️ Yes (' . $log->late_minutes . ' min)' : '✅ No' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold opacity-60 uppercase tracking-wider mb-1">Overtime</p>
                            <p class="text-sm font-semibold">
                                {{ $log->overtime_minutes ? $log->overtime_minutes . ' min' : 'None' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if($log->remarks)
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="text-xs font-bold text-primary uppercase tracking-widest mb-3">Remarks</h3>
                    <p class="text-sm">{{ $log->remarks }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="text-xs font-bold text-primary uppercase tracking-widest border-b border-base-200 pb-3 mb-4">Employee</h3>
                    <div class="flex items-center gap-4">
                        <div class="avatar placeholder">
                            <div class="bg-primary/10 text-primary rounded-xl w-12 h-12 font-bold text-sm">
                                {{ strtoupper(substr($log->employee->first_name, 0, 1) . substr($log->employee->last_name, 0, 1)) }}
                            </div>
                        </div>
                        <div>
                            <div class="font-bold">{{ $log->employee->first_name }} {{ $log->employee->last_name }}</div>
                            <div class="text-xs opacity-60">{{ $log->employee->employee_id }}</div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('hr.employees.show', $log->employee->id) }}" class="btn btn-sm btn-outline btn-primary w-full">View Profile</a>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="text-xs font-bold text-primary uppercase tracking-widest border-b border-base-200 pb-3 mb-4">Record Info</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-[10px] font-bold opacity-60 uppercase tracking-wider mb-1">Date</p>
                            <p class="text-sm font-semibold">{{ $log->date->format('l, F d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold opacity-60 uppercase tracking-wider mb-1">Logged At</p>
                            <p class="text-sm font-semibold">{{ $log->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
