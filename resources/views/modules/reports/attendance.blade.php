<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('reports.index') }}" class="btn btn-ghost btn-xs btn-circle">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                </a>
                <h2 class="text-xl font-bold text-on-surface">Attendance Analytics</h2>
            </div>
            <div class="flex gap-2 items-center">
                <form method="GET" class="flex items-center gap-2">
                    <select name="month" class="select select-xs select-bordered rounded-lg text-[10px] font-bold" onchange="this.form.submit()">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endfor
                    </select>
                    <select name="year" class="select select-xs select-bordered rounded-lg text-[10px] font-bold" onchange="this.form.submit()">
                        @for($y = now()->year; $y >= now()->year - 3; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </form>
                <button onclick="window.print()" class="btn btn-sm btn-ghost border-outline-variant/20 rounded-lg font-bold text-[10px] uppercase tracking-wider gap-2">
                    <span class="material-symbols-outlined text-sm">print</span> Print
                </button>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- Stat Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-surface-container-lowest border border-outline-variant/10 p-5 rounded-xl shadow-sm">
                <div class="text-[9px] font-bold uppercase tracking-widest text-on-surface-variant opacity-60 mb-2">Working Days</div>
                <div class="text-3xl font-black text-on-surface">{{ $workingDaysInMonth }}</div>
                <div class="mt-2 flex items-center gap-1">
                    <span class="text-[9px] font-bold text-on-surface-variant uppercase opacity-40">{{ date('F', mktime(0,0,0,$month,1)) }} {{ $year }}</span>
                </div>
            </div>
            <div class="bg-surface-container-lowest border border-outline-variant/10 p-5 rounded-xl shadow-sm">
                <div class="text-[9px] font-bold uppercase tracking-widest text-on-surface-variant opacity-60 mb-2">Present Records</div>
                <div class="text-3xl font-black text-success">{{ $presentCount }}</div>
                <div class="mt-2 flex items-center gap-1">
                    <div class="w-1 h-1 rounded-full bg-success"></div>
                    <span class="text-[9px] font-bold text-success uppercase">{{ $totalLogs > 0 ? round(($presentCount / $totalLogs) * 100) : 0 }}% Attendance Rate</span>
                </div>
            </div>
            <div class="bg-surface-container-lowest border border-outline-variant/10 p-5 rounded-xl shadow-sm">
                <div class="text-[9px] font-bold uppercase tracking-widest text-on-surface-variant opacity-60 mb-2">Absent / Late</div>
                <div class="text-3xl font-black text-error">{{ $absentCount }}<span class="text-lg text-warning ml-1">/{{ $lateCount }}</span></div>
                <div class="mt-2 flex items-center gap-1">
                    <div class="w-1 h-1 rounded-full bg-error"></div>
                    <span class="text-[9px] font-bold text-error uppercase">Absent</span>
                    <div class="w-1 h-1 rounded-full bg-warning ml-1"></div>
                    <span class="text-[9px] font-bold text-warning uppercase">Late</span>
                </div>
            </div>
            <div class="bg-surface-container-lowest border border-outline-variant/10 p-5 rounded-xl shadow-sm">
                <div class="text-[9px] font-bold uppercase tracking-widest text-on-surface-variant opacity-60 mb-2">Avg Hours / Day</div>
                <div class="text-3xl font-black text-primary">{{ $avgWorkedHours }}<span class="text-xs opacity-40 ml-1">Hrs</span></div>
                <div class="mt-2 flex items-center gap-1">
                    <div class="w-1 h-1 rounded-full bg-primary"></div>
                    <span class="text-[9px] font-bold text-primary uppercase">Per Present Day</span>
                </div>
            </div>
            <div class="bg-surface-container-lowest border border-outline-variant/10 p-5 rounded-xl shadow-sm">
                <div class="text-[9px] font-bold uppercase tracking-widest text-on-surface-variant opacity-60 mb-2">Overtime / Late</div>
                <div class="text-3xl font-black text-secondary">{{ round($totalOvertimeMinutes / 60, 1) }}<span class="text-xs opacity-40 ml-1">Hrs</span></div>
                <div class="mt-2 flex items-center gap-1">
                    <span class="text-[9px] font-bold text-on-surface-variant uppercase opacity-40">{{ round($totalLateMinutes / 60, 1) }}h Late Total</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Employee Attendance Table --}}
            <div class="lg:col-span-2 bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-outline-variant/5 bg-surface-container-low/20 flex items-center justify-between">
                    <h3 class="font-bold text-[10px] uppercase tracking-wider text-on-surface opacity-80 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">group</span> Employee Attendance Breakdown
                    </h3>
                    <span class="text-[8px] font-bold text-on-surface-variant opacity-40 uppercase">{{ $employeeAttendance->count() }} Employees</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-xs w-full">
                        <thead>
                            <tr class="bg-surface-container-low/30">
                                <th class="text-[8px] font-black uppercase tracking-widest opacity-60">Employee</th>
                                <th class="text-[8px] font-black uppercase tracking-widest opacity-60">Dept</th>
                                <th class="text-[8px] font-black uppercase tracking-widest opacity-60 text-center">Present</th>
                                <th class="text-[8px] font-black uppercase tracking-widest opacity-60 text-center">Absent</th>
                                <th class="text-[8px] font-black uppercase tracking-widest opacity-60 text-center">Late</th>
                                <th class="text-[8px] font-black uppercase tracking-widest opacity-60 text-center">Hours</th>
                                <th class="text-[8px] font-black uppercase tracking-widest opacity-60 text-center">OT (Min)</th>
                                <th class="text-[8px] font-black uppercase tracking-widest opacity-60 text-center">Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employeeAttendance as $emp)
                                @php
                                    $score = $workingDaysInMonth > 0 ? round(($emp->present_days / $workingDaysInMonth) * 100) : 0;
                                    $scoreColor = $score >= 90 ? 'text-success' : ($score >= 70 ? 'text-warning' : 'text-error');
                                @endphp
                                <tr class="hover:bg-surface-container-low/20 transition-colors">
                                    <td class="text-[10px] font-bold text-on-surface">{{ $emp->full_name }}</td>
                                    <td class="text-[10px] font-medium text-on-surface-variant opacity-60">{{ $emp->department?->name ?? '—' }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-success badge-xs text-[8px] font-black px-2 py-0.5 h-auto text-white">{{ $emp->present_days }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-error badge-xs text-[8px] font-black px-2 py-0.5 h-auto text-white {{ $emp->absent_days > 0 ? '' : 'opacity-30' }}">{{ $emp->absent_days }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-warning badge-xs text-[8px] font-black px-2 py-0.5 h-auto {{ $emp->late_days > 0 ? '' : 'opacity-30' }}">{{ $emp->late_days }}</span>
                                    </td>
                                    <td class="text-center text-[10px] font-bold text-on-surface">{{ number_format($emp->total_hours ?? 0, 1) }}</td>
                                    <td class="text-center text-[10px] font-bold text-secondary">{{ $emp->overtime_mins ?? 0 }}</td>
                                    <td class="text-center">
                                        <span class="text-[10px] font-black {{ $scoreColor }}">{{ $score }}%</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-8 text-[10px] font-bold opacity-40 italic uppercase">No attendance data for this period</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Sidebar: Department Stats + Leaves --}}
            <div class="space-y-6">
                {{-- Department Punctuality --}}
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-outline-variant/5 bg-surface-container-low/20">
                        <h3 class="font-bold text-[10px] uppercase tracking-wider text-on-surface opacity-80 flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">hub</span> Dept Punctuality
                        </h3>
                    </div>
                    <div class="p-5 space-y-4">
                        @foreach($departmentStats as $dept)
                            @php
                                $totalEntries = $dept->dept_present + $dept->dept_late;
                                $punctualityRate = $totalEntries > 0 ? round((($dept->dept_present - $dept->dept_late) / $totalEntries) * 100) : 0;
                                $punctualityRate = max(0, $punctualityRate);
                            @endphp
                            <div>
                                <div class="flex justify-between items-center mb-1.5 px-0.5">
                                    <span class="font-bold text-[10px] text-on-surface">{{ $dept->name }}</span>
                                    <span class="text-[9px] font-black opacity-50">{{ $dept->dept_present }}P / {{ $dept->dept_late }}L</span>
                                </div>
                                <div class="w-full bg-surface-container-low rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-primary h-full rounded-full transition-all duration-1000" style="width: {{ min($punctualityRate, 100) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Quick Stats --}}
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-outline-variant/5 bg-surface-container-low/20">
                        <h3 class="font-bold text-[10px] uppercase tracking-wider text-on-surface opacity-80 flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">insights</span> Quick Insights
                        </h3>
                    </div>
                    <div class="p-5 space-y-3">
                        <div class="flex justify-between items-center bg-surface-container-low px-4 py-2.5 rounded-lg border border-outline-variant/5">
                            <span class="text-[9px] font-bold opacity-40 uppercase">Total Logs</span>
                            <span class="text-xs font-black text-on-surface">{{ number_format($totalLogs) }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-surface-container-low px-4 py-2.5 rounded-lg border border-outline-variant/5">
                            <span class="text-[9px] font-bold opacity-40 uppercase">Approved Leaves</span>
                            <span class="text-xs font-black text-info">{{ $leaveCount }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-surface-container-low px-4 py-2.5 rounded-lg border border-outline-variant/5">
                            <span class="text-[9px] font-bold opacity-40 uppercase">Total Overtime</span>
                            <span class="text-xs font-black text-secondary">{{ round($totalOvertimeMinutes / 60, 1) }}h</span>
                        </div>
                        <div class="flex justify-between items-center bg-surface-container-low px-4 py-2.5 rounded-lg border border-outline-variant/5">
                            <span class="text-[9px] font-bold opacity-40 uppercase">Total Late Mins</span>
                            <span class="text-xs font-black text-warning">{{ number_format($totalLateMinutes) }} min</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-between p-4 bg-secondary/5 rounded-xl border border-secondary/10">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-secondary text-lg">info</span>
                <p class="text-[10px] font-bold text-secondary uppercase tracking-wider italic">Report generated {{ now()->format('M d, Y H:i') }} — {{ date('F', mktime(0,0,0,$month,1)) }} {{ $year }} period</p>
            </div>
        </div>
    </div>
</x-app-layout>
