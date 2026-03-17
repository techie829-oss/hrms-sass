<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">Attendance</h2>
                <p class="text-xs font-medium mt-1 opacity-70">Track and manage employee daily presence.</p>
            </div>
            <div class="flex gap-2">
                <button class="btn btn-ghost btn-sm btn-outline">
                    <span class="material-symbols-outlined text-base">filter_list</span> Filters
                </button>
                <a href="{{ route('attendance.create') }}" class="btn btn-primary btn-sm">
                    <span class="material-symbols-outlined text-base">add</span> Manual Log
                </a>
            </div>
        </div>
    </x-slot>

    <div class="card bg-base-100 shadow-sm border border-base-200 overflow-hidden">
        <div class="p-4 border-b border-base-200 bg-base-200/30">
            <div class="relative max-w-sm">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 opacity-50 text-lg">search</span>
                <input type="text" placeholder="Search employee..." class="input input-bordered input-sm w-full pl-10 px-4 py-2 text-xs">
            </div>
        </div>

        <x-table :headers="['Employee', 'Date', 'Check In', 'Check Out', 'Hours', 'Status', 'Actions']" :striped="false">
            @forelse($logs as $log)
                <tr class="hover:bg-base-200/50 transition-colors border-b border-base-200">
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <div class="avatar placeholder">
                                <div class="bg-primary/10 text-primary rounded-lg w-8 h-8 font-bold text-[10px] border border-primary/10">
                                    {{ strtoupper(substr($log->employee->first_name, 0, 1) . substr($log->employee->last_name, 0, 1)) }}
                                </div>
                            </div>
                            <div>
                                <div class="font-bold text-xs">{{ $log->employee->full_name }}</div>
                                <div class="text-[9px] font-bold uppercase tracking-wider opacity-60">{{ $log->employee->employee_id }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="text-xs font-bold">{{ $log->date->format('M d, Y') }}</div>
                    </td>
                    <td>
                        <div class="text-xs font-medium text-success">{{ $log->check_in ? $log->check_in->format('H:i') : '--:--' }}</div>
                    </td>
                    <td>
                        <div class="text-xs font-medium text-error">{{ $log->check_out ? $log->check_out->format('H:i') : '--:--' }}</div>
                    </td>
                    <td>
                        <div class="flex flex-col">
                            <div class="text-xs font-bold">{{ $log->worked_hours ?? '0.00' }}h</div>
                            @if($log->is_late)
                                <div class="text-[8px] font-bold text-info uppercase">Late: {{ $log->late_minutes }}m</div>
                            @endif
                            @if($log->overtime_minutes > 0)
                                <div class="text-[8px] font-bold text-success uppercase">OT: {{ $log->overtime_minutes }}m</div>
                            @endif
                        </div>
                    </td>
                    <td>
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
                        <span class="badge {{ $statusBadge }} badge-sm font-bold text-[10px] uppercase">{{ str_replace('_', ' ', $log->status) }}</span>
                    </td>
                    <td class="text-right px-4">
                        <div class="flex justify-end gap-1">
                            <a href="{{ route('attendance.show', $log->id) }}" class="btn btn-ghost btn-xs btn-square text-primary hover:bg-primary/10">
                                <span class="material-symbols-outlined text-base">visibility</span>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-16 text-center">
                        <div class="flex flex-col items-center gap-4 opacity-40">
                            <span class="material-symbols-outlined text-5xl">event_busy</span>
                            <p class="font-bold text-sm">No attendance records.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if($logs->hasPages())
            <div class="p-6 bg-base-200/30 border-t border-base-200">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
