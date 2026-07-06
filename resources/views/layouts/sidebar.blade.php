@php
    use App\Core\Constants\RoleConstants;
    use App\Core\Constants\PermissionConstants;
    $dashboardRoute = 'dashboard';
    if (saas_tenant()) {
        $dashboardRoute = 'tenant.dashboard';
    } else {
        $dashboardRoute = 'super-admin.dashboard';
    }
@endphp

<div class="p-6">
    <!-- Logo -->
    <div class="flex items-center justify-between mb-8">
        <a href="{{ route($dashboardRoute) }}" class="flex items-center">
            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center mr-3 shadow-sm">
                <span class="material-symbols-outlined text-[20px] text-primary-600">view_cozy</span>
            </div>
            <h1 class="text-xl font-bold text-white tracking-tight">{{ config('app.name', 'HRMS') }}</h1>
        </a>
        <!-- Close button for mobile -->
        <button class="lg:hidden text-white/80 hover:text-white transition-colors" onclick="toggleSidebar()">
            <span class="material-symbols-outlined text-[24px]">close</span>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="space-y-1.5 pb-20">
        <a href="{{ route($dashboardRoute) }}"
            class="flex items-center px-4 py-3 {{ request()->routeIs($dashboardRoute) ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
            <span class="material-symbols-outlined text-[20px] mr-3">dashboard</span>
            {{ __('Dashboard') }}
        </a>

        @if(saas_tenant())
            @php
                $moduleManager = app(\App\SaaS\Modules\ModuleManager::class);
                $tenantId = saas_tenant('id');
                $hasHr = $moduleManager->tenantHasAccess('hr', $tenantId);
                $hasAttendance = $moduleManager->tenantHasAccess('attendance', $tenantId);
                $hasLeave = $moduleManager->tenantHasAccess('leave', $tenantId);
                $hasPayroll = $moduleManager->tenantHasAccess('payroll', $tenantId);
                $hasPerformance = $moduleManager->tenantHasAccess('performance', $tenantId);
                $hasReports = $moduleManager->tenantHasAccess('reports', $tenantId);
                $hasRecruitment = $moduleManager->tenantHasAccess('recruitment', $tenantId);
                $hasOperations = $moduleManager->tenantHasAccess('operations', $tenantId);
            @endphp

            @if($hasHr && (Auth::user()->can(PermissionConstants::VIEW_EMPLOYEES) || Auth::user()->can(PermissionConstants::VIEW_DEPARTMENTS)))
                <div class="pt-6 pb-2">
                    <p class="px-4 text-[10px] font-black tracking-widest text-white/40 uppercase">Workforce</p>
                </div>
                @can(PermissionConstants::VIEW_EMPLOYEES)
                <a href="{{ route('hr.employees.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('hr.employees.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[20px] mr-3">badge</span>
                    {{ __('Employees') }}
                </a>
                @endcan
                @can(PermissionConstants::VIEW_DEPARTMENTS)
                <a href="{{ route('hr.departments.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('hr.departments.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[20px] mr-3">hub</span>
                    {{ __('Departments') }}
                </a>
                <a href="{{ route('hr.designations.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('hr.designations.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[20px] mr-3">work</span>
                    {{ __('Designations') }}
                </a>
                @endcan
            @endif

            @if(($hasAttendance && Auth::user()->can(PermissionConstants::VIEW_ATTENDANCE)) || ($hasLeave && Auth::user()->can(PermissionConstants::MANAGE_LEAVE)) || ($hasPayroll && Auth::user()->can(PermissionConstants::VIEW_PAYROLL)) || ($hasOperations && (Auth::user()->can(PermissionConstants::VIEW_LEADS) || Auth::user()->can(PermissionConstants::VIEW_PROJECTS) || Auth::user()->can(PermissionConstants::VIEW_TASKS) || Auth::user()->can(PermissionConstants::VIEW_TIMESHEET))))
                <div class="pt-6 pb-2">
                    <p class="px-4 text-[10px] font-black tracking-widest text-white/40 uppercase">Operations</p>
                </div>
                @if($hasAttendance && Auth::user()->can(PermissionConstants::VIEW_ATTENDANCE))
                <a href="{{ route('attendance.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('attendance.*') && !request()->routeIs('attendance.kiosk') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[20px] mr-3">event_available</span>
                    {{ __('Attendance') }}
                </a>
                @endif
                @if($hasLeave && Auth::user()->can(PermissionConstants::MANAGE_LEAVE))
                <a href="{{ route('leave.requests.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('leave.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[20px] mr-3">event_busy</span>
                    {{ __('Leaves') }}
                </a>
                @endif
                @if($hasPayroll && Auth::user()->can(PermissionConstants::VIEW_PAYROLL))
                <a href="{{ route('payroll.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('payroll.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[20px] mr-3">payments</span>
                    {{ __('Payroll') }}
                </a>
                @endif
                @if($hasOperations)
                @can(PermissionConstants::VIEW_LEADS)
                <a href="{{ route('operations.leads.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('operations.leads.*', 'operations.contacts.*', 'operations.clients.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[20px] mr-3">business_center</span>
                    {{ __('Leads & Clients') }}
                </a>
                @endcan
                @can(PermissionConstants::VIEW_PROJECTS)
                <a href="{{ route('operations.projects.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('operations.projects.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[20px] mr-3">topic</span>
                    {{ __('Projects') }}
                </a>
                @endcan
                @can(PermissionConstants::VIEW_TASKS)
                <a href="{{ route('operations.tasks.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('operations.tasks.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[20px] mr-3">assignment</span>
                    {{ __('Tasks') }}
                </a>
                @endcan
                @can(PermissionConstants::VIEW_TIMESHEET)
                <a href="{{ route('operations.timesheets.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('operations.timesheets.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[20px] mr-3">work_history</span>
                    {{ __('Timesheets') }}
                </a>
                @endcan
                @endif
            @endif

            @if(($hasPerformance && Auth::user()->can(PermissionConstants::VIEW_PERFORMANCE)) || ($hasRecruitment && Auth::user()->can(PermissionConstants::VIEW_RECRUITMENT)))
                <div class="pt-6 pb-2">
                    <p class="px-4 text-[10px] font-black tracking-widest text-white/40 uppercase">Growth</p>
                </div>
                @if($hasPerformance && Auth::user()->can(PermissionConstants::VIEW_PERFORMANCE))
                <a href="{{ route('performance.dashboard') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('performance.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[20px] mr-3">speed</span>
                    {{ __('Performance') }}
                </a>
                @endif
                @if($hasRecruitment && Auth::user()->can(PermissionConstants::VIEW_RECRUITMENT))
                <a href="{{ route('recruitment.dashboard') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('recruitment.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[20px] mr-3">how_to_reg</span>
                    {{ __('Recruitment') }}
                </a>
                @endif
            @endif

            @if($hasReports && Auth::user()->can(PermissionConstants::VIEW_REPORTS))
                <div class="pt-6 pb-2">
                    <p class="px-4 text-[10px] font-black tracking-widest text-white/40 uppercase">Analytics</p>
                </div>
                <a href="{{ route('reports.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('reports.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[20px] mr-3">insert_chart</span>
                    {{ __('Reports') }}
                </a>
            @endif

            <!-- Self Service (My Space) For Everyone -->
            @if(($hasAttendance && Auth::user()->can(PermissionConstants::VIEW_OWN_ATTENDANCE)) || ($hasLeave && Auth::user()->can(PermissionConstants::VIEW_OWN_LEAVE)) || ($hasOperations && Auth::user()->can(PermissionConstants::VIEW_OWN_TIMESHEET) && !Auth::user()->can(PermissionConstants::VIEW_TIMESHEET)))
            <div class="pt-6 pb-2">
                <p class="px-4 text-[10px] font-black tracking-widest text-white/40 uppercase">Self Service</p>
            </div>
            @if($hasAttendance && Auth::user()->can(PermissionConstants::VIEW_OWN_ATTENDANCE))
            <a href="{{ route('attendance.kiosk') }}"
                class="flex items-center px-4 py-3 {{ request()->routeIs('attendance.kiosk') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                <span class="material-symbols-outlined text-[20px] mr-3">timer</span>
                {{ __('My Attendance') }}
            </a>
            @endif
            @if($hasLeave && Auth::user()->can(PermissionConstants::VIEW_OWN_LEAVE))
            <a href="{{ route('leave.requests.index') }}"
                class="flex items-center px-4 py-3 {{ request()->routeIs('leave.requests.*') && !Auth::user()->can(PermissionConstants::MANAGE_LEAVE) ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                <span class="material-symbols-outlined text-[20px] mr-3">event_busy</span>
                {{ __('My Leaves') }}
            </a>
            @endif
            @if($hasOperations && Auth::user()->can(PermissionConstants::VIEW_OWN_TIMESHEET) && !Auth::user()->can(PermissionConstants::VIEW_TIMESHEET))
            <a href="{{ route('operations.timesheets.index') }}"
                class="flex items-center px-4 py-3 {{ request()->routeIs('operations.timesheets.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                <span class="material-symbols-outlined text-[20px] mr-3">work_history</span>
                {{ __('My Timesheets') }}
            </a>
            @endif
            @endif
        @endif

        @if(!saas_tenant() && Auth::user()->hasRole(RoleConstants::SADMIN))
            <div class="pt-6 pb-2">
                <p class="px-4 text-[10px] font-black tracking-widest text-white/40 uppercase">SaaS Platform</p>
            </div>
            <a href="{{ route('admin.tenants.index') }}"
                class="flex items-center px-4 py-3 {{ request()->routeIs('admin.tenants.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                <span class="material-symbols-outlined text-[20px] mr-3">domain</span>
                {{ __('Tenants') }}
            </a>
            <a href="{{ route('admin.plans.index') }}"
                class="flex items-center px-4 py-3 {{ request()->routeIs('admin.plans.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                <span class="material-symbols-outlined text-[20px] mr-3">payments</span>
                {{ __('Plans') }}
            </a>
            <a href="{{ route('admin.roles.index') }}"
                class="flex items-center px-4 py-3 {{ request()->routeIs('admin.roles.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                <span class="material-symbols-outlined text-[20px] mr-3">security</span>
                {{ __('Roles') }}
            </a>
            <a href="{{ route('admin.modules.index') }}"
                class="flex items-center px-4 py-3 {{ request()->routeIs('admin.modules.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                <span class="material-symbols-outlined text-[20px] mr-3">widgets</span>
                {{ __('Modules') }}
            </a>
            <a href="{{ route('admin.leads') }}"
                class="flex items-center px-4 py-3 {{ request()->routeIs('admin.leads') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                <span class="material-symbols-outlined text-[20px] mr-3">contact_mail</span>
                {{ __('Leads') }}
            </a>
        @endif
    </nav>
</div>
