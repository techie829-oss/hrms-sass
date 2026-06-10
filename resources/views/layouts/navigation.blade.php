@php
    use App\Core\Constants\RoleConstants;
    $dashboardRoute = 'dashboard';
    if (saas_tenant()) {
        $dashboardRoute = 'tenant.dashboard';
    } else {
        $dashboardRoute = 'super-admin.dashboard';
    }
@endphp

<nav x-data="{ mobileMenuOpen: false }" class="bg-surface/80 backdrop-blur-xl border-b border-outline-variant/10 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = true" class="lg:hidden mr-3 inline-flex items-center justify-center p-2 rounded-xl text-on-surface-variant hover:bg-surface-container-high focus:outline-none transition duration-150 ease-in-out">
                    <span class="material-symbols-outlined">menu</span>
                </button>

                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route($dashboardRoute) }}" class="text-xl font-bold tracking-tight text-primary flex items-center gap-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary/20 to-primary/5 rounded-xl flex items-center justify-center text-primary border border-primary/20 shadow-sm">
                            <span class="material-symbols-outlined text-[24px]">view_cozy</span>
                        </div>
                        <span class="hidden sm:block text-on-surface">{{ config('app.name', 'HRMS') }}</span>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
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
                        <x-dropdown align="left" width="w-64" contentClasses="bg-surface/95 backdrop-blur-2xl border border-outline-variant/10 p-2 shadow-2xl shadow-primary/5 rounded-2xl">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border-none text-[11px] font-bold uppercase tracking-wider h-10 rounded-lg {{ request()->routeIs('hr.*') ? 'bg-primary text-on-primary' : 'text-on-surface-variant/80 hover:bg-surface-container-low hover:text-on-surface' }} transition ease-in-out duration-150">
                                    <div class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[16px]">group</span>
                                        Workforce
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <div class="px-3 py-2 text-[10px] font-black tracking-widest uppercase text-on-surface-variant/50">Core HR</div>
                                <x-dropdown-link :href="route('hr.employees.index')" class="rounded-xl flex items-center gap-3 py-2.5 px-3 hover:bg-primary/5 transition-colors group">
                                    <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center group-hover:bg-primary/10 group-hover:text-primary shadow-sm transition-colors">
                                        <span class="material-symbols-outlined text-[16px]">badge</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-on-surface group-hover:text-primary transition-colors">Employees</span>
                                        <span class="text-[10px] text-on-surface-variant/60 font-medium">Manage team directory</span>
                                    </div>
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('hr.departments.index')" class="rounded-xl flex items-center gap-3 py-2.5 px-3 hover:bg-primary/5 transition-colors group mt-1">
                                    <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center group-hover:bg-primary/10 group-hover:text-primary shadow-sm transition-colors">
                                        <span class="material-symbols-outlined text-[16px]">hub</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-on-surface group-hover:text-primary transition-colors">Departments</span>
                                        <span class="text-[10px] text-on-surface-variant/60 font-medium">Organizational structure</span>
                                    </div>
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('hr.designations.index')" class="rounded-xl flex items-center gap-3 py-2.5 px-3 hover:bg-primary/5 transition-colors group mt-1">
                                    <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center group-hover:bg-primary/10 group-hover:text-primary shadow-sm transition-colors">
                                        <span class="material-symbols-outlined text-[16px]">work</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-on-surface group-hover:text-primary transition-colors">Designations</span>
                                        <span class="text-[10px] text-on-surface-variant/60 font-medium">Roles and titles</span>
                                    </div>
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                        @endif

                        {{-- Operations Group --}}
                        @if(($hasAttendance || $hasLeave || $hasPayroll || $hasOperations) && Auth::user()->hasAnyRole([RoleConstants::TADMIN, RoleConstants::TMANAGER]))
                        <x-dropdown align="left" width="w-72" contentClasses="bg-surface/95 backdrop-blur-2xl border border-outline-variant/10 p-2 shadow-2xl shadow-primary/5 rounded-2xl">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border-none text-[11px] font-bold uppercase tracking-wider h-10 rounded-lg {{ request()->routeIs('attendance.*', 'leave.*', 'payroll.*', 'operations.*') ? 'bg-primary text-on-primary' : 'text-on-surface-variant/80 hover:bg-surface-container-low hover:text-on-surface' }} transition ease-in-out duration-150">
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
                                        <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center group-hover:bg-primary/10 group-hover:text-primary shadow-sm transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">event_available</span>
                                        </div>
                                        <span class="text-xs font-bold text-on-surface group-hover:text-primary transition-colors">Attendance</span>
                                    </x-dropdown-link>
                                    @endif
                                    @if($hasLeave)
                                    <x-dropdown-link :href="route('leave.requests.index')" class="rounded-xl flex items-center gap-3 py-2 px-3 hover:bg-primary/5 transition-colors group">
                                        <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center group-hover:bg-primary/10 group-hover:text-primary shadow-sm transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">event_busy</span>
                                        </div>
                                        <span class="text-xs font-bold text-on-surface group-hover:text-primary transition-colors">Leaves</span>
                                    </x-dropdown-link>
                                    @endif
                                    @if($hasPayroll)
                                    <x-dropdown-link :href="route('payroll.index')" class="rounded-xl flex items-center gap-3 py-2 px-3 hover:bg-primary/5 transition-colors group">
                                        <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center group-hover:bg-primary/10 group-hover:text-primary shadow-sm transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">payments</span>
                                        </div>
                                        <span class="text-xs font-bold text-on-surface group-hover:text-primary transition-colors">Payroll</span>
                                    </x-dropdown-link>
                                    @endif
                                </div>
                                @if($hasOperations)
                                <div class="px-3 py-2 mt-2 text-[10px] font-black tracking-widest uppercase text-on-surface-variant/50 border-t border-outline-variant/10">Business</div>
                                <div class="grid grid-cols-2 gap-1">
                                    <x-dropdown-link :href="route('operations.leads.index')" class="rounded-xl py-2 px-3 text-xs font-bold text-on-surface hover:bg-primary/5 hover:text-primary transition-colors">Leads</x-dropdown-link>
                                    <x-dropdown-link :href="route('operations.contacts.index')" class="rounded-xl py-2 px-3 text-xs font-bold text-on-surface hover:bg-primary/5 hover:text-primary transition-colors">Contacts</x-dropdown-link>
                                    <x-dropdown-link :href="route('operations.clients.index')" class="rounded-xl py-2 px-3 text-xs font-bold text-on-surface hover:bg-primary/5 hover:text-primary transition-colors">Clients</x-dropdown-link>
                                    <x-dropdown-link :href="route('operations.projects.index')" class="rounded-xl py-2 px-3 text-xs font-bold text-on-surface hover:bg-primary/5 hover:text-primary transition-colors">Projects</x-dropdown-link>
                                </div>
                                @endif
                            </x-slot>
                        </x-dropdown>
                        @endif

                        {{-- Performance Group --}}
                        @if(($hasPerformance || $hasRecruitment) && Auth::user()->hasAnyRole([RoleConstants::TADMIN, RoleConstants::TMANAGER]))
                        <x-dropdown align="left" width="w-64" contentClasses="bg-surface/95 backdrop-blur-2xl border border-outline-variant/10 p-2 shadow-2xl shadow-primary/5 rounded-2xl">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border-none text-[11px] font-bold uppercase tracking-wider h-10 rounded-lg {{ request()->routeIs('performance.*', 'recruitment.*') ? 'bg-primary text-on-primary' : 'text-on-surface-variant/80 hover:bg-surface-container-low hover:text-on-surface' }} transition ease-in-out duration-150">
                                    <div class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[16px]">trending_up</span>
                                        Growth
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                @if($hasPerformance)
                                <x-dropdown-link :href="route('performance.dashboard')" class="rounded-xl flex items-center gap-3 py-2.5 px-3 hover:bg-primary/5 transition-colors group">
                                    <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center group-hover:bg-primary/10 group-hover:text-primary shadow-sm transition-colors">
                                        <span class="material-symbols-outlined text-[16px]">speed</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-on-surface group-hover:text-primary transition-colors">Performance</span>
                                        <span class="text-[10px] text-on-surface-variant/60 font-medium">Appraisals & OKRs</span>
                                    </div>
                                </x-dropdown-link>
                                @endif
                                @if($hasRecruitment)
                                <x-dropdown-link :href="route('recruitment.dashboard')" class="rounded-xl flex items-center gap-3 py-2.5 px-3 hover:bg-primary/5 transition-colors group mt-1">
                                    <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center group-hover:bg-primary/10 group-hover:text-primary shadow-sm transition-colors">
                                        <span class="material-symbols-outlined text-[16px]">work</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-on-surface group-hover:text-primary transition-colors">Recruitment</span>
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
                <x-dropdown align="right" width="w-64" contentClasses="bg-surface/95 backdrop-blur-2xl border border-outline-variant/10 p-2 shadow-2xl shadow-primary/5 rounded-2xl">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center justify-center w-10 h-10 border border-outline-variant/15 rounded-xl text-on-surface-variant bg-surface-container-lowest shadow-sm hover:shadow-md hover:border-primary/30 hover:bg-primary/5 hover:text-primary focus:outline-none transition ease-in-out duration-300">
                            <span class="material-symbols-outlined text-[20px]">apps</span>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="px-3 py-2 text-[10px] font-black tracking-widest uppercase text-on-surface-variant/50">Self Service</div>
                        <x-dropdown-link :href="route('attendance.kiosk')" class="rounded-xl flex items-center gap-3 py-2 px-3 hover:bg-primary/5 transition-colors group">
                            <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center group-hover:bg-primary/10 group-hover:text-primary shadow-sm transition-colors">
                                <span class="material-symbols-outlined text-[16px]">timer</span>
                            </div>
                            <span class="text-xs font-bold text-on-surface group-hover:text-primary transition-colors">Daily Attendance</span>
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('leave.requests.index')" class="rounded-xl flex items-center gap-3 py-2 px-3 hover:bg-primary/5 transition-colors group mt-1">
                            <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center group-hover:bg-primary/10 group-hover:text-primary shadow-sm transition-colors">
                                <span class="material-symbols-outlined text-[16px]">event_busy</span>
                            </div>
                            <span class="text-xs font-bold text-on-surface group-hover:text-primary transition-colors">My Leaves</span>
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
                @endif

                <x-dropdown align="right" width="w-56" contentClasses="bg-surface/95 backdrop-blur-2xl border border-outline-variant/10 p-2 shadow-2xl shadow-primary/5 rounded-2xl">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center pl-2 pr-2 py-1.5 border border-outline-variant/15 text-xs leading-4 font-bold rounded-xl text-on-surface bg-surface-container-lowest hover:bg-surface-container-low hover:border-primary/20 hover:shadow-md focus:outline-none transition ease-in-out duration-300 shadow-sm">
                            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-primary to-primary/80 text-on-primary flex items-center justify-center mr-2 text-[10px] uppercase shadow-sm font-bold tracking-widest">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="mr-1 hidden sm:block">{{ Auth::user()->name }}</div>
                            <span class="material-symbols-outlined text-[16px] text-on-surface-variant">expand_more</span>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-outline-variant/10 mb-1 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-primary/80 text-on-primary flex items-center justify-center text-sm shadow-sm font-bold tracking-widest uppercase">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="flex flex-col">
                                <div class="text-xs font-bold">{{ Auth::user()->name }}</div>
                                <div class="text-[10px] text-on-surface-variant mt-0.5 truncate max-w-[130px]">{{ Auth::user()->email }}</div>
                            </div>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')" class="rounded-xl font-bold text-xs font-label py-2.5 hover:bg-primary/5 hover:text-primary flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">manage_accounts</span>
                            {{ __('Account Settings') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();" class="rounded-xl font-bold text-xs font-label text-error hover:bg-error/10 hover:text-error py-2.5 flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px]">logout</span>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>

    <!-- Off-canvas Mobile Menu (Slide-out Drawer) -->
    <div x-show="mobileMenuOpen" class="fixed inset-0 z-[100] lg:hidden" style="display: none;">
        <!-- Backdrop -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-surface/60 backdrop-blur-sm" 
             @click="mobileMenuOpen = false"></div>

        <!-- Drawer -->
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="fixed inset-y-0 left-0 max-w-xs w-full bg-surface shadow-2xl flex flex-col z-[101]">
            
            <div class="px-6 py-5 border-b border-outline-variant/10 flex items-center justify-between">
                <a href="{{ route($dashboardRoute) }}" class="text-xl font-bold tracking-tight text-primary flex items-center gap-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary/20 to-primary/5 rounded-xl flex items-center justify-center text-primary border border-primary/20 shadow-sm">
                        <span class="material-symbols-outlined text-[24px]">view_cozy</span>
                    </div>
                    <span class="text-on-surface">{{ config('app.name', 'HRMS') }}</span>
                </a>
                <button @click="mobileMenuOpen = false" class="text-on-surface-variant hover:text-on-surface bg-surface-container-low hover:bg-surface-container-high rounded-full w-8 h-8 flex items-center justify-center transition-colors">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-4 py-6 space-y-6">
                <!-- User Profile Summary -->
                <div class="flex items-center gap-4 px-2">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary to-primary/80 text-on-primary flex items-center justify-center text-lg shadow-md font-bold tracking-widest uppercase">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-bold text-sm text-on-surface">{{ Auth::user()->name }}</div>
                        <div class="text-[11px] text-on-surface-variant mt-0.5">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="space-y-1">
                    <x-responsive-nav-link :href="route($dashboardRoute)" :active="request()->routeIs($dashboardRoute)" class="font-bold uppercase tracking-widest font-label flex items-center gap-3 rounded-xl px-4 py-3">
                        <span class="material-symbols-outlined text-[20px]">dashboard</span>
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>

                    @if(saas_tenant())
                        @if(app(\App\SaaS\Modules\ModuleManager::class)->tenantHasAccess('hr', saas_tenant('id')))
                            <div class="px-4 py-2 mt-4 text-[10px] font-black tracking-widest uppercase text-primary/70">Workforce</div>
                            <x-responsive-nav-link :href="route('hr.employees.index')" :active="request()->routeIs('hr.employees.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-3 rounded-xl px-4 py-3">
                                <span class="material-symbols-outlined text-[20px]">badge</span>
                                {{ __('Employees') }}
                            </x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('hr.departments.index')" :active="request()->routeIs('hr.departments.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-3 rounded-xl px-4 py-3">
                                <span class="material-symbols-outlined text-[20px]">hub</span>
                                {{ __('Departments') }}
                            </x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('hr.designations.index')" :active="request()->routeIs('hr.designations.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-3 rounded-xl px-4 py-3">
                                <span class="material-symbols-outlined text-[20px]">work</span>
                                {{ __('Designations') }}
                            </x-responsive-nav-link>
                        @endif
                        
                        <div class="px-4 py-2 mt-4 text-[10px] font-black tracking-widest uppercase text-primary/70">Operations</div>
                        @if(app(\App\SaaS\Modules\ModuleManager::class)->tenantHasAccess('attendance', saas_tenant('id')))
                            <x-responsive-nav-link :href="route('attendance.index')" :active="request()->routeIs('attendance.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-3 rounded-xl px-4 py-3">
                                <span class="material-symbols-outlined text-[20px]">event_available</span>
                                {{ __('Attendance') }}
                            </x-responsive-nav-link>
                        @endif
                        @if(app(\App\SaaS\Modules\ModuleManager::class)->tenantHasAccess('leave', saas_tenant('id')))
                            <x-responsive-nav-link :href="route('leave.requests.index')" :active="request()->routeIs('leave.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-3 rounded-xl px-4 py-3">
                                <span class="material-symbols-outlined text-[20px]">event_busy</span>
                                {{ __('Leaves') }}
                            </x-responsive-nav-link>
                        @endif
                        @if(app(\App\SaaS\Modules\ModuleManager::class)->tenantHasAccess('payroll', saas_tenant('id')))
                            <x-responsive-nav-link :href="route('payroll.index')" :active="request()->routeIs('payroll.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-3 rounded-xl px-4 py-3">
                                <span class="material-symbols-outlined text-[20px]">payments</span>
                                {{ __('Payroll') }}
                            </x-responsive-nav-link>
                        @endif
                        
                        @if(app(\App\SaaS\Modules\ModuleManager::class)->tenantHasAccess('performance', saas_tenant('id')) || app(\App\SaaS\Modules\ModuleManager::class)->tenantHasAccess('recruitment', saas_tenant('id')))
                            <div class="px-4 py-2 mt-4 text-[10px] font-black tracking-widest uppercase text-primary/70">Growth</div>
                            @if(app(\App\SaaS\Modules\ModuleManager::class)->tenantHasAccess('performance', saas_tenant('id')))
                            <x-responsive-nav-link :href="route('performance.dashboard')" :active="request()->routeIs('performance.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-3 rounded-xl px-4 py-3">
                                <span class="material-symbols-outlined text-[20px]">speed</span>
                                {{ __('Performance') }}
                            </x-responsive-nav-link>
                            @endif
                            @if(app(\App\SaaS\Modules\ModuleManager::class)->tenantHasAccess('recruitment', saas_tenant('id')))
                            <x-responsive-nav-link :href="route('recruitment.dashboard')" :active="request()->routeIs('recruitment.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-3 rounded-xl px-4 py-3">
                                <span class="material-symbols-outlined text-[20px]">how_to_reg</span>
                                {{ __('Recruitment') }}
                            </x-responsive-nav-link>
                            @endif
                        @endif

                        @if(app(\App\SaaS\Modules\ModuleManager::class)->tenantHasAccess('reports', saas_tenant('id')))
                            <div class="px-4 py-2 mt-4 text-[10px] font-black tracking-widest uppercase text-primary/70">Analytics</div>
                            <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-3 rounded-xl px-4 py-3">
                                <span class="material-symbols-outlined text-[20px]">insert_chart</span>
                                {{ __('Reports') }}
                            </x-responsive-nav-link>
                        @endif
                    @endif

                    @if(!saas_tenant() && Auth::user()->hasRole(RoleConstants::SADMIN))
                        <div class="px-4 py-2 mt-4 text-[10px] font-black tracking-widest uppercase text-primary/70">SaaS Platform</div>
                        <x-responsive-nav-link :href="route('admin.tenants.index')" :active="request()->routeIs('admin.tenants.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-3 rounded-xl px-4 py-3">
                            <span class="material-symbols-outlined text-[20px]">domain</span>
                            {{ __('Tenants') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.plans.index')" :active="request()->routeIs('admin.plans.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-3 rounded-xl px-4 py-3">
                            <span class="material-symbols-outlined text-[20px]">payments</span>
                            {{ __('Plans') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.roles.index')" :active="request()->routeIs('admin.roles.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-3 rounded-xl px-4 py-3">
                            <span class="material-symbols-outlined text-[20px]">security</span>
                            {{ __('Roles') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.modules.index')" :active="request()->routeIs('admin.modules.*')" class="font-bold uppercase tracking-widest font-label flex items-center gap-3 rounded-xl px-4 py-3">
                            <span class="material-symbols-outlined text-[20px]">widgets</span>
                            {{ __('Modules') }}
                        </x-responsive-nav-link>
                    @endif
                </div>
            </div>

            <!-- Drawer Footer (Settings & Logout) -->
            <div class="border-t border-outline-variant/10 p-4 bg-surface-container-lowest">
                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')" class="font-bold uppercase tracking-widest font-label flex items-center gap-3 rounded-xl px-4 py-3">
                        <span class="material-symbols-outlined text-[20px]">manage_accounts</span>
                        {{ __('Profile Settings') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();" class="font-bold uppercase tracking-widest font-label text-error hover:bg-error/10 hover:text-error flex items-center gap-3 rounded-xl px-4 py-3">
                            <span class="material-symbols-outlined text-[20px]">logout</span>
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
