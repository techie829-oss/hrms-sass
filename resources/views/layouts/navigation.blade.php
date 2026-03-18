@php
    $dashboardRoute = 'dashboard';
    if (tenant()) {
        $dashboardRoute = 'tenant.dashboard';
    } else {
        $dashboardRoute = 'super-admin.dashboard';
    }
@endphp

<nav x-data="{ open: false }" class="bg-surface-container-lowest border-b border-outline-variant/15 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route($dashboardRoute) }}" class="text-xl font-bold tracking-tight text-primary">
                        {{ config('app.name', 'HRMS') }}
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 lg:-my-px lg:ms-10 lg:flex items-center">
                    <x-nav-link :href="route($dashboardRoute)" :active="request()->routeIs($dashboardRoute)" class="text-[11px] font-bold uppercase tracking-wider px-3">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(tenant())
                        @php
                            $moduleManager = app(\App\SaaS\Modules\ModuleManager::class);
                            $tenantId = tenant('id');
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
                        @if($hasHr && Auth::user()->hasAnyRole(['tadmin', 'tmanager']))
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border-none text-[11px] font-bold uppercase tracking-wider text-on-surface-variant/70 hover:text-primary transition ease-in-out duration-150">
                                    <div class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[18px]">group</span>
                                        Workforce
                                    </div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" /></svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('hr.employees.index')" class="text-[10px] font-bold uppercase tracking-wider">{{ __('Employees') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('hr.departments.index')" class="text-[10px] font-bold uppercase tracking-wider">{{ __('Departments') }}</x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                        @endif

                        {{-- Operations Group --}}
                        @if(($hasAttendance || $hasLeave || $hasPayroll) && Auth::user()->hasAnyRole(['tadmin', 'tmanager']))
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border-none text-[11px] font-bold uppercase tracking-wider text-on-surface-variant/70 hover:text-primary transition ease-in-out duration-150">
                                    <div class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[18px]">settings_applications</span>
                                        Operations
                                    </div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" /></svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                @if($hasAttendance)<x-dropdown-link :href="route('attendance.index')" class="text-[10px] font-bold uppercase tracking-wider">{{ __('Attendance') }}</x-dropdown-link>@endif
                                @if($hasLeave)<x-dropdown-link :href="route('leave.requests.index')" class="text-[10px] font-bold uppercase tracking-wider">{{ __('Leaves') }}</x-dropdown-link>@endif
                                @if($hasPayroll)<x-dropdown-link :href="route('payroll.index')" class="text-[10px] font-bold uppercase tracking-wider">{{ __('Payroll') }}</x-dropdown-link>@endif
                                @if($hasOperations)
                                    <div class="border-t border-outline-variant/10 my-1"></div>
                                    <x-dropdown-link :href="route('operations.leads.index')" class="text-[10px] font-bold uppercase tracking-wider">{{ __('Leads') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('operations.contacts.index')" class="text-[10px] font-bold uppercase tracking-wider">{{ __('Contacts') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('operations.clients.index')" class="text-[10px] font-bold uppercase tracking-wider">{{ __('Clients') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('operations.projects.index')" class="text-[10px] font-bold uppercase tracking-wider">{{ __('Projects') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('operations.tasks.index')" class="text-[10px] font-bold uppercase tracking-wider">{{ __('Tasks') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('operations.timesheets.index')" class="text-[10px] font-bold uppercase tracking-wider">{{ __('Timesheets') }}</x-dropdown-link>
                                @endif
                            </x-slot>
                        </x-dropdown>
                        @endif

                        {{-- Performance & Jobs Group --}}
                        @if(($hasPerformance || $hasRecruitment) && Auth::user()->hasAnyRole(['tadmin', 'tmanager']))
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border-none text-[11px] font-bold uppercase tracking-wider text-on-surface-variant/70 hover:text-primary transition ease-in-out duration-150">
                                    <div class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[18px]">bolt</span>
                                        Performance
                                    </div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" /></svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                @if($hasPerformance)<x-dropdown-link :href="route('performance.dashboard')" class="text-[10px] font-bold uppercase tracking-wider">{{ __('Overview') }}</x-dropdown-link>@endif
                                @if($hasRecruitment)
                                    <x-dropdown-link :href="route('recruitment.dashboard')" class="text-[10px] font-bold uppercase tracking-wider">{{ __('Job Postings') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('recruitment.applications.index')" class="text-[10px] font-bold uppercase tracking-wider">{{ __('Applications') }}</x-dropdown-link>
                                @endif
                            </x-slot>
                        </x-dropdown>
                        @endif

                        @if($hasReports && Auth::user()->hasAnyRole(['tadmin', 'tmanager']))
                        <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" class="text-[11px] font-bold uppercase tracking-wider px-3">
                            {{ __('Reports') }}
                        </x-nav-link>
                        @endif

                        {{-- My Space (Self Service) --}}
                        <x-dropdown align="left" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border-none text-[11px] font-bold uppercase tracking-wider text-primary font-bold transition ease-in-out duration-150">
                                    <div class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[18px]">person</span>
                                        My Space
                                    </div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" /></svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('attendance.kiosk')" class="text-[10px] font-bold uppercase tracking-wider">{{ __('Daily Attendance') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('leave.requests.index')" class="text-[10px] font-bold uppercase tracking-wider">{{ __('My Leaves') }}</x-dropdown-link>
                                <x-dropdown-link :href="route('tenant.profile.edit')" class="text-[10px] font-bold uppercase tracking-wider">{{ __('My Profile') }}</x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    @endif

                    @if(!tenant() && Auth::user()->hasAnyRole(['super_admin', 'sadmin', 'smanager', 'sstaff']))
                        @if(Auth::user()->hasAnyRole(['super_admin', 'sadmin']))
                        <x-nav-link :href="route('admin.tenants.index')" :active="request()->routeIs('admin.tenants.*')" class="text-[11px] font-bold uppercase tracking-wider px-3">
                            {{ __('Tenants') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.plans.index')" :active="request()->routeIs('admin.plans.*')" class="text-[11px] font-bold uppercase tracking-wider px-3">
                            {{ __('Plans') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.roles.index')" :active="request()->routeIs('admin.roles.*')" class="text-[11px] font-bold uppercase tracking-wider px-3">
                            {{ __('Roles') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.modules.index')" :active="request()->routeIs('admin.modules.*')" class="text-[11px] font-bold uppercase tracking-wider px-3">
                            {{ __('Modules') }}
                        </x-nav-link>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden lg:flex lg:items-center lg:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-outline-variant/15 text-xs leading-4 font-bold rounded-lg text-on-surface bg-surface-container-low hover:bg-surface-container-high focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route((tenant() ? 'tenant.' : '') . 'profile.edit')" class="font-bold text-xs uppercase tracking-widest font-label">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route((tenant() ? 'tenant.' : '') . 'logout') }}">
                            @csrf

                            <x-dropdown-link :href="route((tenant() ? 'tenant.' : '') . 'logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();" class="font-bold text-xs uppercase tracking-widest font-label text-error">
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
    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden bg-surface-container-lowest border-t border-outline-variant/15">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route($dashboardRoute)" :active="request()->routeIs($dashboardRoute)" class="font-bold uppercase tracking-widest font-label">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="/about" class="font-bold uppercase tracking-widest font-label">
                {{ __('About') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="/contact" class="font-bold uppercase tracking-widest font-label">
                {{ __('Contact') }}
            </x-responsive-nav-link>

            @if(tenant() && app(\App\SaaS\Modules\ModuleManager::class)->tenantHasAccess('hr', tenant('id')))
                <div class="border-t border-outline-variant/5 mt-2 pt-2">
                    <x-responsive-nav-link :href="route('hr.employees.index')" :active="request()->routeIs('hr.employees.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">group</span>
                        {{ __('Employees') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('hr.departments.index')" :active="request()->routeIs('hr.departments.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">hub</span>
                        {{ __('Departments') }}
                    </x-responsive-nav-link>
                    @if(app(\App\SaaS\Modules\ModuleManager::class)->tenantHasAccess('attendance', tenant('id')))
                        <x-responsive-nav-link :href="route('attendance.index')" :active="request()->routeIs('attendance.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">event_available</span>
                            {{ __('Attendance') }}
                        </x-responsive-nav-link>
                    @endif

                    @if(app(\App\SaaS\Modules\ModuleManager::class)->tenantHasAccess('leave', tenant('id')))
                        <x-responsive-nav-link :href="route('leave.requests.index')" :active="request()->routeIs('leave.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">event_busy</span>
                            {{ __('Leaves') }}
                        </x-responsive-nav-link>
                    @endif

                    @if(app(\App\SaaS\Modules\ModuleManager::class)->tenantHasAccess('payroll', tenant('id')))
                        <x-responsive-nav-link :href="route('payroll.index')" :active="request()->routeIs('payroll.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">payments</span>
                            {{ __('Payroll') }}
                        </x-responsive-nav-link>
                    @endif

                    @if(app(\App\SaaS\Modules\ModuleManager::class)->tenantHasAccess('performance', tenant('id')))
                        <x-responsive-nav-link :href="route('performance.dashboard')" :active="request()->routeIs('performance.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">trending_up</span>
                            {{ __('Performance') }}
                        </x-responsive-nav-link>
                    @endif

                    @if(app(\App\SaaS\Modules\ModuleManager::class)->tenantHasAccess('reports', tenant('id')))
                        <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">insert_chart</span>
                            {{ __('Reports') }}
                        </x-responsive-nav-link>
                    @endif

                    @if(app(\App\SaaS\Modules\ModuleManager::class)->tenantHasAccess('recruitment', tenant('id')))
                        <x-responsive-nav-link :href="route('recruitment.dashboard')" :active="request()->routeIs('recruitment.dashboard', 'recruitment.job_postings.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">work</span>
                            {{ __('Jobs') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('recruitment.applications.index')" :active="request()->routeIs('recruitment.applications.*', 'recruitment.interviews.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                            {{ __('Applications') }}
                        </x-responsive-nav-link>
                    @endif
                </div>
@endif

            @if(!tenant() && Auth::user()->hasRole('super_admin'))
                <x-responsive-nav-link :href="route('admin.tenants.index')" :active="request()->routeIs('admin.tenants.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">domain</span>
                    {{ __('Tenants') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.plans.index')" :active="request()->routeIs('admin.plans.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">payments</span>
                    {{ __('Plans') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.roles.index')" :active="request()->routeIs('admin.roles.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">security</span>
                    {{ __('Roles') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.modules.index')" :active="request()->routeIs('admin.modules.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">widgets</span>
                    {{ __('Modules') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-outline-variant/15">
            <div class="px-4">
                <div class="font-bold text-base text-on-surface font-headline">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-on-surface-variant">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route((tenant() ? 'tenant.' : '') . 'profile.edit')" class="font-bold uppercase tracking-widest font-label">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route((tenant() ? 'tenant.' : '') . 'logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route((tenant() ? 'tenant.' : '') . 'logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();" class="font-bold uppercase tracking-widest font-label text-error">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
