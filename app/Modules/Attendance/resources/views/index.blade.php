<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-on-surface tracking-tight">Attendance Logs</h2>
                <p class="text-xs font-medium mt-0.5 text-on-surface-variant">Track and manage employee daily presence.</p>
            </div>
            <div class="flex items-center gap-3">
                @php
                    $showCalendarToggle = !$canViewAll || isset($filters['employee_id']) || request('search');
                @endphp

                @if($showCalendarToggle)
                <div class="inline-flex p-0.5 bg-slate-100 rounded-xl border border-slate-200">
                    <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}" class="rounded-lg px-3 py-1 text-[11px] font-semibold transition-all {{ $view == 'list' ? 'bg-white text-primary shadow-sm border border-slate-200/60' : 'bg-transparent text-slate-500 hover:text-slate-800' }}">
                        List
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['view' => 'calendar']) }}" class="rounded-lg px-3 py-1 text-[11px] font-semibold transition-all {{ $view == 'calendar' ? 'bg-white text-primary shadow-sm border border-slate-200/60' : 'bg-transparent text-slate-500 hover:text-slate-800' }}">
                        Calendar
                    </a>
                </div>
                @endif
                @can('manage_attendance')
                <div class="flex gap-2">
                    <a href="{{ route('attendance.settings') }}" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-3 shadow-sm flex items-center justify-center">
                        <span class="material-symbols-outlined text-base">settings</span>
                    </a>
                    <a href="{{ route('attendance.create') }}" class="btn btn-primary btn-sm rounded-xl px-4 shadow-sm shadow-primary/20 text-white font-semibold text-xs flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-sm">add</span> Manual Log
                    </a>
                </div>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="flex flex-col gap-6">
        <!-- Filters Area -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-visible">
            <div class="p-3.5 flex flex-wrap items-center justify-between gap-4">
                <form action="{{ route('attendance.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <input type="hidden" name="view" value="{{ $view }}">
                    
                    @can('view_all_attendance')
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search employee..." class="w-full max-w-[200px] pl-8 pr-3 py-1.5 text-xs border border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary/20 rounded-xl bg-white text-slate-900 placeholder-slate-400 transition-all shadow-sm">
                    </div>
                    @endcan

                    <div class="flex items-center gap-2">
                        <input type="month" name="month" value="{{ request('month', date('Y-m')) }}" class="px-3 py-1.5 text-xs border border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary/20 rounded-xl bg-white text-slate-900 transition-all shadow-sm">
                        <button type="submit" class="btn btn-primary btn-xs rounded-xl px-4 py-1.5 h-auto text-white font-semibold text-xs shadow-sm shadow-primary/10">Filter</button>
                        @if(request()->hasAny(['search', 'month', 'date']))
                        <a href="{{ route('attendance.index', ['view' => $view]) }}" class="btn btn-ghost btn-xs rounded-xl text-rose-600 hover:bg-rose-50 px-2 py-1.5 h-auto flex items-center justify-center">
                            <span class="material-symbols-outlined text-xs">close</span>
                        </a>
                        @endif
                    </div>
                </form>

                <div class="flex items-center gap-4 text-[10px] font-semibold text-slate-500">
                    <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500 ring-4 ring-emerald-50"></span> Present</div>
                    <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-500 ring-4 ring-amber-50"></span> Late</div>
                    <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-rose-500 ring-4 ring-rose-50"></span> Absent</div>
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
                        <div class="flex items-center gap-4 px-1">
                            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-primary to-primary/80 text-white flex flex-col items-center justify-center shadow-sm shadow-primary/20">
                                <span class="text-[9px] font-bold uppercase tracking-wider opacity-90 leading-none">{{ \Carbon\Carbon::parse($date)->format('M') }}</span>
                                <span class="text-lg font-extrabold leading-none mt-1.5">{{ \Carbon\Carbon::parse($date)->format('d') }}</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-sm text-slate-800 tracking-tight leading-none">{{ \Carbon\Carbon::parse($date)->format('l') }}</h3>
                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest mt-1.5">{{ \Carbon\Carbon::parse($date)->format('jS M, Y') }}</p>
                            </div>
                            <div class="h-px flex-1 bg-gradient-to-r from-slate-200 to-transparent ml-4"></div>
                            <div class="px-3 py-1 rounded-xl bg-slate-50 border border-slate-200/60 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                {{ count($dateLogs->groupBy('employee_id')) }} Employees
                            </div>
                        </div>

                        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                            <div class="table-crm">
                                <x-table :headers="['Employee', 'Entries', 'First In', 'Last Out', 'Status', 'Total Work Hours', 'Actions']" :striped="false">
                                    @foreach($dateLogs->groupBy('employee_id') as $employeeId => $empLogs)
                                        @php
                                            $firstLog = $empLogs->sortBy('check_in')->first();
                                            $lastLog = $empLogs->sortByDesc('check_out')->first();
                                            $employee = $firstLog->employee;
                                            $entryCount = count($empLogs);
                                            
                                            // Find summary for this employee on this date
                                            $summary = $summaries->where('date', $date)->where('employee_id', $employeeId)->first();
                                            $totalWorkedHours = $summary ? $summary->total_worked_hours : $empLogs->sum('worked_hours');
                                        @endphp
                                        <tr class="hover:bg-slate-50/50 transition-all border-b border-slate-100 last:border-0 group">
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="avatar {{ !$employee->profile_photo ? 'placeholder' : '' }}">
                                                        <div class="bg-slate-100 text-slate-700 rounded-xl w-9 h-9 font-bold text-xs flex items-center justify-center border border-slate-200/60 shadow-sm group-hover:scale-105 transition-transform overflow-hidden">
                                                            @if($employee->profile_photo)
                                                                <img src="{{ asset('storage/' . $employee->profile_photo) }}" alt="" class="w-full h-full object-cover">
                                                            @else
                                                                @php
                                                                    $nameParts = explode(' ', $employee->full_name ?? 'U');
                                                                    $initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
                                                                @endphp
                                                                {{ $initials }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="font-bold text-xs text-slate-700">{{ $employee->full_name }}</div>
                                                        <div class="text-[9px] font-semibold text-slate-400 uppercase tracking-wider mt-0.5">{{ $employee->employee_id }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="flex flex-col gap-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200/40">
                                                        {{ $entryCount }} {{ $entryCount > 1 ? 'Entries' : 'Entry' }}
                                                    </span>
                                                    @if($summary && count($summary->tags ?? []) > 1)
                                                        <span class="text-[8px] font-bold text-sky-600 uppercase tracking-wider">Multi-Session</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="flex flex-col">
                                                    <span class="text-xs font-bold text-slate-700">
                                                        {{ $firstLog->check_in ? $firstLog->check_in->format('h:i A') : '--:--' }}
                                                    </span>
                                                    @if($summary && in_array('late_arrived', $summary->tags ?? []))
                                                        <span class="text-[9px] font-bold text-amber-600 uppercase leading-none mt-0.5">Late</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="flex flex-col">
                                                    <span class="text-xs font-bold text-slate-700">
                                                        {{ $lastLog->check_out ? $lastLog->check_out->format('h:i A') : '--:--' }}
                                                    </span>
                                                    @if($summary && in_array('checkout_missing', $summary->tags ?? []))
                                                        <span class="text-[9px] font-bold text-rose-500 uppercase leading-none mt-0.5">Missing Out</span>
                                                    @elseif($summary && in_array('early_leave', $summary->tags ?? []))
                                                        <span class="text-[9px] font-bold text-orange-500 uppercase leading-none mt-0.5">Early Leave</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="flex flex-wrap gap-1">
                                                    @php
                                                        $typeMap = [
                                                            'full_day'    => ['l' => 'Full Day', 'c' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                                                            'half_day'    => ['l' => 'Half Day', 'c' => 'bg-blue-50 text-blue-700 border-blue-200'],
                                                            'quarter_day' => ['l' => 'Short Day', 'c' => 'bg-amber-50 text-amber-700 border-amber-200'],
                                                            'absent'      => ['l' => 'Absent', 'c' => 'bg-rose-50 text-rose-700 border-rose-200'],
                                                        ];
                                                        $t = $summary ? ($typeMap[$summary->day_type] ?? null) : null;
                                                    @endphp
                                                    @if($t)
                                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider border {{ $t['c'] }}">
                                                            {{ $t['l'] }}
                                                        </span>
                                                    @else
                                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider border bg-slate-50 text-slate-500 border-slate-200">
                                                            Pending
                                                        </span>
                                                    @endif
                                                    
                                                    @if($summary && in_array('overtime', $summary->tags ?? []))
                                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider border bg-indigo-50 text-indigo-700 border-indigo-200">
                                                            Overtime
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="flex flex-col gap-1 w-24">
                                                    <div class="flex items-center justify-between text-[10px]">
                                                        <span class="font-bold text-slate-700">{{ $summary ? $summary->formatted_hours : number_format($totalWorkedHours, 1) . 'h' }}</span>
                                                        <span class="font-semibold text-slate-400">{{ min(round(($totalWorkedHours / 8) * 100), 100) }}%</span>
                                                    </div>
                                                    <div class="w-full bg-slate-100 rounded-full h-1">
                                                        <div class="bg-primary h-1 rounded-full" style="width: {{ min(($totalWorkedHours / 8) * 100, 100) }}%"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4 text-right">
                                                <div class="flex justify-end gap-1">
                                                    <a href="{{ route('attendance.show', $firstLog->id) }}" class="p-1 text-slate-400 hover:text-primary transition-all">
                                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                                    </a>
                                                    @if($entryCount > 1)
                                                    <div class="dropdown dropdown-end">
                                                        <label tabindex="0" class="p-1 text-slate-400 hover:text-slate-600 cursor-pointer transition-all">
                                                            <span class="material-symbols-outlined text-lg">more_vert</span>
                                                        </label>
                                                        <ul tabindex="0" class="dropdown-content z-[1] menu p-1.5 shadow-lg bg-white rounded-xl border border-slate-200 w-44 mt-1">
                                                            <li class="menu-title text-[9px] font-bold uppercase tracking-wider text-slate-400 px-2 py-1">Daily Entries</li>
                                                            @foreach($empLogs as $elog)
                                                                <li>
                                                                    <a class="text-[10px] font-semibold text-slate-600 hover:bg-slate-50 rounded-lg py-1.5 px-2 flex justify-between">
                                                                        <span>{{ $elog->check_in?->format('h:i A') }} - {{ $elog->check_out?->format('h:i A') ?? '...' }}</span>
                                                                        <span class="text-slate-400 font-bold">{{ $elog->worked_hours }}h</span>
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
                    </div>
                @empty
                    <div class="bg-white border border-slate-200 border-dashed rounded-xl p-20 flex flex-col items-center justify-center text-slate-400 shadow-sm">
                        <div class="w-16 h-16 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center mb-4 text-slate-400">
                            <span class="material-symbols-outlined text-3xl">calendar_today</span>
                        </div>
                        <h4 class="font-bold text-xs uppercase tracking-wider">No Records Found</h4>
                        <p class="text-[10px] font-semibold mt-1">Try adjusting your search or date filters</p>
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
