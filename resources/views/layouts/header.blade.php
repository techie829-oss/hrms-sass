<header class="min-h-16 bg-white border-b border-gray-200 px-4 sm:px-6 flex items-center justify-between sticky top-0 z-20 py-2.5 sm:py-0">
    <div class="flex items-center flex-1 min-w-0 mr-4">
        <!-- Toggle button for all screen sizes (Mobile drawer / Desktop toggle) -->
        <button
            class="mr-3.5 text-gray-600 hover:text-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-opacity-50 rounded-lg p-1.5 hover:bg-gray-100 transition-colors shrink-0"
            onclick="toggleSidebar()"
            title="Toggle Navigation Sidebar">
            <span class="material-symbols-outlined text-[22px]">menu</span>
        </button>

        <div class="flex-1 min-w-0">
            @isset($header)
                {{ $header }}
            @else
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-gray-900 tracking-tight leading-snug truncate">@yield('page-title', 'Dashboard')</h2>
                    <p class="text-xs text-gray-500 hidden sm:block truncate">@yield('page-description', 'Welcome to your HRMS dashboard')</p>
                </div>
            @endisset
        </div>
    </div>

    <!-- User Profile Dropdown -->
    <div class="relative shrink-0">
        <button id="userMenuButton"
            class="flex items-center space-x-3 p-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-opacity-50">
                    
                    <div class="hidden sm:block text-right">
                        <div class="text-sm font-semibold text-gray-900">
                            {{ auth()->user()->name ?? 'User' }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ auth()->user()->email ?? '' }}
                        </div>
                    </div>

                    <!-- User Avatar -->
                    <div class="relative">
                        <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center shadow-sm">
                            <span class="text-primary-700 font-semibold text-sm">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </span>
                        </div>
                        <!-- Online indicator -->
                        <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>

                    <!-- Dropdown Arrow -->
                    <span class="material-symbols-outlined text-[20px] text-gray-400">expand_more</span>
                </button>

                <!-- Dropdown Menu -->
                <div id="userDropdown"
                    class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50 hidden">
                    
                    <!-- User Info Header (Mobile mainly) -->
                    <div class="px-4 py-3 border-b border-gray-100 sm:hidden">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center shadow-sm">
                                <span class="text-primary-700 font-semibold text-sm">
                                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">{{ auth()->user()->name ?? 'User' }}</div>
                                <div class="text-xs text-gray-500">{{ auth()->user()->email ?? '' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Items -->
                    <div class="py-2">
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors">
                            <span class="material-symbols-outlined text-[20px] mr-3">person</span>
                            {{ __('Profile') }}
                        </a>

                        <div class="border-t border-gray-100 my-1"></div>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <span class="material-symbols-outlined text-[20px] mr-3">logout</span>
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
</header>
