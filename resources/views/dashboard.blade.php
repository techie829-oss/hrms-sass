<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-on-surface">Dashboard</h2>
            <div class="flex gap-2">
                <button class="btn btn-sm btn-ghost border-outline-variant/10 rounded-lg font-bold text-[10px] uppercase tracking-wider">
                    <span class="material-symbols-outlined text-sm">calendar_today</span> {{ now()->format('M Y') }}
                </button>
                <button class="btn btn-sm btn-primary border-none rounded-lg font-bold text-[10px] uppercase tracking-wider shadow-sm">
                    <span class="material-symbols-outlined text-sm">download</span> Export
                </button>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Compact Stats Row -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @if($hasHr)
            <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/10 shadow-sm transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-on-surface-variant">Workforce</span>
                    <span class="material-symbols-outlined text-primary text-base">groups</span>
                </div>
                <div class="text-2xl font-bold text-on-surface">{{ number_format($totalEmployees) }}</div>
                <div class="text-[9px] font-bold text-success mt-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[10px]">person</span> {{ number_format($activeEmployees) }} Active
                </div>
            </div>
            @endif
            
            @if($hasAttendance)
            <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/10 shadow-sm transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-on-surface-variant">Attendance</span>
                    <span class="material-symbols-outlined text-secondary text-base">event_available</span>
                </div>
                <div class="text-2xl font-bold text-on-surface">{{ $attendanceRate }}%</div>
                <div class="text-[9px] font-bold text-on-surface-variant mt-1 italic opacity-70">Today's rate</div>
            </div>
            @endif

            @if($hasLeave)
            <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/10 shadow-sm transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-on-surface-variant">Pending Leaves</span>
                    <span class="material-symbols-outlined text-tertiary text-base">pending_actions</span>
                </div>
                <div class="text-2xl font-bold text-tertiary">{{ number_format($pendingLeaves) }}</div>
                <div class="text-[9px] font-bold text-on-surface-variant mt-1 italic opacity-70">Awaiting approval</div>
            </div>
            @endif

            @if($hasPayroll)
            <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/10 shadow-sm transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-on-surface-variant">Payroll Disbursed</span>
                    <span class="material-symbols-outlined text-primary text-base">payments</span>
                </div>
                <div class="text-2xl font-bold text-on-surface">₹{{ number_format($payrollDisbursed) }}</div>
                <div class="badge badge-success badge-outline text-[8px] font-bold uppercase h-4 py-0">This Month</div>
            </div>
            @endif

            <div class="hidden lg:block bg-primary p-5 rounded-xl shadow-sm relative overflow-hidden transition-all">
                <div class="relative z-10">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-white/80">System Health</span>
                    <div class="text-2xl font-bold text-white">99.9%</div>
                    <div class="w-full bg-white/20 h-1.5 rounded-full mt-2 overflow-hidden">
                        <div class="bg-white h-full rounded-full" style="width: 99%"></div>
                    </div>
                </div>
                <span class="material-symbols-outlined absolute -right-3 -bottom-3 text-5xl opacity-10 text-white">bolt</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left Side: Main Tables and Charts -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Employee Overview Table -->
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-outline-variant/5 flex items-center justify-between">
                        <h3 class="font-bold text-xs uppercase tracking-wider text-on-surface">Employee Directory</h3>
                        @if($hasHr)
                        <a href="{{ route('hr.employees.index') }}" class="text-[9px] font-bold text-primary hover:underline uppercase tracking-wider">View All</a>
                        @endif
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-xs table-zebra w-full">
                            <thead>
                                <tr class="text-on-surface-variant/70 border-b border-outline-variant/5">
                                    <th class="py-3 px-5">Name</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Status</th>
                                    <th class="text-right pr-5">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="font-medium text-[11px]">
                                @forelse($recentEmployees as $employee)
                                <tr class="hover:bg-primary/5 transition-colors border-b border-outline-variant/5">
                                    <td class="py-3 px-5">
                                        <div class="flex items-center gap-3">
                                            <div class="avatar placeholder">
                                                <div class="bg-primary/10 text-primary rounded-lg w-8 h-8 font-bold text-[9px]">{{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}</div>
                                            </div>
                                            <div>
                                                <div class="font-bold text-on-surface">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                                                <div class="text-[9px] opacity-60">{{ $employee->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $employee->department ? $employee->department->name : 'N/A' }}</td>
                                    <td>{{ $employee->designation ? $employee->designation->name : 'N/A' }}</td>
                                    <td>
                                        @if($employee->status === 'active')
                                            <span class="badge badge-success badge-sm text-[8px] font-bold text-white px-2 py-0.5">ACTIVE</span>
                                        @else
                                            <span class="badge badge-neutral badge-sm text-[8px] font-bold text-white px-2 py-0.5">{{ strtoupper($employee->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right pr-5">
                                        <a href="{{ route('hr.employees.show', $employee->id) }}" class="btn btn-ghost btn-xs rounded-md"><span class="material-symbols-outlined text-sm">visibility</span></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-on-surface-variant opacity-70 italic text-[11px]">No employees found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-surface-container-low p-6 rounded-xl border border-outline-variant/10 shadow-sm">
                        <h4 class="text-[9px] font-bold uppercase tracking-wider mb-6 text-on-surface-variant">Department Distribution</h4>
                        <div class="space-y-5">
                            @php $colors = ['primary', 'secondary', 'tertiary', 'info']; $colorIndex = 0; @endphp
                            @forelse($departmentDistribution as $dept)
                            <div>
                                <div class="flex justify-between text-[9px] font-bold mb-1.5 uppercase"><span>{{ $dept['name'] }}</span><span>{{ $dept['percentage'] }}%</span></div>
                                <progress class="progress progress-{{ $colors[$colorIndex % count($colors)] }} h-1.5" value="{{ $dept['percentage'] }}" max="100"></progress>
                            </div>
                            @php $colorIndex++; @endphp
                            @empty
                            <p class="text-[10px] text-center text-on-surface-variant italic">No data</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm flex flex-col justify-center items-center text-center">
                        <div class="radial-progress text-primary mb-4" style="--value:82; --size:4.5rem; --thickness: 4px;" role="progressbar">
                            <span class="text-[10px] font-bold">82%</span>
                        </div>
                        <h4 class="text-[10px] font-bold uppercase tracking-wider text-on-surface">Target Progress</h4>
                        <p class="text-[9px] text-on-surface-variant font-medium mt-1">Productivity alignment status</p>
                    </div>
                </div>
            </div>

            <!-- Right Side: Activity and Tasks (Dense Sidebar) -->
            <div class="lg:col-span-4 space-y-6">
                @if($hasAttendance)
                <!-- Time Tracking Widget -->
                <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-[9px] font-bold uppercase tracking-wider text-on-surface-variant">Time Tracking</h4>
                        <span class="badge badge-primary badge-outline text-[8px] font-bold uppercase py-0">{{ now()->format('D, d M') }}</span>
                    </div>

                    @if(!$currentUserAttendance)
                    <div class="text-center py-4">
                        <div class="text-3xl font-bold text-on-surface tabular-nums mb-1" id="clock-display">{{ now()->format('H:i:s') }}</div>
                        <div class="flex flex-col items-center gap-1 mb-6">
                            <p class="text-[9px] text-on-surface-variant font-medium uppercase tracking-widest">Not Clocked In</p>
                            @if($assignedShift)
                                <span class="badge badge-ghost text-[8px] font-black uppercase opacity-60">Shift: {{ $assignedShift->name }} ({{ Carbon\Carbon::parse($assignedShift->start_time)->format('H:i') }})</span>
                            @endif
                        </div>
                        
                        <form action="{{ route('attendance.clock-in') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-full gap-2 rounded-xl border-none shadow-md shadow-primary/20">
                                <span class="material-symbols-outlined text-sm">login</span>
                                <span class="text-[10px] font-bold uppercase tracking-widest">Clock In</span>
                            </button>
                        </form>
                    </div>
                    @elseif($currentUserAttendance && !$currentUserAttendance->check_out)
                    <div class="text-center py-4">
                        <div class="text-3xl font-bold text-primary tabular-nums mb-1" id="clock-display">{{ now()->format('H:i:s') }}</div>
                        <div class="flex flex-col items-center gap-2 mb-6 uppercase tracking-widest">
                            <p class="text-[9px] text-primary font-bold flex items-center justify-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                                Clocked In at {{ \Carbon\Carbon::parse($currentUserAttendance->check_in)->format('H:i') }}
                            </p>
                            @if($currentUserAttendance->is_late)
                                <span class="badge badge-error text-white font-black text-[8px] px-2 py-0.5">LATE BY {{ $currentUserAttendance->late_minutes }} MINS</span>
                            @endif
                        </div>
                        
                        <form action="{{ route('attendance.clock-out') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-error btn-outline w-full gap-2 rounded-xl transition-all hover:bg-error/10">
                                <span class="material-symbols-outlined text-sm">logout</span>
                                <span class="text-[10px] font-bold uppercase tracking-widest text-error">Clock Out</span>
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <div class="text-3xl font-bold text-on-surface-variant/40 tabular-nums mb-1">{{ \Carbon\Carbon::parse($currentUserAttendance->check_out)->diff(\Carbon\Carbon::parse($currentUserAttendance->check_in))->format('%H:%I') }}</div>
                        <div class="flex flex-col items-center gap-2 mb-3 uppercase tracking-widest">
                            <p class="text-[9px] text-success font-bold">Shift Completed</p>
                            @if($currentUserAttendance->is_late)
                                <span class="badge badge-neutral text-[8px] font-black opacity-40">Recorded as Late</span>
                            @endif
                        </div>
                        <div class="bg-surface-container-low p-3 rounded-lg flex justify-between items-center text-[10px] font-bold mb-2">
                            <span class="opacity-50">IN: {{ \Carbon\Carbon::parse($currentUserAttendance->check_in)->format('H:i') }}</span>
                            <span class="opacity-50">OUT: {{ \Carbon\Carbon::parse($currentUserAttendance->check_out)->format('H:i') }}</span>
                        </div>
                        <button disabled class="btn btn-ghost btn-sm w-full opacity-50 text-[9px] uppercase tracking-widest">Done for today</button>
                    </div>
                    @endif

                    <script>
                        function updateClock() {
                            const now = new Date();
                            const timeString = now.getHours().toString().padStart(2, '0') + ':' + 
                                             now.getMinutes().toString().padStart(2, '0') + ':' + 
                                             now.getSeconds().toString().padStart(2, '0');
                            const display = document.getElementById('clock-display');
                            if (display) display.textContent = timeString;
                        }
                        setInterval(updateClock, 1000);
                    </script>
                </div>
                @endif

                <!-- Task List -->
                <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h4 class="text-[9px] font-bold uppercase tracking-wider text-on-surface-variant">Pending Tasks</h4>
                        @if($hasLeave)
                        <a href="{{ route('leave.requests.index') }}" class="text-[9px] font-bold text-primary hover:underline uppercase tracking-wider">View All</a>
                        @endif
                    </div>
                    <div class="space-y-2.5">
                        @forelse($pendingTasks ?? [] as $task)
                        <div class="flex items-center gap-3 p-3 bg-surface-container-low/40 rounded-lg hover:bg-surface-container-low transition-all cursor-pointer {{ $task['urgent'] ? 'border border-error/20 bg-error/5' : '' }}">
                            <input type="checkbox" class="checkbox checkbox-xs checkbox-primary rounded-md" {{ $task['is_completed'] ? 'checked' : '' }} />
                            <div class="flex flex-col gap-0.5">
                                <span class="text-[11px] font-bold {{ $task['is_completed'] ? 'text-on-surface line-through opacity-40' : 'text-on-surface' }}">{{ $task['title'] }}</span>
                                @if($task['urgent'] && !$task['is_completed'])
                                <span class="text-[8px] text-error font-bold uppercase tracking-tighter">Urgent Action</span>
                                @endif
                            </div>
                        </div>
                        @empty
                        <p class="text-[10px] text-center text-on-surface-variant italic py-2">No pending tasks right now.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Activity Feed -->
                <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm">
                    <h4 class="text-[9px] font-bold uppercase tracking-wider mb-6 text-on-surface-variant">System Activity</h4>
                    <div class="space-y-5 relative before:absolute before:left-[7px] before:top-2 before:bottom-2 before:w-[1px] before:bg-outline-variant/10">
                        @forelse($recentActivities as $activity)
                        <div class="flex gap-4 relative">
                            @php
                                $dotColor = 'bg-primary';
                                if($activity['type'] === 'deleted') $dotColor = 'bg-error';
                                if($activity['type'] === 'updated') $dotColor = 'bg-secondary';
                            @endphp
                            <div class="w-3.5 h-3.5 rounded-full {{ $dotColor }} border-[3px] border-white shadow-sm z-10 mt-0.5"></div>
                            <div>
                                <p class="text-[11px] font-bold text-on-surface leading-tight capitalize">{{ $activity['description'] }}</p>
                                <p class="text-[9px] text-on-surface-variant font-medium mt-0.5">{{ $activity['subject_name'] }} <span class="opacity-50">by</span> {{ $activity['causer_name'] }}</p>
                                <p class="text-[8px] text-outline font-bold uppercase mt-1 tracking-wider">{{ $activity['time_ago'] }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-[10px] text-center text-on-surface-variant py-8 italic">No recent activity found.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
