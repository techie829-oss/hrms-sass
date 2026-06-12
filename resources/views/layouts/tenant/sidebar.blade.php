@php
    use App\Core\Constants\RoleConstants;
    $dashboardRoute = 'tenant.dashboard';
@endphp

<div class="h-full flex flex-col">
    <!-- Header/Logo Area -->
    <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 bg-white sticky top-0 z-10">
        <a href="{{ route($dashboardRoute) }}" class="flex items-center gap-2.5">
            <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center shadow-sm">
                <span class="material-symbols-outlined text-[18px] text-white font-bold">view_cozy</span>
            </div>
            <div class="text-left">
                <h1 class="text-sm font-bold text-gray-900 leading-none">
                    {{ saas_tenant('name') ?? config('app.name', 'HRMS') }}
                </h1>
                <p class="text-[9px] font-semibold text-gray-400 uppercase tracking-widest mt-0.5">HRMS Portal</p>
            </div>
        </a>
        <!-- Close button for mobile -->
        <button class="lg:hidden text-gray-400 hover:text-gray-600 p-1 rounded-md hover:bg-gray-100 transition-colors" onclick="toggleSidebar()">
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
    </div>

    <!-- Navigation List -->
    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-6 pb-24">
        <!-- Dashboard Link (Direct/Standalone) -->
        <div class="space-y-1">
            <a href="{{ route($dashboardRoute) }}"
                class="group flex items-center px-3 py-2 text-xs font-semibold rounded-lg transition-all duration-150 {{ request()->routeIs($dashboardRoute) ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <span class="material-symbols-outlined text-[18px] mr-2.5 transition-colors {{ request()->routeIs($dashboardRoute) ? 'text-primary-500 font-bold' : 'text-gray-400 group-hover:text-gray-500' }}">dashboard</span>
                {{ __('Dashboard') }}
            </a>
        </div>

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

        <!-- Workforce Section -->
        @if($hasHr && Auth::user()->hasAnyRole([RoleConstants::TADMIN, RoleConstants::TMANAGER]))
            <div class="space-y-1">
                <p class="px-3 mb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('Workforce') }}</p>
                <div class="space-y-0.5">
                    <a href="{{ route('hr.employees.index') }}"
                        class="group flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('hr.employees.*') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <span class="material-symbols-outlined text-[18px] mr-2.5 transition-colors {{ request()->routeIs('hr.employees.*') ? 'text-primary-500 font-bold' : 'text-gray-400 group-hover:text-gray-500' }}">badge</span>
                        {{ __('Employees') }}
                    </a>
                    <a href="{{ route('hr.departments.index') }}"
                        class="group flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('hr.departments.*') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <span class="material-symbols-outlined text-[18px] mr-2.5 transition-colors {{ request()->routeIs('hr.departments.*') ? 'text-primary-500 font-bold' : 'text-gray-400 group-hover:text-gray-500' }}">hub</span>
                        {{ __('Departments') }}
                    </a>
                    <a href="{{ route('hr.designations.index') }}"
                        class="group flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('hr.designations.*') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <span class="material-symbols-outlined text-[18px] mr-2.5 transition-colors {{ request()->routeIs('hr.designations.*') ? 'text-primary-500 font-bold' : 'text-gray-400 group-hover:text-gray-500' }}">work</span>
                        {{ __('Designations') }}
                    </a>
                </div>
            </div>
        @endif

        <!-- Operations Section -->
        @if(($hasAttendance || $hasLeave || $hasPayroll || $hasOperations) && Auth::user()->hasAnyRole([RoleConstants::TADMIN, RoleConstants::TMANAGER]))
            <div class="space-y-1">
                <p class="px-3 mb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('Operations') }}</p>
                <div class="space-y-0.5">
                    @if($hasAttendance)
                        <a href="{{ route('attendance.index') }}"
                            class="group flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('attendance.*') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-symbols-outlined text-[18px] mr-2.5 transition-colors {{ request()->routeIs('attendance.*') ? 'text-primary-500 font-bold' : 'text-gray-400 group-hover:text-gray-500' }}">event_available</span>
                            {{ __('Attendance') }}
                        </a>
                    @endif
                    @if($hasLeave)
                        <a href="{{ route('leave.requests.index') }}"
                            class="group flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('leave.*') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-symbols-outlined text-[18px] mr-2.5 transition-colors {{ request()->routeIs('leave.*') ? 'text-primary-500 font-bold' : 'text-gray-400 group-hover:text-gray-500' }}">event_busy</span>
                            {{ __('Leaves') }}
                        </a>
                    @endif
                    @if($hasPayroll)
                        <a href="{{ route('payroll.index') }}"
                            class="group flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('payroll.*') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-symbols-outlined text-[18px] mr-2.5 transition-colors {{ request()->routeIs('payroll.*') ? 'text-primary-500 font-bold' : 'text-gray-400 group-hover:text-gray-500' }}">payments</span>
                            {{ __('Payroll') }}
                        </a>
                    @endif
                    @if($hasOperations)
                        <a href="{{ route('operations.leads.index') }}"
                            class="group flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('operations.leads.*', 'operations.contacts.*', 'operations.clients.*') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-symbols-outlined text-[18px] mr-2.5 transition-colors {{ request()->routeIs('operations.leads.*', 'operations.contacts.*', 'operations.clients.*') ? 'text-primary-500 font-bold' : 'text-gray-400 group-hover:text-gray-500' }}">business_center</span>
                            {{ __('Leads & Clients') }}
                        </a>
                        <a href="{{ route('operations.projects.index') }}"
                            class="group flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('operations.projects.*') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-symbols-outlined text-[18px] mr-2.5 transition-colors {{ request()->routeIs('operations.projects.*') ? 'text-primary-500 font-bold' : 'text-gray-400 group-hover:text-gray-500' }}">topic</span>
                            {{ __('Projects') }}
                        </a>
                        <a href="{{ route('operations.tasks.index') }}"
                            class="group flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('operations.tasks.*') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-symbols-outlined text-[18px] mr-2.5 transition-colors {{ request()->routeIs('operations.tasks.*') ? 'text-primary-500 font-bold' : 'text-gray-400 group-hover:text-gray-500' }}">assignment</span>
                            {{ __('Tasks') }}
                        </a>
                        <a href="{{ route('operations.timesheets.index') }}"
                            class="group flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('operations.timesheets.*') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-symbols-outlined text-[18px] mr-2.5 transition-colors {{ request()->routeIs('operations.timesheets.*') ? 'text-primary-500 font-bold' : 'text-gray-400 group-hover:text-gray-500' }}">work_history</span>
                            {{ __('Timesheets') }}
                        </a>
                    @endif
                </div>
            </div>
        @endif

        <!-- Growth Section -->
        @if(($hasPerformance || $hasRecruitment) && Auth::user()->hasAnyRole([RoleConstants::TADMIN, RoleConstants::TMANAGER]))
            <div class="space-y-1">
                <p class="px-3 mb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('Growth') }}</p>
                <div class="space-y-0.5">
                    @if($hasPerformance)
                        <a href="{{ route('performance.dashboard') }}"
                            class="group flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('performance.*') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-symbols-outlined text-[18px] mr-2.5 transition-colors {{ request()->routeIs('performance.*') ? 'text-primary-500 font-bold' : 'text-gray-400 group-hover:text-gray-500' }}">speed</span>
                            {{ __('Performance') }}
                        </a>
                    @endif
                    @if($hasRecruitment)
                        <a href="{{ route('recruitment.dashboard') }}"
                            class="group flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('recruitment.*') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <span class="material-symbols-outlined text-[18px] mr-2.5 transition-colors {{ request()->routeIs('recruitment.*') ? 'text-primary-500 font-bold' : 'text-gray-400 group-hover:text-gray-500' }}">how_to_reg</span>
                            {{ __('Recruitment') }}
                        </a>
                    @endif
                </div>
            </div>
        @endif

        <!-- Analytics Section -->
        @if($hasReports && Auth::user()->hasAnyRole([RoleConstants::TADMIN, RoleConstants::TMANAGER]))
            <div class="space-y-1">
                <p class="px-3 mb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('Analytics') }}</p>
                <div class="space-y-0.5">
                    <a href="{{ route('reports.index') }}"
                        class="group flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('reports.*') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <span class="material-symbols-outlined text-[18px] mr-2.5 transition-colors {{ request()->routeIs('reports.*') ? 'text-primary-500 font-bold' : 'text-gray-400 group-hover:text-gray-500' }}">insert_chart</span>
                        {{ __('Reports') }}
                    </a>
                </div>
            </div>
        @endif

        <!-- Self Service Section -->
        <div class="space-y-1">
            <p class="px-3 mb-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('Self Service') }}</p>
            <div class="space-y-0.5">
                <a href="{{ route('attendance.kiosk') }}"
                    class="group flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('attendance.kiosk') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span class="material-symbols-outlined text-[18px] mr-2.5 transition-colors {{ request()->routeIs('attendance.kiosk') ? 'text-primary-500 font-bold' : 'text-gray-400 group-hover:text-gray-500' }}">timer</span>
                    {{ __('My Attendance') }}
                </a>
                <a href="{{ route('leave.requests.index') }}"
                    class="group flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('leave.requests.*') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span class="material-symbols-outlined text-[18px] mr-2.5 transition-colors {{ request()->routeIs('leave.requests.*') ? 'text-primary-500 font-bold' : 'text-gray-400 group-hover:text-gray-500' }}">event_busy</span>
                    {{ __('My Leaves') }}
                </a>
            </div>
        </div>
    </nav>
</div>
