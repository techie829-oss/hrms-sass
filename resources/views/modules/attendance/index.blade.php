<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface">Attendance Logs</h2>
                <p class="text-sm text-on-surface-variant font-medium mt-1">Track and manage employee time and presence.</p>
            </div>
            <div class="flex gap-3">
                <button class="btn btn-ghost border-outline-variant/20 rounded-xl font-bold text-xs uppercase tracking-widest px-6">
                    <span class="material-symbols-outlined text-lg">filter_list</span> Filters
                </button>
                <a href="{{ route('attendance.create') }}" class="btn btn-primary primary-gradient border-none rounded-xl font-bold text-xs uppercase tracking-widest px-6 shadow-lg">
                    <span class="material-symbols-outlined text-lg">add_task</span> Manual Log
                </a>
            </div>
        </div>
    </x-slot>

    <div class="bg-surface-container-lowest rounded-[2.5rem] border border-outline-variant/15 premium-shadow overflow-hidden">
        <div class="p-6 border-b border-outline-variant/10 bg-surface-container-low/30">
            <div class="relative max-w-md">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">search</span>
                <input type="text" placeholder="Search by employee name or ID..." class="w-full pl-12 pr-4 py-3 bg-surface-container-lowest border border-outline-variant/20 rounded-2xl text-sm focus:border-primary focus:ring-0 transition-all font-medium">
            </div>
        </div>

        <x-table :headers="['Employee', 'Date', 'Check In', 'Check Out', 'Worked Hours', 'Status', 'Actions']" :striped="false">
            @forelse($logs as $log)
                <tr class="hover:bg-primary/5 transition-colors border-b border-outline-variant/5">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-4">
                            <div class="avatar placeholder">
                                <div class="bg-primary/10 text-primary rounded-2xl w-10 h-10 font-black text-xs premium-shadow border border-primary/5">
                                    {{ strtoupper(substr($log->employee->first_name, 0, 1) . substr($log->employee->last_name, 0, 1)) }}
                                </div>
                            </div>
                            <div>
                                <div class="font-bold text-on-surface text-sm">{{ $log->employee->full_name }}</div>
                                <div class="text-[10px] text-on-surface-variant font-bold uppercase tracking-tighter opacity-70">{{ $log->employee->employee_id }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="text-sm font-bold text-on-surface">{{ $log->date->format('M d, Y') }}</div>
                    </td>
                    <td>
                        <div class="text-sm font-medium text-success">{{ $log->check_in ? $log->check_in->format('H:i') : '--:--' }}</div>
                    </td>
                    <td>
                        <div class="text-sm font-medium text-error">{{ $log->check_out ? $log->check_out->format('H:i') : '--:--' }}</div>
                    </td>
                    <td>
                        <div class="text-sm font-bold text-on-surface">{{ $log->worked_hours ?? '0.00' }} hrs</div>
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
                        <span class="badge {{ $statusBadge }} text-white font-black text-[9px] px-3 py-2 uppercase tracking-widest">{{ str_replace('_', ' ', $log->status) }}</span>
                    </td>
                    <td class="text-right px-6">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('attendance.show', $log->id) }}" class="btn btn-ghost btn-sm btn-square rounded-xl hover:bg-primary/10 hover:text-primary">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-20 text-center">
                        <div class="flex flex-col items-center gap-4 opacity-40">
                            <span class="material-symbols-outlined text-6xl">event_busy</span>
                            <p class="font-headline font-bold text-lg">No attendance logs found for this period.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if($logs->hasPages())
            <div class="p-6 bg-surface-container-low/30 border-t border-outline-variant/10">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
