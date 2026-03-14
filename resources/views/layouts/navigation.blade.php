@php
    $dashboardRoute = 'dashboard';
    if (tenant()) {
        $dashboardRoute = 'tenant.dashboard';
    } elseif (Auth::user()->hasRole('super_admin')) {
        $dashboardRoute = 'super-admin.dashboard';
    }
@endphp

<nav x-data="{ open: false }" class="bg-surface-container-lowest border-b border-outline-variant/15 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between h-20">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route($dashboardRoute) }}" class="text-2xl font-black tracking-tight font-headline text-primary">
                        {{ config('app.name', 'HRMS Solutions') }}
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route($dashboardRoute)" :active="request()->routeIs($dashboardRoute)" class="text-sm font-bold uppercase tracking-widest font-label">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(tenant() && app(\App\SaaS\Modules\ModuleManager::class)->tenantHasAccess('hr', tenant('id')))
                        <x-nav-link href="/hr/employees" :active="request()->is('hr/employees*')" class="text-sm font-bold uppercase tracking-widest font-label">
                            {{ __('Employees') }}
                        </x-nav-link>
                        <x-nav-link href="/hr/departments" :active="request()->is('hr/departments*')" class="text-sm font-bold uppercase tracking-widest font-label">
                            {{ __('Departments') }}
                        </x-nav-link>
                    @endif

                    @if(!tenant() && Auth::user()->hasRole('super_admin'))
                        <x-nav-link href="/tenants" :active="request()->is('tenants*')" class="text-sm font-bold uppercase tracking-widest font-label">
                            {{ __('Tenants') }}
                        </x-nav-link>
                        <x-nav-link href="/plans" :active="request()->is('plans*')" class="text-sm font-bold uppercase tracking-widest font-label">
                            {{ __('Plans') }}
                        </x-nav-link>
                        <x-nav-link href="/roles" :active="request()->is('roles*')" class="text-sm font-bold uppercase tracking-widest font-label">
                            {{ __('Roles') }}
                        </x-nav-link>
                        <x-nav-link href="/modules" :active="request()->is('modules*')" class="text-sm font-bold uppercase tracking-widest font-label">
                            {{ __('Modules') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-outline-variant/15 text-sm leading-4 font-bold rounded-xl text-on-surface bg-surface-container-low hover:bg-surface-container-high focus:outline-none transition ease-in-out duration-150 premium-shadow">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="font-bold text-xs uppercase tracking-widest font-label">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();" class="font-bold text-xs uppercase tracking-widest font-label text-error">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
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
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-surface-container-lowest border-t border-outline-variant/15">
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

            @if(!tenant() && Auth::user()->hasRole('super_admin'))
                <x-responsive-nav-link href="/tenants" :active="request()->is('tenants*')" class="font-bold uppercase tracking-widest font-label">
                    {{ __('Tenants') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="/plans" :active="request()->is('plans*')" class="font-bold uppercase tracking-widest font-label">
                    {{ __('Plans') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="/roles" :active="request()->is('roles*')" class="font-bold uppercase tracking-widest font-label">
                    {{ __('Roles') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="/modules" :active="request()->is('modules*')" class="font-bold uppercase tracking-widest font-label">
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
                <x-responsive-nav-link :href="route('profile.edit')" class="font-bold uppercase tracking-widest font-label">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();" class="font-bold uppercase tracking-widest font-label text-error">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
