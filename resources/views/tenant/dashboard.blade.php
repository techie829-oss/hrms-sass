@php
    use App\Core\Constants\RoleConstants;
    use App\Core\Constants\PermissionConstants;
@endphp
<x-app-layout>
    <div class="space-y-5 animate-fade-in pb-8">
        <!-- Welcome Banner (Premium Compact) -->
        <div class="relative overflow-hidden rounded-2xl bg-slate-900 shadow-md border border-slate-800" style="background: linear-gradient(135deg, #0f172a 0%, var(--color-primary-900, #1e3a8a) 100%);">
            <!-- Abstract background shapes -->
            <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-primary-500 opacity-20 blur-[80px]"></div>
            <div class="absolute -left-10 -bottom-10 h-40 w-40 rounded-full bg-blue-400 opacity-20 blur-[60px]"></div>
            <div class="absolute right-1/4 top-0 h-32 w-full bg-gradient-to-b from-white/5 to-transparent opacity-50"></div>
            
            <div class="relative flex flex-col sm:flex-row items-center justify-between px-6 py-6 sm:px-8">
                <div class="text-left">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center rounded-full bg-white/10 px-2 py-0.5 text-[10px] font-medium text-white ring-1 ring-inset ring-white/20">
                            {{ __('Workspace Active') }}
                        </span>
                        <span class="text-xs text-slate-300 font-medium">{{ \Carbon\Carbon::now()->format('l, j F Y') }}</span>
                    </div>
                    <h2 class="text-2xl font-bold text-white tracking-tight mt-1">
                        {{ __('Welcome back') }}, {{ auth()->user()->name ?? 'Admin' }}
                    </h2>
                    <p class="mt-1 max-w-2xl text-sm text-slate-200/90">
                        Here's what's happening at <span class="font-semibold text-white">{{ saas_tenant('name') ?? 'Your Enterprise' }}</span> today.
                    </p>
                </div>
                
                @if(auth()->user()->can(PermissionConstants::CREATE_EMPLOYEES) || auth()->user()->can(PermissionConstants::MANAGE_ATTENDANCE))
                <div class="mt-5 sm:mt-0 flex flex-shrink-0 gap-3">
                    @if($hasHr && auth()->user()->can(PermissionConstants::CREATE_EMPLOYEES))
                    <a href="{{ route('hr.employees.create') }}" class="inline-flex items-center rounded-xl bg-white px-4 py-2 text-xs font-semibold text-gray-900 shadow-sm transition-all hover:bg-gray-50">
                        <span class="material-symbols-outlined mr-1.5 -ml-0.5 text-[16px] text-primary-600 font-bold">person_add</span>
                        {{ __('Add Employee') }}
                    </a>
                    @endif
                    @if($hasAttendance && auth()->user()->can(PermissionConstants::MANAGE_ATTENDANCE))
                    <a href="{{ route('attendance.kiosk') }}" class="inline-flex items-center rounded-xl bg-white/10 backdrop-blur-md px-4 py-2 text-xs font-semibold text-white shadow-sm ring-1 ring-inset ring-white/20 transition-all hover:bg-white/20">
                        <span class="material-symbols-outlined mr-1.5 -ml-0.5 text-[16px] text-white">qr_code_scanner</span>
                        {{ __('Kiosk Mode') }}
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Employees -->
            @if($hasHr && $canViewEmployees)
            <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm border border-gray-100 hover:border-blue-200 hover:shadow-md transition-all duration-300">
                <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-gradient-to-br from-blue-50 to-blue-100/50 transition-transform duration-500 group-hover:scale-150"></div>
                <div class="relative flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500">{{ __('Total Employees') }}</p>
                        <div class="mt-1 flex items-baseline gap-2">
                            <p class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ number_format($totalEmployees) }}</p>
                            <span class="text-[10px] font-medium text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-md flex items-center">
                                <span class="material-symbols-outlined text-[10px] mr-0.5">person</span>
                                {{ number_format($activeEmployees) }} {{ __('Active') }}
                            </span>
                        </div>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-blue-500/30 shadow-lg">
                        <span class="material-symbols-outlined text-[24px]">groups</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Attendance Rate -->
            @if($hasAttendance && $canViewAttendance)
            <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm border border-gray-100 hover:border-emerald-200 hover:shadow-md transition-all duration-300">
                <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-gradient-to-br from-emerald-50 to-emerald-100/50 transition-transform duration-500 group-hover:scale-150"></div>
                <div class="relative flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500">{{ __('Attendance Rate') }}</p>
                        <div class="mt-1 flex items-baseline gap-2">
                            <p class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ $attendanceRate }}%</p>
                            <span class="text-[10px] font-medium text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded-md">
                                {{ __('Today') }}
                            </span>
                        </div>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 text-white shadow-emerald-500/30 shadow-lg">
                        <span class="material-symbols-outlined text-[24px]">event_available</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Pending Leaves -->
            @if($hasLeave && ($canApproveLeave || auth()->user()->can(PermissionConstants::VIEW_OWN_LEAVE)))
            <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm border border-gray-100 hover:border-amber-200 hover:shadow-md transition-all duration-300">
                <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-gradient-to-br from-amber-50 to-amber-100/50 transition-transform duration-500 group-hover:scale-150"></div>
                <div class="relative flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500">{{ $canApproveLeave ? __('Pending Leaves') : __('My Pending Leaves') }}</p>
                        <div class="mt-1 flex items-baseline gap-2">
                            <p class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ number_format($pendingLeaves) }}</p>
                            <span class="text-[10px] font-medium text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded-md">
                                {{ $canApproveLeave ? __('Awaiting action') : __('Applied') }}
                            </span>
                        </div>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 text-white shadow-amber-500/30 shadow-lg">
                        <span class="material-symbols-outlined text-[24px]">pending_actions</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Payroll Disbursed -->
            @if($hasPayroll && $canViewPayroll)
            <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-sm border border-gray-100 hover:border-purple-200 hover:shadow-md transition-all duration-300">
                <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-gradient-to-br from-purple-50 to-purple-100/50 transition-transform duration-500 group-hover:scale-150"></div>
                <div class="relative flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500">{{ __('Payroll Disbursed') }}</p>
                        <div class="mt-1 flex items-baseline gap-2">
                            <p class="text-2xl font-extrabold text-gray-900 tracking-tight">₹{{ number_format($payrollDisbursed) }}</p>
                            <span class="text-[10px] font-medium text-purple-700 bg-purple-50 px-1.5 py-0.5 rounded-md">
                                {{ __('This Month') }}
                            </span>
                        </div>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 text-white shadow-purple-500/30 shadow-lg">
                        <span class="material-symbols-outlined text-[24px]">payments</span>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <!-- Left Side: Main Tables and Charts -->
            <div class="lg:col-span-2 space-y-5">
                
                <!-- Quick Actions Grid -->
                @if(auth()->user()->can(PermissionConstants::VIEW_EMPLOYEES) || auth()->user()->can(PermissionConstants::MANAGE_ATTENDANCE) || auth()->user()->can(PermissionConstants::APPROVE_LEAVE) || auth()->user()->can(PermissionConstants::VIEW_PAYROLL))
                <div class="space-y-3">
                    <h3 class="text-[11px] font-bold text-gray-500 flex items-center uppercase tracking-wider">
                        {{ __('Quick Actions') }}
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @if($hasHr && auth()->user()->can(PermissionConstants::VIEW_EMPLOYEES))
                        <a href="{{ route('hr.employees.index') }}" class="group flex flex-col justify-between rounded-2xl border border-gray-100 bg-white p-5 transition-all duration-300 hover:border-blue-200 hover:shadow-lg hover:shadow-blue-500/5 hover:-translate-y-0.5">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50/50 text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white">
                                <span class="material-symbols-outlined text-[20px]">badge</span>
                            </div>
                            <div class="mt-4">
                                <h4 class="text-sm font-bold text-gray-900 group-hover:text-blue-700 transition-colors">{{ __('Manage Employees') }}</h4>
                                <p class="mt-1 text-xs text-gray-500 line-clamp-1">Profiles & directory</p>
                            </div>
                        </a>
                        @endif

                        @if($hasAttendance && auth()->user()->can(PermissionConstants::MANAGE_ATTENDANCE))
                        <a href="{{ route('attendance.index') }}" class="group flex flex-col justify-between rounded-2xl border border-gray-100 bg-white p-5 transition-all duration-300 hover:border-emerald-200 hover:shadow-lg hover:shadow-emerald-500/5 hover:-translate-y-0.5">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50/50 text-emerald-600 transition-colors group-hover:bg-emerald-600 group-hover:text-white">
                                <span class="material-symbols-outlined text-[20px]">event_available</span>
                            </div>
                            <div class="mt-4">
                                <h4 class="text-sm font-bold text-gray-900 group-hover:text-emerald-700 transition-colors">{{ __('Track Attendance') }}</h4>
                                <p class="mt-1 text-xs text-gray-500 line-clamp-1">Logs, shifts & policy</p>
                            </div>
                        </a>
                        @endif

                        @if($hasLeave && auth()->user()->can(PermissionConstants::APPROVE_LEAVE))
                        <a href="{{ route('leave.requests.index') }}" class="group flex flex-col justify-between rounded-2xl border border-gray-100 bg-white p-5 transition-all duration-300 hover:border-amber-200 hover:shadow-lg hover:shadow-amber-500/5 hover:-translate-y-0.5">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50/50 text-amber-600 transition-colors group-hover:bg-amber-600 group-hover:text-white">
                                <span class="material-symbols-outlined text-[20px]">event_busy</span>
                            </div>
                            <div class="mt-4">
                                <h4 class="text-sm font-bold text-gray-900 group-hover:text-amber-700 transition-colors">{{ __('Manage Leaves') }}</h4>
                                <p class="mt-1 text-xs text-gray-500 line-clamp-1">Approve & configure</p>
                            </div>
                        </a>
                        @endif

                        @if($hasPayroll && auth()->user()->can(PermissionConstants::VIEW_PAYROLL))
                        <a href="{{ route('payroll.index') }}" class="group flex flex-col justify-between rounded-2xl border border-gray-100 bg-white p-5 transition-all duration-300 hover:border-purple-200 hover:shadow-lg hover:shadow-purple-500/5 hover:-translate-y-0.5">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50/50 text-purple-600 transition-colors group-hover:bg-purple-600 group-hover:text-white">
                                <span class="material-symbols-outlined text-[20px]">payments</span>
                            </div>
                            <div class="mt-4">
                                <h4 class="text-sm font-bold text-gray-900 group-hover:text-purple-700 transition-colors">{{ __('Run Payroll') }}</h4>
                                <p class="mt-1 text-xs text-gray-500 line-clamp-1">Salary runs & payslips</p>
                            </div>
                        </a>
                        @endif

                        @if($hasRecruitment && auth()->user()->can(PermissionConstants::VIEW_RECRUITMENT))
                        <a href="{{ route('recruitment.dashboard') }}" class="group flex flex-col justify-between rounded-2xl border border-gray-100 bg-white p-5 transition-all duration-300 hover:border-rose-200 hover:shadow-lg hover:shadow-rose-500/5 hover:-translate-y-0.5">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50/50 text-rose-600 transition-colors group-hover:bg-rose-600 group-hover:text-white">
                                <span class="material-symbols-outlined text-[20px]">how_to_reg</span>
                            </div>
                            <div class="mt-4">
                                <h4 class="text-sm font-bold text-gray-900 group-hover:text-rose-700 transition-colors">{{ __('Job Postings') }}</h4>
                                <p class="mt-1 text-xs text-gray-500 line-clamp-1">Careers & applications</p>
                            </div>
                        </a>
                        @endif

                        @if(auth()->user()->hasRole(RoleConstants::TADMIN) || auth()->user()->can(PermissionConstants::MANAGE_SETTINGS))
                        <a href="{{ route($dashboardRoute) }}" class="group flex flex-col justify-between rounded-2xl border border-gray-100 bg-white p-5 transition-all duration-300 hover:border-gray-300 hover:shadow-lg hover:shadow-gray-500/5 hover:-translate-y-0.5">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-50/50 text-gray-600 transition-colors group-hover:bg-gray-800 group-hover:text-white">
                                <span class="material-symbols-outlined text-[20px]">settings</span>
                            </div>
                            <div class="mt-4">
                                <h4 class="text-sm font-bold text-gray-900 group-hover:text-gray-900 transition-colors">{{ __('Portal Settings') }}</h4>
                                <p class="mt-1 text-xs text-gray-500 line-clamp-1">Manage configuration</p>
                            </div>
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                @if($canViewEmployees)
                <!-- Employee Directory Panel (school-erp table style) -->
                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-4">
                        <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">
                            {{ __('Employee Directory') }}
                        </h3>
                        @if($hasHr)
                        <a href="{{ route('hr.employees.index') }}" class="text-[10px] font-semibold text-primary-600 hover:text-primary-700 bg-primary-50 px-2 py-1 rounded-md transition-colors">{{ __('View All') }}</a>
                        @endif
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead>
                                <tr class="text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                    <th class="pb-3">{{ __('Name') }}</th>
                                    <th class="pb-3">{{ __('Department') }}</th>
                                    <th class="pb-3">{{ __('Designation') }}</th>
                                    <th class="pb-3">{{ __('Status') }}</th>
                                    <th class="pb-3 text-right">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-xs font-medium text-gray-700">
                                @forelse($recentEmployees as $employee)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-primary-50 text-primary-700 rounded-lg flex items-center justify-center font-bold text-[10px] border border-primary-100/50">
                                                {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                                            </div>
                                            <div class="font-bold text-gray-900">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-gray-500">{{ $employee->department ? $employee->department->name : 'N/A' }}</td>
                                    <td class="py-3 text-gray-500">{{ $employee->designation ? $employee->designation->name : 'N/A' }}</td>
                                    <td class="py-3">
                                        @if($employee->status === 'active')
                                            <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-[10px] font-bold text-emerald-700 ring-1 ring-inset ring-emerald-600/10">ACTIVE</span>
                                        @else
                                            <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-[10px] font-bold text-gray-600 ring-1 ring-inset ring-gray-500/10">{{ strtoupper($employee->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-right">
                                        <a href="{{ route('hr.employees.show', $employee->id) }}" class="inline-flex items-center p-1 rounded-md text-gray-400 hover:text-primary-600 hover:bg-primary-50 transition-all">
                                            <span class="material-symbols-outlined text-[16px]">visibility</span>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-400 italic">{{ __('No employees found.') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Department Distribution & Progress -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <h4 class="text-[11px] font-bold uppercase tracking-wider mb-5 text-gray-500">{{ __('Department Distribution') }}</h4>
                        <div class="space-y-4">
                            @php $colors = ['bg-primary-600', 'bg-secondary-600', 'bg-accent-600', 'bg-blue-600']; $colorIndex = 0; @endphp
                            @forelse($departmentDistribution as $dept)
                            <div>
                                <div class="flex justify-between text-[10px] font-semibold mb-1 text-gray-700 uppercase">
                                    <span>{{ $dept['name'] }}</span>
                                    <span class="font-bold">{{ $dept['percentage'] }}%</span>
                                </div>
                                <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full {{ $colors[$colorIndex % count($colors)] }}" style="width: {{ $dept['percentage'] }}%"></div>
                                </div>
                            </div>
                            @php $colorIndex++; @endphp
                            @empty
                            <p class="text-xs text-center text-gray-400 italic py-4">{{ __('No data') }}</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm flex flex-col justify-center items-center text-center">
                        <div class="relative flex items-center justify-center mb-4">
                            <!-- Circular Progress Indicator -->
                            <svg class="w-20 h-20 transform -rotate-90">
                                <circle class="text-gray-100" stroke-width="6" stroke="currentColor" fill="transparent" r="32" cx="40" cy="40"/>
                                <circle class="text-primary-600" stroke-width="6" stroke-dasharray="201" stroke-dashoffset="36" stroke-linecap="round" stroke="currentColor" fill="transparent" r="32" cx="40" cy="40"/>
                            </svg>
                            <span class="absolute text-sm font-extrabold text-gray-900">82%</span>
                        </div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-gray-800">{{ __('Target Progress') }}</h4>
                        <p class="text-[11px] text-gray-400 mt-1">{{ __('Productivity alignment status') }}</p>
                    </div>
                </div>
                @else
                <!-- Employee Specific View -->
                <div class="space-y-5">
                    <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm border border-gray-100 flex items-center gap-5">
                        <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center text-primary-600 border border-primary-100/50 shrink-0">
                            <span class="material-symbols-outlined text-[24px]">waving_hand</span>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h3>
                            <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">
                                Have a productive day ahead. You can track your time, manage leaves, and view your performance from here.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- My Recent Attendance -->
                        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                            <div class="border-b border-gray-100 pb-3 mb-4">
                                <h3 class="font-bold text-[11px] uppercase tracking-wider text-gray-500">My Recent Attendance</h3>
                            </div>
                            <div class="space-y-3.5">
                                @forelse($myRecentAttendance ?? [] as $log)
                                <div class="flex items-center justify-between text-xs">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($log->date)->format('d M, Y') }}</span>
                                        <span class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">{{ \Carbon\Carbon::parse($log->date)->format('l') }}</span>
                                    </div>
                                    <div class="flex gap-4 text-right">
                                        <div class="flex flex-col font-semibold">
                                            <span class="text-primary-600">IN: {{ $log->check_in ? \Carbon\Carbon::parse($log->check_in)->format('H:i') : '--:--' }}</span>
                                            <span class="text-secondary-600">OUT: {{ $log->check_out ? \Carbon\Carbon::parse($log->check_out)->format('H:i') : '--:--' }}</span>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <p class="text-xs text-center text-gray-400 italic py-2">No recent logs</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Company Policy or Info -->
                        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm flex flex-col justify-center items-center text-center">
                            <span class="material-symbols-outlined text-primary-600/30 text-4xl mb-2">info</span>
                            <h4 class="font-bold text-xs text-gray-800">Quick Info</h4>
                            <p class="text-xs text-gray-400 mt-1 italic">Stay updated with latest company news and policies here.</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Side: Activity and Tasks (Dense Sidebar) -->
            <div class="space-y-5">
                @if($hasAttendance)
                <!-- Time Tracking Widget (school-erp card style) -->
                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                        <h4 class="text-[11px] font-bold uppercase tracking-wider text-gray-500">Time Tracking</h4>
                        <span class="inline-flex items-center rounded-md bg-primary-50 px-2 py-0.5 text-[10px] font-bold text-primary-700 ring-1 ring-inset ring-primary-700/10">{{ now()->format('D, d M') }}</span>
                    </div>

                    @if(!$currentUserAttendance)
                    <div class="text-center py-2">
                        <div class="text-3xl font-extrabold text-gray-900 tracking-tight tabular-nums mb-1" id="clock-display">{{ now()->format('H:i:s') }}</div>
                        <div class="flex flex-col items-center gap-1 mb-5">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Not Clocked In</p>
                            @if($assignedShift)
                                <span class="inline-flex items-center rounded-md bg-gray-50 px-1.5 py-0.5 text-[9px] font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Shift: {{ $assignedShift->name }} ({{ Carbon\Carbon::parse($assignedShift->start_time)->format('H:i') }})</span>
                            @endif
                        </div>
                        
                        @if($enforceKiosk ?? false)
                            <div class="mt-4 p-4 bg-primary-50/50 rounded-2xl border border-primary-100/30">
                                <p class="text-[10px] font-bold text-primary-700 uppercase tracking-widest mb-3">Secure Kiosk Mode Enabled</p>
                                <a href="{{ route('attendance.kiosk') }}" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                    <span class="material-symbols-outlined text-sm mr-2">qr_code_scanner</span>
                                    <span class="text-[10px] font-bold uppercase tracking-widest">Go to Kiosk</span>
                                </a>
                                <p class="text-[9px] text-gray-400 mt-2">
                                    Face/Location capture required
                                </p>
                            </div>
                        @else
                            <form action="{{ route('attendance.clock-in') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-xs font-bold text-white bg-primary-600 hover:bg-primary-700 transition-colors">
                                    <span class="material-symbols-outlined text-sm mr-2">login</span>
                                    {{ __('Clock In Now') }}
                                </button>
                            </form>
                        @endif
                    </div>
                    @elseif($currentUserAttendance && !$currentUserAttendance->check_out)
                    <div class="text-center py-2">
                        <div class="text-3xl font-extrabold text-primary-600 tracking-tight tabular-nums mb-1" id="clock-display">{{ now()->format('H:i:s') }}</div>
                        <div class="flex flex-col items-center gap-2 mb-5 uppercase tracking-widest">
                            <p class="text-[10px] text-primary-700 font-bold flex items-center justify-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary-500 animate-pulse"></span>
                                Clocked In at {{ \Carbon\Carbon::parse($currentUserAttendance->check_in)->format('H:i') }}
                            </p>
                            @if($currentUserAttendance->is_late)
                                <span class="inline-flex items-center rounded-md bg-rose-50 px-2 py-0.5 text-[9px] font-bold text-rose-700 ring-1 ring-inset ring-rose-600/10">LATE BY {{ $currentUserAttendance->late_minutes }} MINS</span>
                            @endif
                        </div>
                        
                        @if($enforceKiosk ?? false)
                            <div class="mt-4 p-4 bg-rose-50/50 rounded-2xl border border-rose-100/30">
                                <p class="text-[10px] font-bold text-rose-700 uppercase tracking-widest mb-3">Secure Kiosk Mode Enabled</p>
                                <a href="{{ route('attendance.kiosk') }}" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition-colors">
                                    <span class="material-symbols-outlined text-sm mr-2">logout</span>
                                    <span class="text-[10px] font-bold uppercase tracking-widest">Clock Out via Kiosk</span>
                                </a>
                            </div>
                        @else
                            <form action="{{ route('attendance.clock-out') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex justify-center items-center py-2.5 px-4 border border-rose-200 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors">
                                    <span class="material-symbols-outlined text-sm mr-2">logout</span>
                                    {{ __('Clock Out') }}
                                </button>
                            </form>
                        @endif
                    </div>
                    @else
                    <div class="text-center py-2">
                        <div class="text-3xl font-extrabold text-emerald-600 tracking-tight tabular-nums mb-1">{{ \Carbon\Carbon::parse($currentUserAttendance->check_out)->diff(\Carbon\Carbon::parse($currentUserAttendance->check_in))->format('%H:%I') }}</div>
                        <div class="flex flex-col items-center gap-2 mb-3 uppercase tracking-widest">
                            <p class="text-[10px] text-emerald-700 font-bold">Shift Completed</p>
                            @if($currentUserAttendance->is_late)
                                <span class="inline-flex items-center rounded-md bg-gray-50 px-1.5 py-0.5 text-[9px] font-medium text-gray-500 ring-1 ring-inset ring-gray-500/10">Recorded as Late</span>
                            @endif
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg flex justify-between items-center text-[10px] font-bold mb-4 text-gray-600 border border-gray-100">
                            <span>IN: {{ \Carbon\Carbon::parse($currentUserAttendance->check_in)->format('H:i') }}</span>
                            <span>OUT: {{ \Carbon\Carbon::parse($currentUserAttendance->check_out)->format('H:i') }}</span>
                        </div>

                        @if($isMultiEnabled ?? false)
                            @if($enforceKiosk ?? false)
                                <a href="{{ route('attendance.kiosk') }}" class="w-full flex justify-center items-center py-2 px-4 border border-transparent rounded-xl shadow-sm text-xs font-bold text-white bg-primary-600 hover:bg-primary-700 transition-colors">
                                    <span class="material-symbols-outlined text-sm mr-2">qr_code_scanner</span>
                                    {{ __('Clock In Again') }}
                                </a>
                            @else
                                <form action="{{ route('attendance.clock-in') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex justify-center items-center py-2 px-4 border border-transparent rounded-xl shadow-sm text-xs font-bold text-white bg-primary-600 hover:bg-primary-700 transition-colors">
                                        <span class="material-symbols-outlined text-sm mr-2">login</span>
                                        {{ __('Clock In Again') }}
                                    </button>
                                </form>
                            @endif
                        @else
                            <button disabled class="w-full py-2 px-4 rounded-xl text-xs font-bold text-gray-400 bg-gray-50 border border-gray-100 cursor-not-allowed">{{ __('Done for today') }}</button>
                        @endif
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
                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                        <h4 class="text-[11px] font-bold uppercase tracking-wider text-gray-500">Pending Tasks</h4>
                        @if($hasLeave && ($canApproveLeave || auth()->user()->can('view_own-leave')))
                        <a href="{{ route('leave.requests.index') }}" class="text-[10px] font-semibold text-primary-600 hover:text-primary-700 bg-primary-50 px-2 py-1 rounded-md transition-colors">View All</a>
                        @endif
                    </div>
                    <div class="space-y-2.5">
                        @forelse($pendingTasks ?? [] as $task)
                        <div class="flex items-center gap-3 p-3 bg-gray-50/50 rounded-xl hover:bg-primary-50/20 transition-all cursor-pointer border border-transparent hover:border-primary-100/50 {{ $task['urgent'] ? 'border-red-100 bg-red-50/20 hover:bg-red-50/40 hover:border-red-200' : '' }}">
                            <input type="checkbox" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 h-3.5 w-3.5" {{ $task['is_completed'] ? 'checked' : '' }} />
                            <div class="flex flex-col gap-0.5 text-xs">
                                <span class="font-bold {{ $task['is_completed'] ? 'text-gray-400 line-through opacity-60' : 'text-gray-800' }}">{{ $task['title'] }}</span>
                                @if($task['urgent'] && !$task['is_completed'])
                                <span class="text-[8px] text-red-600 font-extrabold uppercase tracking-wider">Urgent Action</span>
                                @endif
                            </div>
                        </div>
                        @empty
                        <p class="text-xs text-center text-gray-400 italic py-2">No pending tasks right now.</p>
                        @endforelse
                    </div>
                </div>

                @if($canViewEmployees || $canViewAttendance || $canViewLeave)
                <!-- System Activity Feed -->
                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <h4 class="text-[11px] font-bold uppercase tracking-wider mb-5 text-gray-500">System Activity</h4>
                    <div class="space-y-5 relative before:absolute before:left-[7px] before:top-2 before:bottom-2 before:w-[1px] before:bg-gray-100">
                        @forelse($recentActivities as $activity)
                        <div class="flex gap-4 relative text-xs">
                            @php
                                $dotColor = 'bg-primary-500';
                                if($activity['type'] === 'deleted') $dotColor = 'bg-rose-500';
                                if($activity['type'] === 'updated') $dotColor = 'bg-amber-500';
                            @endphp
                            <div class="w-3.5 h-3.5 rounded-full {{ $dotColor }} border-[3px] border-white shadow-sm z-10 mt-0.5"></div>
                            <div>
                                <p class="font-bold text-gray-900 leading-tight capitalize">{{ $activity['description'] }}</p>
                                <p class="text-[10px] text-gray-400 font-medium mt-0.5">{{ $activity['subject_name'] }} <span class="opacity-50">by</span> {{ $activity['causer_name'] }}</p>
                                <p class="text-[8px] text-gray-400 font-extrabold uppercase mt-1 tracking-wider">{{ $activity['time_ago'] }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-xs text-center text-gray-400 py-4 italic">No recent activity found.</p>
                        @endforelse
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
