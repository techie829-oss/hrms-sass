@php
    use App\Core\Constants\RoleConstants;
    $dashboardRoute = 'dashboard';
    if (saas_tenant()) {
        $dashboardRoute = 'tenant.dashboard';
    } else {
        $dashboardRoute = 'super-admin.dashboard';
    }
@endphp

<nav x-data="{ open: false }" class="bg-surface/80 backdrop-blur-xl border-b border-outline-variant/10 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route($dashboardRoute) }}" class="text-xl font-bold tracking-tight text-primary">
                        <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary border border-primary/20">
                            <span class="material-symbols-outlined text-[24px]">view_cozy</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 lg:-my-px lg:ms-8 lg:flex items-center">
                    <x-nav-link :href="route($dashboardRoute)" :active="request()->routeIs($dashboardRoute)" class="text-[11px] font-bold uppercase tracking-wider px-3 h-10 rounded-lg hover:bg-surface-container-low transition-colors">
                        {{ __('Dashboard') }}
                    </x-nav-link>

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

                        {{-- Workforce Group --}}
                        @if($hasHr && Auth::user()->hasAnyRole([RoleConstants::TADMIN, RoleConstants::TMANAGER]))
                        <x-dropdown align="left" width="w-64" contentClasses="bg-surface/90 backdrop-blur-xl border border-outline-variant/10 p-2 shadow-2xl shadow-primary/5 rounded-2xl">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border-none text-[11px] font-bold uppercase tracking-wider h-10 rounded-lg {{ request()->routeIs('hr.*') ? 'bg-primary/5 text-primary' : 'text-on-surface-variant/80 hover:bg-surface-container-low hover:text-on-surface' }} transition ease-in-out duration-150">
                                    <div class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[16px]">group</span>
                                        Workforce
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <div class="px-3 py-2 text-[10px] font-black tracking-widest uppercase text-base-content/40">Core HR</div>
                                <x-dropdown-link :href="route('hr.employees.index')" class="rounded-xl flex items-center gap-3 py-2.5 px-3 hover:bg-primary/5 transition-colors group">
                                    <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-[16px]">badge</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-on-surface">Employees</span>
                                        <span class="text-[10px] text-on-surface-variant/60 font-medium">Manage team directory</span>
                                    </div>
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('hr.departments.index')" class="rounded-xl flex items-center gap-3 py-2.5 px-3 hover:bg-primary/5 transition-colors group">
                                    <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-[16px]">hub</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-on-surface">Departments</span>
                                        <span class="text-[10px] text-on-surface-variant/60 font-medium">Organizational structure</span>
                                    </div>
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('hr.designations.index')" class="rounded-xl flex items-center gap-3 py-2.5 px-3 hover:bg-primary/5 transition-colors group">
                                    <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-[16px]">work</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-on-surface">Designations</span>
                                        <span class="text-[10px] text-on-surface-variant/60 font-medium">Roles and titles</span>
                                    </div>
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                        @endif

                        {{-- Operations Group --}}
                        @if(($hasAttendance || $hasLeave || $hasPayroll || $hasOperations) && Auth::user()->hasAnyRole([RoleConstants::TADMIN, RoleConstants::TMANAGER]))
                        <x-dropdown align="left" width="w-72" contentClasses="bg-surface/90 backdrop-blur-xl border border-outline-variant/10 p-2 shadow-2xl shadow-primary/5 rounded-2xl">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border-none text-[11px] font-bold uppercase tracking-wider h-10 rounded-lg {{ request()->routeIs('attendance.*', 'leave.*', 'payroll.*', 'operations.*') ? 'bg-primary/5 text-primary' : 'text-on-surface-variant/80 hover:bg-surface-container-low hover:text-on-surface' }} transition ease-in-out duration-150">
                                    <div class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[16px]">settings_applications</span>
                                        Operations
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <div class="grid grid-cols-1 gap-1">
                                    @if($hasAttendance)
                                    <x-dropdown-link :href="route('attendance.index')" class="rounded-xl flex items-center gap-3 py-2 px-3 hover:bg-primary/5 transition-colors group">
                                        <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">event_available</span>
                                        </div>
                                        <span class="text-xs font-bold text-on-surface">Attendance</span>
                                    </x-dropdown-link>
                                    @endif
                                    @if($hasLeave)
                                    <x-dropdown-link :href="route('leave.requests.index')" class="rounded-xl flex items-center gap-3 py-2 px-3 hover:bg-primary/5 transition-colors group">
                                        <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">event_busy</span>
                                        </div>
                                        <span class="text-xs font-bold text-on-surface">Leaves</span>
                                    </x-dropdown-link>
                                    @endif
                                    @if($hasPayroll)
                                    <x-dropdown-link :href="route('payroll.index')" class="rounded-xl flex items-center gap-3 py-2 px-3 hover:bg-primary/5 transition-colors group">
                                        <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">payments</span>
                                        </div>
                                        <span class="text-xs font-bold text-on-surface">Payroll</span>
                                    </x-dropdown-link>
                                    @endif
                                </div>
                                @if($hasOperations)
                                <div class="px-3 py-2 mt-2 text-[10px] font-black tracking-widest uppercase text-base-content/40 border-t border-outline-variant/10">Business</div>
                                <div class="grid grid-cols-2 gap-1">
                                    <x-dropdown-link :href="route('operations.leads.index')" class="rounded-xl py-2 px-3 text-xs font-bold text-on-surface hover:bg-primary/5 hover:text-primary">Leads</x-dropdown-link>
                                    <x-dropdown-link :href="route('operations.contacts.index')" class="rounded-xl py-2 px-3 text-xs font-bold text-on-surface hover:bg-primary/5 hover:text-primary">Contacts</x-dropdown-link>
                                    <x-dropdown-link :href="route('operations.clients.index')" class="rounded-xl py-2 px-3 text-xs font-bold text-on-surface hover:bg-primary/5 hover:text-primary">Clients</x-dropdown-link>
                                    <x-dropdown-link :href="route('operations.projects.index')" class="rounded-xl py-2 px-3 text-xs font-bold text-on-surface hover:bg-primary/5 hover:text-primary">Projects</x-dropdown-link>
                                </div>
                                @endif
                            </x-slot>
                        </x-dropdown>
                        @endif

                        {{-- Performance Group --}}
                        @if(($hasPerformance || $hasRecruitment) && Auth::user()->hasAnyRole([RoleConstants::TADMIN, RoleConstants::TMANAGER]))
                        <x-dropdown align="left" width="w-64" contentClasses="bg-surface/90 backdrop-blur-xl border border-outline-variant/10 p-2 shadow-2xl shadow-primary/5 rounded-2xl">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border-none text-[11px] font-bold uppercase tracking-wider h-10 rounded-lg {{ request()->routeIs('performance.*', 'recruitment.*') ? 'bg-primary/5 text-primary' : 'text-on-surface-variant/80 hover:bg-surface-container-low hover:text-on-surface' }} transition ease-in-out duration-150">
                                    <div class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[16px]">trending_up</span>
                                        Growth
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                @if($hasPerformance)
                                <x-dropdown-link :href="route('performance.dashboard')" class="rounded-xl flex items-center gap-3 py-2.5 px-3 hover:bg-primary/5 transition-colors group">
                                    <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-[16px]">speed</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-on-surface">Performance</span>
                                        <span class="text-[10px] text-on-surface-variant/60 font-medium">Appraisals & OKRs</span>
                                    </div>
                                </x-dropdown-link>
                                @endif
                                @if($hasRecruitment)
                                <x-dropdown-link :href="route('recruitment.dashboard')" class="rounded-xl flex items-center gap-3 py-2.5 px-3 hover:bg-primary/5 transition-colors group">
                                    <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-[16px]">work</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-on-surface">Recruitment</span>
                                        <span class="text-[10px] text-on-surface-variant/60 font-medium">Hiring pipeline</span>
                                    </div>
                                </x-dropdown-link>
                                @endif
                            </x-slot>
                        </x-dropdown>
                        @endif

                        @if($hasReports && Auth::user()->hasAnyRole([RoleConstants::TADMIN, RoleConstants::TMANAGER]))
                        <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" class="text-[11px] font-bold uppercase tracking-wider px-3 h-10 rounded-lg hover:bg-surface-container-low transition-colors">
                            {{ __('Reports') }}
                        </x-nav-link>
                        @endif
                    @endif

                    @if(!saas_tenant() && Auth::user()->hasAnyRole([RoleConstants::SADMIN, RoleConstants::SMANAGER, RoleConstants::SSTAFF]))
                        @if(Auth::user()->hasAnyRole([RoleConstants::SADMIN, RoleConstants::SMANAGER]))
                        <x-nav-link :href="route('admin.tenants.index')" :active="request()->routeIs('admin.tenants.*')" class="text-[11px] font-bold uppercase tracking-wider px-3 h-10 rounded-lg hover:bg-surface-container-low transition-colors">
                            {{ __('Tenants') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.plans.index')" :active="request()->routeIs('admin.plans.*')" class="text-[11px] font-bold uppercase tracking-wider px-3 h-10 rounded-lg hover:bg-surface-container-low transition-colors">
                            {{ __('Plans') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.roles.index')" :active="request()->routeIs('admin.roles.*')" class="text-[11px] font-bold uppercase tracking-wider px-3 h-10 rounded-lg hover:bg-surface-container-low transition-colors">
                            {{ __('Roles') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.modules.index')" :active="request()->routeIs('admin.modules.*')" class="text-[11px] font-bold uppercase tracking-wider px-3 h-10 rounded-lg hover:bg-surface-container-low transition-colors">
                            {{ __('Modules') }}
                        </x-nav-link>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden lg:flex lg:items-center lg:ms-6 gap-3">
                @if(saas_tenant())
                {{-- My Space (Self Service) Quick Button --}}
                <x-dropdown align="right" width="w-64" contentClasses="bg-surface/90 backdrop-blur-xl border border-outline-variant/10 p-2 shadow-2xl shadow-primary/5 rounded-2xl">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center justify-center w-10 h-10 border border-outline-variant/15 rounded-xl text-on-surface-variant bg-surface-container-low hover:bg-primary/5 hover:text-primary focus:outline-none transition ease-in-out duration-150">
                            <span class="material-symbols-outlined text-[20px]">person</span>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="px-3 py-2 text-[10px] font-black tracking-widest uppercase text-base-content/40">Self Service</div>
                        <x-dropdown-link :href="route('attendance.kiosk')" class="rounded-xl flex items-center gap-3 py-2 px-3 hover:bg-primary/5 transition-colors group">
                            <span class="material-symbols-outlined text-[18px] text-on-surface-variant group-hover:text-primary">timer</span>
                            <span class="text-xs font-bold text-on-surface">Daily Attendance</span>
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('leave.requests.index')" class="rounded-xl flex items-center gap-3 py-2 px-3 hover:bg-primary/5 transition-colors group">
                            <span class="material-symbols-outlined text-[18px] text-on-surface-variant group-hover:text-primary">event_busy</span>
                            <span class="text-xs font-bold text-on-surface">My Leaves</span>
                        </x-dropdown-link>
                        <div class="border-t border-outline-variant/10 my-1 mx-2"></div>
                        <x-dropdown-link :href="route('profile.edit')" class="rounded-xl flex items-center gap-3 py-2 px-3 hover:bg-primary/5 transition-colors group">
                            <span class="material-symbols-outlined text-[18px] text-on-surface-variant group-hover:text-primary">manage_accounts</span>
                            <span class="text-xs font-bold text-on-surface">My Profile</span>
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
                @endif

                <x-dropdown align="right" width="w-56" contentClasses="bg-surface/90 backdrop-blur-xl border border-outline-variant/10 p-2 shadow-2xl shadow-primary/5 rounded-2xl">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center pl-3 pr-2 py-1.5 border border-outline-variant/15 text-xs leading-4 font-bold rounded-xl text-on-surface bg-surface hover:bg-surface-container-low focus:outline-none transition ease-in-out duration-150 shadow-sm">
                            <div class="w-6 h-6 rounded-md bg-primary/10 text-primary flex items-center justify-center mr-2 text-[10px] uppercase">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="mr-1">{{ Auth::user()->name }}</div>
                            <span class="material-symbols-outlined text-[16px] text-on-surface-variant">expand_more</span>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-outline-variant/10 mb-1">
                            <div class="text-xs font-bold">{{ Auth::user()->name }}</div>
                            <div class="text-[10px] text-on-surface-variant mt-0.5 truncate">{{ Auth::user()->email }}</div>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')" class="rounded-xl font-bold text-xs font-label py-2.5 hover:bg-primary/5 hover:text-primary">
                            {{ __('Account Settings') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();" class="rounded-xl font-bold text-xs font-label text-error hover:bg-error/10 hover:text-error py-2.5">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center lg:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-on-surface-variant hover:bg-surface-container-high focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden bg-surface/90 backdrop-blur-xl border-t border-outline-variant/10 absolute w-full shadow-2xl">
        <div class="pt-2 pb-3 space-y-1 p-4">
            <x-responsive-nav-link :href="route($dashboardRoute)" :active="request()->routeIs($dashboardRoute)" class="font-bold uppercase tracking-widest font-label rounded-xl">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(saas_tenant() && app(\App\SaaS\Modules\ModuleManager::class)->tenantHasAccess('hr', saas_tenant('id')))
                <div class="px-4 py-2 mt-2 text-[10px] font-black tracking-widest uppercase text-primary bg-primary/5 rounded-lg">Workforce</div>
                <x-responsive-nav-link :href="route('hr.employees.index')" :active="request()->routeIs('hr.employees.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2 rounded-xl">
                    <span class="material-symbols-outlined text-[18px]">group</span>
                    {{ __('Employees') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('hr.departments.index')" :active="request()->routeIs('hr.departments.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2 rounded-xl">
                    <span class="material-symbols-outlined text-[18px]">hub</span>
                    {{ __('Departments') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('hr.designations.index')" :active="request()->routeIs('hr.designations.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2 rounded-xl">
                    <span class="material-symbols-outlined text-[18px]">work</span>
                    {{ __('Designations') }}
                </x-responsive-nav-link>
                
                <div class="px-4 py-2 mt-2 text-[10px] font-black tracking-widest uppercase text-primary bg-primary/5 rounded-lg">Operations</div>
                @if(app(\App\SaaS\Modules\ModuleManager::class)->tenantHasAccess('attendance', saas_tenant('id')))
                    <x-responsive-nav-link :href="route('attendance.index')" :active="request()->routeIs('attendance.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2 rounded-xl">
                        <span class="material-symbols-outlined text-[18px]">event_available</span>
                        {{ __('Attendance') }}
                    </x-responsive-nav-link>
                @endif
                @if(app(\App\SaaS\Modules\ModuleManager::class)->tenantHasAccess('leave', saas_tenant('id')))
                    <x-responsive-nav-link :href="route('leave.requests.index')" :active="request()->routeIs('leave.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2 rounded-xl">
                        <span class="material-symbols-outlined text-[18px]">event_busy</span>
                        {{ __('Leaves') }}
                    </x-responsive-nav-link>
                @endif
                @if(app(\App\SaaS\Modules\ModuleManager::class)->tenantHasAccess('payroll', saas_tenant('id')))
                    <x-responsive-nav-link :href="route('payroll.index')" :active="request()->routeIs('payroll.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2 rounded-xl">
                        <span class="material-symbols-outlined text-[18px]">payments</span>
                        {{ __('Payroll') }}
                    </x-responsive-nav-link>
                @endif
                @if(app(\App\SaaS\Modules\ModuleManager::class)->tenantHasAccess('performance', saas_tenant('id')))
                    <div class="px-4 py-2 mt-2 text-[10px] font-black tracking-widest uppercase text-primary bg-primary/5 rounded-lg">Growth</div>
                    <x-responsive-nav-link :href="route('performance.dashboard')" :active="request()->routeIs('performance.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2 rounded-xl">
                        <span class="material-symbols-outlined text-[18px]">trending_up</span>
                        {{ __('Performance') }}
                    </x-responsive-nav-link>
                @endif
                @if(app(\App\SaaS\Modules\ModuleManager::class)->tenantHasAccess('reports', saas_tenant('id')))
                    <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2 rounded-xl">
                        <span class="material-symbols-outlined text-[18px]">insert_chart</span>
                        {{ __('Reports') }}
                    </x-responsive-nav-link>
                @endif
                @if(app(\App\SaaS\Modules\ModuleManager::class)->tenantHasAccess('recruitment', saas_tenant('id')))
                    <x-responsive-nav-link :href="route('recruitment.dashboard')" :active="request()->routeIs('recruitment.dashboard', 'recruitment.job_postings.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2 rounded-xl">
                        <span class="material-symbols-outlined text-[18px]">work</span>
                        {{ __('Jobs') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('recruitment.applications.index')" :active="request()->routeIs('recruitment.applications.*', 'recruitment.interviews.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2 rounded-xl">
                        <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                        {{ __('Applications') }}
                    </x-responsive-nav-link>
                @endif
            @endif

            @if(!saas_tenant() && Auth::user()->hasRole(RoleConstants::SADMIN))
                <x-responsive-nav-link :href="route('admin.tenants.index')" :active="request()->routeIs('admin.tenants.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2 rounded-xl">
                    <span class="material-symbols-outlined text-[18px]">domain</span>
                    {{ __('Tenants') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.plans.index')" :active="request()->routeIs('admin.plans.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2 rounded-xl">
                    <span class="material-symbols-outlined text-[18px]">payments</span>
                    {{ __('Plans') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.roles.index')" :active="request()->routeIs('admin.roles.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2 rounded-xl">
                    <span class="material-symbols-outlined text-[18px]">security</span>
                    {{ __('Roles') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.modules.index')" :active="request()->routeIs('admin.modules.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2 rounded-xl">
                    <span class="material-symbols-outlined text-[18px]">widgets</span>
                    {{ __('Modules') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-4 border-t border-outline-variant/15 bg-surface-container-lowest p-4">
            <div class="px-4">
                <div class="font-bold text-base text-on-surface font-headline">{{ Auth::user()->name }}</div>
                <div class="font-medium text-xs text-on-surface-variant mt-0.5">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-4 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="font-bold uppercase tracking-widest font-label rounded-xl">
                    {{ __('Profile Settings') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();" class="font-bold uppercase tracking-widest font-label text-error hover:bg-error/10 hover:text-error rounded-xl">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
