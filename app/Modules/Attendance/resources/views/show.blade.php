<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('attendance.index') }}" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-3 shadow-sm flex items-center justify-center">
                <span class="material-symbols-outlined text-base">arrow_back</span>
            </a>
            <div>
                <h2 class="text-xl font-bold text-on-surface tracking-tight">Daily Attendance Details</h2>
                <p class="text-xs font-semibold text-slate-400 mt-0.5 uppercase tracking-wider">
                    {{ $log->employee->full_name }} &mdash; {{ $log->date->format('l, F d, Y') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Summary Stats Card -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Day Summary</h3>
                </div>
                <div class="p-6 grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">First In</p>
                        <p class="text-xl font-extrabold text-emerald-600">{{ $allLogs->first()->check_in ? $allLogs->first()->check_in->format('h:i A') : '--:--' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Last Out</p>
                        <p class="text-xl font-extrabold text-rose-500">{{ $allLogs->last()->check_out ? $allLogs->last()->check_out->format('h:i A') : '--:--' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Total Hours</p>
                        <p class="text-xl font-extrabold text-primary">{{ number_format($allLogs->sum('worked_hours'), 2) }}h</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Entries</p>
                        <p class="text-xl font-extrabold text-slate-600">{{ count($allLogs) }}</p>
                    </div>
                </div>
            </div>

            <!-- Detailed Entries Timeline -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Detailed Timeline</h3>
                    <span class="px-2.5 py-0.5 rounded-full bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-wider">All Clock Logs</span>
                </div>
                <div class="p-0">
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="text-[10px] font-bold uppercase tracking-wider text-slate-500 py-3 pl-6">#</th>
                                    <th class="text-[10px] font-bold uppercase tracking-wider text-slate-500 py-3">In Time</th>
                                    <th class="text-[10px] font-bold uppercase tracking-wider text-slate-500 py-3">Out Time</th>
                                    <th class="text-[10px] font-bold uppercase tracking-wider text-slate-500 py-3">Duration</th>
                                    <th class="text-[10px] font-bold uppercase tracking-wider text-slate-500 py-3">Status</th>
                                    <th class="text-[10px] font-bold uppercase tracking-wider text-slate-500 py-3 text-right pr-6">IP / Location</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($allLogs as $index => $entry)
                                <tr class="hover:bg-slate-50/55 transition-colors group">
                                    <td class="py-3 pl-6">
                                        <span class="w-6 h-6 rounded-lg bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-600 group-hover:bg-primary group-hover:text-white transition-all">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="py-3">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-slate-700">{{ $entry->check_in ? $entry->check_in->format('h:i A') : '--:--' }}</span>
                                            @if($entry->is_late)
                                                <span class="text-[8px] font-bold text-amber-600 uppercase mt-0.5">Late Entry</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="text-xs font-bold text-slate-700">{{ $entry->check_out ? $entry->check_out->format('h:i A') : '--:--' }}</span>
                                    </td>
                                    <td class="py-3">
                                        <span class="text-xs font-bold text-primary">{{ $entry->worked_hours ?? '0.00' }}h</span>
                                    </td>
                                    <td class="py-3">
                                        @php
                                            $statusClasses = [
                                                'present' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                                                'absent' => 'bg-rose-50 text-rose-700 border-rose-200/60',
                                                'half_day' => 'bg-amber-50 text-amber-700 border-amber-200/60',
                                                'late' => 'bg-blue-50 text-blue-700 border-blue-200/60',
                                            ];
                                            $statusClass = $statusClasses[$entry->status] ?? 'bg-slate-50 text-slate-500 border-slate-200';
                                        @endphp
                                        <span class="px-2 py-0.5 rounded-lg border {{ $statusClass }} font-bold text-[8px] uppercase tracking-wider">
                                            {{ str_replace('_', ' ', $entry->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right pr-6">
                                        <div class="flex flex-col items-end text-slate-400 group-hover:text-slate-600 transition-colors">
                                            <span class="text-[9px] font-bold">{{ $entry->check_in_ip ?? 'No IP' }}</span>
                                            @if($entry->check_in_lat && $entry->check_in_lng)
                                                <span class="text-[8px] font-bold uppercase tracking-wider mt-0.5">Lat: {{ $entry->check_in_lat }}</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Employee Card -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="p-6 flex flex-col items-center text-center">
                    <div class="avatar placeholder mb-4">
                        <div class="bg-slate-100 text-slate-700 rounded-2xl w-16 h-16 font-extrabold text-lg border border-slate-200/60 shadow-sm flex items-center justify-center">
                            {{ strtoupper(substr($log->employee->first_name, 0, 1) . substr($log->employee->last_name, 0, 1)) }}
                        </div>
                    </div>
                    <h3 class="text-sm font-bold text-slate-700 tracking-tight leading-none">{{ $log->employee->full_name }}</h3>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 mt-1.5">{{ $log->employee->employee_id }}</p>
                    <div class="w-full h-px bg-slate-100 my-4"></div>
                    <a href="{{ route('hr.employees.show', $log->employee->id) }}" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-4 py-1.5 h-auto text-xs font-semibold w-full block text-center shadow-sm">View Employee Profile</a>
                </div>
            </div>

            <!-- Meta Info -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden p-6 space-y-4">
                <div>
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Shift Details</h4>
                    <p class="text-xs font-bold text-slate-700">Regular Office Shift (SHFT-01)</p>
                    <p class="text-[10px] font-semibold text-slate-400 mt-0.5 uppercase">09:00 AM - 06:00 PM</p>
                </div>
                <div class="w-full h-px bg-slate-100"></div>
                <div>
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Policy Info</h4>
                    <p class="text-xs font-bold text-slate-700">Standard Attendance Policy</p>
                    <div class="flex gap-2 mt-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
