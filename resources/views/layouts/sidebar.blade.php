@php
    use App\Core\Constants\RoleConstants;
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

            @if($hasHr && Auth::user()->hasAnyRole([RoleConstants::TADMIN, RoleConstants::TMANAGER]))
                <div class="pt-6 pb-2">
                    <p class="px-4 text-[10px] font-black tracking-widest text-white/40 uppercase">Workforce</p>
                </div>
                <a href="{{ route('hr.employees.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('hr.employees.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[20px] mr-3">badge</span>
                    {{ __('Employees') }}
                </a>
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
            @endif

            @if(($hasAttendance || $hasLeave || $hasPayroll || $hasOperations) && Auth::user()->hasAnyRole([RoleConstants::TADMIN, RoleConstants::TMANAGER]))
                <div class="pt-6 pb-2">
                    <p class="px-4 text-[10px] font-black tracking-widest text-white/40 uppercase">Operations</p>
                </div>
                @if($hasAttendance)
                <a href="{{ route('attendance.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('attendance.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[20px] mr-3">event_available</span>
                    {{ __('Attendance') }}
                </a>
                @endif
                @if($hasLeave)
                <a href="{{ route('leave.requests.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('leave.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[20px] mr-3">event_busy</span>
                    {{ __('Leaves') }}
                </a>
                @endif
                @if($hasPayroll)
                <a href="{{ route('payroll.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('payroll.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[20px] mr-3">payments</span>
                    {{ __('Payroll') }}
                </a>
                @endif
                @if($hasOperations)
                @can('view_leads')
                <a href="{{ route('operations.leads.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('operations.leads.*', 'operations.contacts.*', 'operations.clients.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[20px] mr-3">business_center</span>
                    {{ __('Leads & Clients') }}
                </a>
                @endcan
                @can('view_projects')
                <a href="{{ route('operations.projects.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('operations.projects.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[20px] mr-3">topic</span>
                    {{ __('Projects') }}
                </a>
                @endcan
                @can('view_tasks')
                <a href="{{ route('operations.tasks.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('operations.tasks.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[20px] mr-3">assignment</span>
                    {{ __('Tasks') }}
                </a>
                @endcan
                @can('view_timesheet')
                <a href="{{ route('operations.timesheets.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('operations.timesheets.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[20px] mr-3">work_history</span>
                    {{ __('Timesheets') }}
                </a>
                @endcan
                @endif
            @endif

            @if(($hasPerformance || $hasRecruitment) && Auth::user()->hasAnyRole([RoleConstants::TADMIN, RoleConstants::TMANAGER]))
                <div class="pt-6 pb-2">
                    <p class="px-4 text-[10px] font-black tracking-widest text-white/40 uppercase">Growth</p>
                </div>
                @if($hasPerformance)
                <a href="{{ route('performance.dashboard') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('performance.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[20px] mr-3">speed</span>
                    {{ __('Performance') }}
                </a>
                @endif
                @if($hasRecruitment)
                <a href="{{ route('recruitment.dashboard') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('recruitment.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[20px] mr-3">how_to_reg</span>
                    {{ __('Recruitment') }}
                </a>
                @endif
            @endif

            @if($hasReports && Auth::user()->hasAnyRole([RoleConstants::TADMIN, RoleConstants::TMANAGER]))
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
            <div class="pt-6 pb-2">
                <p class="px-4 text-[10px] font-black tracking-widest text-white/40 uppercase">Self Service</p>
            </div>
            <a href="{{ route('attendance.kiosk') }}"
                class="flex items-center px-4 py-3 {{ request()->routeIs('attendance.kiosk') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                <span class="material-symbols-outlined text-[20px] mr-3">timer</span>
                {{ __('My Attendance') }}
            </a>
            <a href="{{ route('leave.requests.index') }}"
                class="flex items-center px-4 py-3 {{ request()->routeIs('leave.requests.*') ? 'text-white bg-white/20 font-bold' : 'text-white/70 hover:text-white hover:bg-white/10 font-medium' }} rounded-xl transition-all">
                <span class="material-symbols-outlined text-[20px] mr-3">event_busy</span>
                {{ __('My Leaves') }}
            </a>
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
        @endif
    </nav>
</div>
