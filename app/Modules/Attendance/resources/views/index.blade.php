<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-base-content/90 tracking-tight">Attendance Logs</h2>
                <p class="text-xs font-medium mt-0.5 opacity-50">Track and manage employee daily presence.</p>
            </div>
            <div class="flex items-center gap-3">
                @php
                    $showCalendarToggle = !$canViewAll || isset($filters['employee_id']) || request('search');
                @endphp

                @if($showCalendarToggle)
                <div class="join bg-base-200/50 p-1 rounded-2xl border border-base-300/50 shadow-sm">
                    <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}" class="join-item btn btn-sm {{ $view == 'list' ? 'btn-primary shadow-lg border-primary' : 'btn-ghost border-transparent' }} gap-2 px-4 transition-all rounded-xl">
                        <span class="material-symbols-outlined text-sm">view_list</span> List
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['view' => 'calendar']) }}" class="join-item btn btn-sm {{ $view == 'calendar' ? 'btn-primary shadow-lg border-primary' : 'btn-ghost border-transparent' }} gap-2 px-4 transition-all rounded-xl">
                        <span class="material-symbols-outlined text-sm">calendar_view_month</span> Calendar
                    </a>
                </div>
                @endif
                @can('manage_attendance')
                <div class="flex gap-2">
                    <a href="{{ route('attendance.settings') }}" class="btn btn-ghost btn-sm btn-outline border-base-300 rounded-xl px-3">
                        <span class="material-symbols-outlined text-base">settings</span>
                    </a>
                    <a href="{{ route('attendance.create') }}" class="btn btn-primary btn-sm rounded-xl px-4 shadow-lg shadow-primary/20">
                        <span class="material-symbols-outlined text-base">add</span> Manual Log
                    </a>
                </div>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="flex flex-col gap-6">
        <!-- Filters Area -->
        <div class="card bg-base-100 shadow-sm border border-base-200/60 rounded-3xl overflow-visible">
            <div class="p-4 flex flex-wrap items-center justify-between gap-4">
                <form action="{{ route('attendance.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <input type="hidden" name="view" value="{{ $view }}">
                    
                    @can('view_all_attendance')
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 opacity-40 text-lg group-focus-within:text-primary transition-colors">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search employee..." class="input input-bordered input-sm w-full max-w-[200px] pl-10 pr-10 py-2 text-xs rounded-2xl focus:border-primary transition-all">
                    </div>
                    @endcan

                    <div class="flex items-center gap-2">
                        <input type="month" name="month" value="{{ request('month', date('Y-m')) }}" class="input input-bordered input-sm text-xs rounded-2xl focus:border-primary transition-all">
                        <button type="submit" class="btn btn-primary btn-sm rounded-2xl px-5 font-bold shadow-md shadow-primary/10">Filter</button>
                        @if(request()->hasAny(['search', 'month', 'date']))
                        <a href="{{ route('attendance.index', ['view' => $view]) }}" class="btn btn-ghost btn-sm rounded-2xl text-error/70 hover:bg-error/10 px-3">
                            <span class="material-symbols-outlined text-base">close_small</span>
                        </a>
                        @endif
                    </div>
                </form>

                <div class="flex items-center gap-4 text-[9px] font-black uppercase tracking-[0.15em] opacity-40">
                    <div class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-success ring-4 ring-success/10"></span> Present</div>
                    <div class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-warning ring-4 ring-warning/10"></span> Late</div>
                    <div class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-error ring-4 ring-error/10"></span> Absent</div>
                </div>
            </div>
        </div>

        @if($view == 'calendar')
            @include('attendance::partials.calendar_view')
        @else
            <!-- Date-wise Separated List View -->
            <div class="flex flex-col gap-10">
                @forelse($logs->groupBy(fn($l) => $l->date->format('Y-m-d')) as $date => $dateLogs)
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center gap-4 px-4">
                            <div class="h-12 w-12 rounded-[22px] bg-gradient-to-br from-primary to-primary-focus text-white flex flex-col items-center justify-center shadow-xl shadow-primary/20 border border-white/20">
                                <span class="text-[9px] font-black uppercase tracking-tighter opacity-80 leading-none">{{ \Carbon\Carbon::parse($date)->format('M') }}</span>
                                <span class="text-xl font-black leading-none mt-1">{{ \Carbon\Carbon::parse($date)->format('d') }}</span>
                            </div>
                            <div>
                                <h3 class="font-black text-base text-base-content/90 tracking-tight leading-none">{{ \Carbon\Carbon::parse($date)->format('l') }}</h3>
                                <p class="text-[10px] font-black uppercase tracking-widest opacity-40 mt-1.5">{{ \Carbon\Carbon::parse($date)->format('jS M, Y') }}</p>
                            </div>
                            <div class="h-px flex-1 bg-gradient-to-r from-base-300/60 to-transparent ml-4"></div>
                            <div class="px-4 py-1.5 rounded-2xl bg-base-200/50 border border-base-300/30 text-[10px] font-black uppercase tracking-widest opacity-60">
                                {{ count($dateLogs->groupBy('employee_id')) }} Employees
                            </div>
                        </div>

                        <div class="card bg-base-100 shadow-xl shadow-base-content/5 border border-base-200/60 rounded-[32px] overflow-hidden">
                            <x-table :headers="['Employee', 'Entries', 'Status', 'Total Work Hours', 'Actions']" :striped="false">
                                @foreach($dateLogs->groupBy('employee_id') as $employeeId => $empLogs)
                                    @php
                                        $firstLog = $empLogs->sortBy('check_in')->first();
                                        $lastLog = $empLogs->sortByDesc('check_out')->first();
                                        $totalWorkedHours = $empLogs->sum('worked_hours');
                                        $employee = $firstLog->employee;
                                        $entryCount = count($empLogs);
                                    @endphp
                                    <tr class="hover:bg-base-200/30 transition-all border-b border-base-200 last:border-0 group">
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-4">
                                                <div class="avatar placeholder">
                                                    <div class="bg-gradient-to-tr from-primary/10 to-primary/5 text-primary rounded-[18px] w-11 h-11 font-black text-xs border border-primary/10 shadow-sm group-hover:scale-105 transition-transform">
                                                        {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="font-black text-sm text-base-content/80">{{ $employee->full_name }}</div>
                                                    <div class="text-[9px] font-black uppercase tracking-[0.15em] opacity-30 group-hover:opacity-60 transition-opacity">{{ $employee->employee_id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex flex-col gap-1">
                                                <span class="badge badge-sm rounded-lg font-black text-[9px] bg-base-200 border-base-300/50 text-base-content/60">
                                                    {{ $entryCount }} {{ $entryCount > 1 ? 'Entries' : 'Entry' }}
                                                </span>
                                                <span class="text-[8px] font-black opacity-30 uppercase tracking-tighter">
                                                    {{ $firstLog->check_in?->format('h:i A') }} - {{ $lastLog->check_out?->format('h:i A') ?? '...' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $status = $firstLog->status;
                                                $statusClasses = [
                                                    'present' => 'bg-success/10 text-success border-success/20 ring-success/5',
                                                    'absent' => 'bg-error/10 text-error border-error/20 ring-error/5',
                                                    'half_day' => 'bg-warning/10 text-warning border-warning/20 ring-warning/5',
                                                    'late' => 'bg-info/10 text-info border-info/20 ring-info/5',
                                                ];
                                                $statusClass = $statusClasses[$status] ?? 'bg-base-200 text-base-content/50 border-base-300';
                                            @endphp
                                            <span class="px-3 py-1.5 rounded-xl border {{ $statusClass }} font-black text-[9px] uppercase tracking-[0.1em] ring-4">
                                                {{ str_replace('_', ' ', $status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="flex items-center gap-6">
                                                <div class="flex flex-col">
                                                    <span class="text-lg font-black text-primary leading-none">{{ number_format($totalWorkedHours, 2) }}</span>
                                                    <span class="text-[9px] font-black opacity-30 uppercase tracking-widest mt-1">Hours</span>
                                                </div>
                                                <div class="flex flex-col flex-1 min-w-[100px] gap-1.5">
                                                    <div class="flex items-center justify-between px-1">
                                                        <span class="text-[9px] font-black opacity-30">Progress</span>
                                                        <span class="text-[9px] font-black opacity-30">{{ min(round(($totalWorkedHours / 8) * 100), 100) }}%</span>
                                                    </div>
                                                    <progress class="progress progress-primary w-full h-1.5 shadow-sm" value="{{ min(($totalWorkedHours / 8) * 100, 100) }}" max="100"></progress>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right px-6">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('attendance.show', $firstLog->id) }}" class="btn btn-ghost btn-sm btn-square rounded-2xl text-primary/60 hover:text-primary hover:bg-primary/10 transition-all">
                                                    <span class="material-symbols-outlined text-lg">visibility</span>
                                                </a>
                                                @if($entryCount > 1)
                                                <div class="dropdown dropdown-end">
                                                    <label tabindex="0" class="btn btn-ghost btn-sm btn-square rounded-2xl text-base-content/40 hover:text-base-content hover:bg-base-200 transition-all">
                                                        <span class="material-symbols-outlined text-lg">more_vert</span>
                                                    </label>
                                                    <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow-2xl bg-base-100 rounded-2xl border border-base-200 w-48 mt-2">
                                                        <li class="menu-title text-[9px] font-black uppercase opacity-40">Daily Entries</li>
                                                        @foreach($empLogs as $elog)
                                                            <li>
                                                                <a class="text-[10px] font-bold py-2">
                                                                    {{ $elog->check_in?->format('h:i A') }} - {{ $elog->check_out?->format('h:i A') ?? '...' }}
                                                                    <span class="ml-auto text-[9px] opacity-50">{{ $elog->worked_hours }}h</span>
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </x-table>
                        </div>
                    </div>
                @empty
                    <div class="card bg-base-100 border border-base-200 border-dashed rounded-[40px] p-24 flex flex-col items-center justify-center opacity-30 grayscale">
                        <div class="w-20 h-20 rounded-[32px] bg-base-200 flex items-center justify-center mb-6">
                            <span class="material-symbols-outlined text-4xl">calendar_today</span>
                        </div>
                        <h4 class="font-black text-sm uppercase tracking-[0.2em]">No Records Found</h4>
                        <p class="text-[10px] font-bold mt-2">Try adjusting your search or date filters</p>
                    </div>
                @endforelse
            </div>

            @if($logs->hasPages())
                <div class="flex justify-center mt-6">
                    {{ $logs->appends(request()->query())->links() }}
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
