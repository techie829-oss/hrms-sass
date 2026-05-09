<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('attendance.index') }}" class="btn btn-ghost btn-sm btn-square rounded-xl">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="text-xl font-black text-base-content/90 tracking-tight">Daily Attendance Details</h2>
                <p class="text-xs font-bold opacity-40 mt-1 uppercase tracking-widest">
                    {{ $log->employee->full_name }} &mdash; {{ $log->date->format('l, F d, Y') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Summary Stats Card -->
            <div class="card bg-base-100 shadow-xl shadow-base-content/5 border border-base-200/60 rounded-[32px] overflow-hidden">
                <div class="p-6 border-b border-base-200/50 bg-base-200/20">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] opacity-40">Day Summary</h3>
                </div>
                <div class="p-8 grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div>
                        <p class="text-[9px] font-black opacity-30 uppercase tracking-widest mb-2">First In</p>
                        <p class="text-2xl font-black text-success/80">{{ $allLogs->first()->check_in ? $allLogs->first()->check_in->format('h:i A') : '--:--' }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black opacity-30 uppercase tracking-widest mb-2">Last Out</p>
                        <p class="text-2xl font-black text-error/70">{{ $allLogs->last()->check_out ? $allLogs->last()->check_out->format('h:i A') : '--:--' }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black opacity-30 uppercase tracking-widest mb-2">Total Hours</p>
                        <p class="text-2xl font-black text-primary">{{ number_format($allLogs->sum('worked_hours'), 2) }}h</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black opacity-30 uppercase tracking-widest mb-2">Entries</p>
                        <p class="text-2xl font-black text-base-content/60">{{ count($allLogs) }}</p>
                    </div>
                </div>
            </div>

            <!-- Detailed Entries Timeline -->
            <div class="card bg-base-100 shadow-xl shadow-base-content/5 border border-base-200/60 rounded-[32px] overflow-hidden">
                <div class="p-6 border-b border-base-200/50 bg-base-200/20 flex justify-between items-center">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] opacity-40">Detailed Timeline</h3>
                    <span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-[9px] font-black uppercase tracking-widest">All Clock Logs</span>
                </div>
                <div class="p-0">
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead class="bg-base-200/30">
                                <tr>
                                    <th class="text-[10px] font-black uppercase tracking-widest py-4 pl-8">#</th>
                                    <th class="text-[10px] font-black uppercase tracking-widest py-4">In Time</th>
                                    <th class="text-[10px] font-black uppercase tracking-widest py-4">Out Time</th>
                                    <th class="text-[10px] font-black uppercase tracking-widest py-4">Duration</th>
                                    <th class="text-[10px] font-black uppercase tracking-widest py-4">Status</th>
                                    <th class="text-[10px] font-black uppercase tracking-widest py-4 text-right pr-8">IP / Location</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-base-200/60">
                                @foreach($allLogs as $index => $entry)
                                <tr class="hover:bg-base-200/30 transition-colors group">
                                    <td class="py-4 pl-8">
                                        <span class="w-6 h-6 rounded-lg bg-base-200 flex items-center justify-center text-[10px] font-black opacity-40 group-hover:bg-primary group-hover:text-white transition-all">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="py-4">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-black text-base-content/80">{{ $entry->check_in ? $entry->check_in->format('h:i A') : '--:--' }}</span>
                                            @if($entry->is_late)
                                                <span class="text-[8px] font-black text-warning uppercase">Late Entry</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <span class="text-xs font-black text-base-content/80">{{ $entry->check_out ? $entry->check_out->format('h:i A') : '--:--' }}</span>
                                    </td>
                                    <td class="py-4">
                                        <span class="text-xs font-black text-primary">{{ $entry->worked_hours ?? '0.00' }}h</span>
                                    </td>
                                    <td class="py-4">
                                        @php
                                            $statusClasses = [
                                                'present' => 'bg-success/10 text-success border-success/20',
                                                'absent' => 'bg-error/10 text-error border-error/20',
                                                'half_day' => 'bg-warning/10 text-warning border-warning/20',
                                                'late' => 'bg-info/10 text-info border-info/20',
                                            ];
                                            $statusClass = $statusClasses[$entry->status] ?? 'bg-base-200 text-base-content/50 border-base-300';
                                        @endphp
                                        <span class="px-2 py-1 rounded-lg border {{ $statusClass }} font-black text-[8px] uppercase tracking-widest">
                                            {{ str_replace('_', ' ', $entry->status) }}
                                        </span>
                                    </td>
                                    <td class="py-4 text-right pr-8">
                                        <div class="flex flex-col items-end opacity-40 group-hover:opacity-100 transition-opacity">
                                            <span class="text-[9px] font-bold">{{ $entry->check_in_ip ?? 'No IP' }}</span>
                                            @if($entry->check_in_lat && $entry->check_in_lng)
                                                <span class="text-[8px] font-black uppercase tracking-tighter">Lat: {{ $entry->check_in_lat }}</span>
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
        <div class="space-y-8">
            <!-- Employee Card -->
            <div class="card bg-base-100 shadow-xl shadow-base-content/5 border border-base-200/60 rounded-[32px] overflow-hidden">
                <div class="p-8 flex flex-col items-center text-center">
                    <div class="avatar placeholder mb-4">
                        <div class="bg-gradient-to-tr from-primary/20 to-primary/5 text-primary rounded-[32px] w-20 h-20 font-black text-xl border-4 border-white shadow-xl shadow-primary/10">
                            {{ strtoupper(substr($log->employee->first_name, 0, 1) . substr($log->employee->last_name, 0, 1)) }}
                        </div>
                    </div>
                    <h3 class="text-base font-black text-base-content/90 tracking-tight leading-none">{{ $log->employee->full_name }}</h3>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-30 mt-3">{{ $log->employee->employee_id }}</p>
                    <div class="w-full h-px bg-gradient-to-r from-transparent via-base-200 to-transparent my-6"></div>
                    <a href="{{ route('hr.employees.show', $log->employee->id) }}" class="btn btn-sm btn-ghost hover:bg-primary/5 text-primary text-[10px] font-black uppercase tracking-widest w-full rounded-2xl">View Employee Profile</a>
                </div>
            </div>

            <!-- Meta Info -->
            <div class="card bg-base-100 shadow-xl shadow-base-content/5 border border-base-200/60 rounded-[32px] overflow-hidden p-8 space-y-6">
                <div>
                    <h4 class="text-[9px] font-black opacity-30 uppercase tracking-[0.2em] mb-2">Shift Details</h4>
                    <p class="text-xs font-black text-base-content/70">Regular Office Shift (SHFT-01)</p>
                    <p class="text-[10px] font-bold opacity-40 mt-1 uppercase">09:00 AM - 06:00 PM</p>
                </div>
                <div>
                    <h4 class="text-[9px] font-black opacity-30 uppercase tracking-[0.2em] mb-2">Policy Info</h4>
                    <p class="text-xs font-black text-base-content/70">Standard Attendance Policy</p>
                    <div class="flex gap-2 mt-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-success"></span>
                        <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                        <span class="w-1.5 h-1.5 rounded-full bg-warning"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
