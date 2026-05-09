<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">Attendance Logs</h2>
                <p class="text-xs font-medium mt-1 opacity-70">Track and manage employee daily presence.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="join bg-base-200 p-1 rounded-xl border border-base-300 shadow-sm">
                    <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}" class="join-item btn btn-sm {{ $view == 'list' ? 'btn-primary shadow-lg' : 'btn-ghost' }} gap-2 px-4 transition-all">
                        <span class="material-symbols-outlined text-sm">view_list</span> List
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['view' => 'calendar']) }}" class="join-item btn btn-sm {{ $view == 'calendar' ? 'btn-primary shadow-lg' : 'btn-ghost' }} gap-2 px-4 transition-all">
                        <span class="material-symbols-outlined text-sm">calendar_view_month</span> Calendar
                    </a>
                </div>
                @can('manage_attendance')
                <div class="flex gap-2">
                    <a href="{{ route('attendance.settings') }}" class="btn btn-ghost btn-sm btn-outline border-base-300 rounded-xl">
                        <span class="material-symbols-outlined text-base">settings</span>
                    </a>
                    <a href="{{ route('attendance.create') }}" class="btn btn-primary btn-sm rounded-xl">
                        <span class="material-symbols-outlined text-base">add</span> Manual Log
                    </a>
                </div>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="flex flex-col gap-6">
        <!-- Filters Area -->
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="p-4 flex flex-wrap items-center justify-between gap-4">
                <form action="{{ route('attendance.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <input type="hidden" name="view" value="{{ $view }}">
                    
                    @can('view_all_attendance')
                    <div class="relative max-w-xs">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 opacity-50 text-lg">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search employee..." class="input input-bordered input-sm w-full pl-10 pr-10 py-2 text-xs rounded-xl">
                    </div>
                    @endcan

                    <div class="flex items-center gap-2">
                        <input type="month" name="month" value="{{ request('month', date('Y-m')) }}" class="input input-bordered input-sm text-xs rounded-xl">
                        <button type="submit" class="btn btn-primary btn-sm rounded-xl px-4">Filter</button>
                        @if(request()->hasAny(['search', 'month', 'date']))
                        <a href="{{ route('attendance.index', ['view' => $view]) }}" class="btn btn-ghost btn-sm rounded-xl text-error">
                            <span class="material-symbols-outlined text-base">close</span>
                        </a>
                        @endif
                    </div>
                </form>

                <div class="flex items-center gap-4 text-[10px] font-bold uppercase tracking-widest opacity-60">
                    <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-success"></span> Present</div>
                    <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-warning"></span> Late</div>
                    <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-error"></span> Absent</div>
                </div>
            </div>
        </div>

        @if($view == 'calendar')
            @include('attendance::partials.calendar_view')
        @else
            <!-- Date-wise Separated List View -->
            <div class="flex flex-col gap-8">
                @php $currentDate = null; @endphp
                @forelse($logs->groupBy(fn($l) => $l->date->format('Y-m-d')) as $date => $dateLogs)
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-4 px-2">
                            <div class="h-10 w-10 rounded-2xl bg-primary/10 text-primary flex flex-col items-center justify-center border border-primary/10 shadow-sm">
                                <span class="text-[10px] font-black uppercase leading-none">{{ \Carbon\Carbon::parse($date)->format('M') }}</span>
                                <span class="text-lg font-black leading-none mt-0.5">{{ \Carbon\Carbon::parse($date)->format('d') }}</span>
                            </div>
                            <div>
                                <h3 class="font-black text-sm uppercase tracking-wider text-base-content/80">{{ \Carbon\Carbon::parse($date)->format('l') }}</h3>
                                <p class="text-[10px] font-bold opacity-50">{{ count($dateLogs) }} Records found</p>
                            </div>
                            <div class="h-px flex-1 bg-gradient-to-r from-base-content/10 to-transparent"></div>
                        </div>

                        <div class="card bg-base-100 shadow-sm border border-base-200 overflow-hidden">
                            <x-table :headers="['Employee', 'Shift Time', 'Clock In', 'Clock Out', 'Status', 'Work Hours', 'Actions']" :striped="false">
                                @foreach($dateLogs as $log)
                                    <tr class="hover:bg-base-200/40 transition-colors border-b border-base-200 last:border-0">
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-3">
                                                <div class="avatar placeholder">
                                                    <div class="bg-primary/10 text-primary rounded-xl w-9 h-9 font-black text-[10px] border border-primary/10">
                                                        {{ strtoupper(substr($log->employee->first_name, 0, 1) . substr($log->employee->last_name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="font-black text-xs">{{ $log->employee->full_name }}</div>
                                                    <div class="text-[9px] font-bold uppercase tracking-widest opacity-60">{{ $log->employee->employee_id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex flex-col">
                                                <span class="text-[10px] font-bold opacity-60">SHFT-01</span>
                                                <span class="text-[10px] font-black uppercase">09:00 - 18:00</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex flex-col">
                                                <span class="text-xs font-black {{ $log->check_in ? 'text-success' : 'text-base-content/20' }}">
                                                    {{ $log->check_in ? $log->check_in->format('H:i A') : '--:--' }}
                                                </span>
                                                @if($log->is_late)
                                                    <span class="text-[9px] font-black text-warning uppercase">Late ({{ $log->late_minutes }}m)</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-xs font-black {{ $log->check_out ? 'text-error' : 'text-base-content/20' }}">
                                                {{ $log->check_out ? $log->check_out->format('H:i A') : '--:--' }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $statusClasses = [
                                                    'present' => 'bg-success/10 text-success border-success/20',
                                                    'absent' => 'bg-error/10 text-error border-error/20',
                                                    'half_day' => 'bg-warning/10 text-warning border-warning/20',
                                                    'late' => 'bg-info/10 text-info border-info/20',
                                                ];
                                                $statusClass = $statusClasses[$log->status] ?? 'bg-base-200 text-base-content/50 border-base-300';
                                            @endphp
                                            <span class="px-2 py-1 rounded-lg border {{ $statusClass }} font-black text-[9px] uppercase tracking-widest">
                                                {{ str_replace('_', ' ', $log->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-black">{{ $log->worked_hours ?? '0.00' }}h</span>
                                                <progress class="progress progress-primary w-12 h-1" value="{{ min(($log->worked_hours ?? 0) * 10, 100) }}" max="100"></progress>
                                            </div>
                                        </td>
                                        <td class="text-right px-4">
                                            <div class="flex justify-end gap-1">
                                                <a href="{{ route('attendance.show', $log->id) }}" class="btn btn-ghost btn-xs btn-square rounded-lg text-primary hover:bg-primary/10">
                                                    <span class="material-symbols-outlined text-base">visibility</span>
                                                </a>
                                                @can('manage_attendance')
                                                <a href="{{ route('attendance.edit', $log->id) }}" class="btn btn-ghost btn-xs btn-square rounded-lg text-secondary hover:bg-secondary/10">
                                                    <span class="material-symbols-outlined text-base">edit</span>
                                                </a>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </x-table>
                        </div>
                    </div>
                @empty
                    <div class="card bg-base-100 border border-base-200 border-dashed p-20 flex flex-col items-center justify-center opacity-40 grayscale">
                        <span class="material-symbols-outlined text-6xl">event_busy</span>
                        <h4 class="font-black text-sm uppercase tracking-widest mt-4">No Attendance Records Found</h4>
                        <p class="text-xs font-bold">Try adjusting your filters or selection</p>
                    </div>
                @endforelse
            </div>

            @if($logs->hasPages())
                <div class="p-6">
                    {{ $logs->appends(request()->query())->links() }}
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
