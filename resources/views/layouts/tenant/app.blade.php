<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HRMS Solutions') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')

        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Dynamic Theme Colors -->
        {!! app(\App\Services\ColorPaletteService::class)->generateInlineCSS() !!}

        <style>
            .bg-primary-50 { background-color: var(--color-primary-50) !important; }
            .bg-primary-100 { background-color: var(--color-primary-100) !important; }
            .bg-primary-500 { background-color: var(--color-primary-500) !important; }
            .bg-primary-600 { background-color: var(--color-primary-600) !important; }
            .bg-primary-700 { background-color: var(--color-primary-700) !important; }
            .bg-primary-900 { background-color: var(--color-primary-900) !important; }

            .text-primary-50 { color: var(--color-primary-50) !important; }
            .text-primary-100 { color: var(--color-primary-100) !important; }
            .text-primary-500 { color: var(--color-primary-500) !important; }
            .text-primary-600 { color: var(--color-primary-600) !important; }
            .text-primary-700 { color: var(--color-primary-700) !important; }
            .text-primary-900 { color: var(--color-primary-900) !important; }

            .border-primary-500 { border-color: var(--color-primary-500) !important; }
            .border-primary-600 { border-color: var(--color-primary-600) !important; }

            .focus\:ring-primary-500:focus { --tw-ring-color: var(--color-primary-500) !important; }
            .focus\:border-primary-500:focus { border-color: var(--color-primary-500) !important; }
            .hover\:bg-primary-600:hover { background-color: var(--color-primary-600) !important; }
            .hover\:bg-primary-700:hover { background-color: var(--color-primary-700) !important; }

            .bg-secondary-50 { background-color: var(--color-secondary-50) !important; }
            .bg-secondary-100 { background-color: var(--color-secondary-100) !important; }
            .text-secondary-600 { color: var(--color-secondary-600) !important; }
            .text-secondary-700 { color: var(--color-secondary-700) !important; }
            .text-secondary-900 { color: var(--color-secondary-900) !important; }
            .bg-accent-50 { background-color: var(--color-accent-50) !important; }
            .text-accent-600 { color: var(--color-accent-600) !important; }

            ::selection {
                background-color: var(--color-primary-600) !important;
                color: #ffffff !important;
            }
            ::-moz-selection {
                background-color: var(--color-primary-600) !important;
                color: #ffffff !important;
            }

            /* Custom Select styling for clean, consistent inputs */
            .content-area select {
                appearance: none;
                -webkit-appearance: none;
                -moz-appearance: none;
                background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
                background-repeat: no-repeat;
                background-position: right 0.75rem center;
                background-size: 0.9rem;
                padding-right: 2.25rem;
            }

            /* Layout Architecture (Sklops Responsive Compact Design) */
            body {
                overflow-x: hidden;
                background-color: #f8fafc; /* Slate 50 */
                font-family: 'Inter', system-ui, -apple-system, sans-serif;
            }

            .sidebar {
                height: 100vh;
                overflow-y: auto;
                background-color: #ffffff; /* Clean white Sklops ERP sidebar */
                border-right: 1px solid #e2e8f0;
                transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
                position: fixed;
                top: 0;
                left: 0;
                z-index: 50;
                width: 15.5rem;
            }

            .sidebar.collapsed {
                transform: translateX(-100%);
            }

            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(15, 23, 42, 0.45);
                backdrop-filter: blur(2px);
                z-index: 40;
                display: none;
            }

            .sidebar-overlay.show {
                display: block;
            }

            .content-area {
                min-height: 100vh;
                margin-left: 15.5rem;
                transition: margin-left 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .content-area.sidebar-collapsed {
                margin-left: 0;
            }

            .top-header {
                position: sticky;
                top: 0;
                z-index: 30;
                background: rgba(255, 255, 255, 0.95);
                border-bottom: 1px solid #e2e8f0;
                backdrop-filter: blur(12px);
            }

            /* Responsive Drawer for Tablet & Mobile (up to 1024px) */
            @media (max-width: 1024px) {
                .sidebar {
                    transform: translateX(-100%);
                }

                .sidebar.show {
                    transform: translateX(0);
                }

                .content-area {
                    margin-left: 0;
                }
            }

            .card {
                background: white;
                border-radius: 1rem; /* rounded-2xl feel */
                border: 1px solid #f1f5f9;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            }

            .stat-card {
                background: white;
                border-radius: 1rem;
                border: 1px solid #f1f5f9;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
                transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .stat-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
            }

            /* Tablet responsive */
            @media (min-width: 769px) and (max-width: 1024px) {
                .sidebar {
                    width: 14rem;
                }

                .content-area {
                    margin-left: 14rem;
                }
            }
        </style>
    </head>
    <body class="font-body text-gray-900 antialiased bg-gray-50">
        <!-- Sidebar Overlay for Mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            @include('layouts.tenant.sidebar')
        </div>

        <!-- Content Area -->
        <div class="content-area" id="contentArea">
            <!-- Header -->
            @include('layouts.header')

            <!-- Page Heading (Optional, inside content area) -->
            @isset($header)
                <div class="bg-white border-b border-gray-200">
                    <div class="w-full max-w-[1600px] mx-auto py-3.5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </div>
            @endisset

            <!-- Page Content -->
            <main class="py-4 sm:py-6 px-4 sm:px-6 lg:px-8">
                <div class="w-full max-w-[1600px] mx-auto">
                    @if (session('success'))
                        <div class="alert alert-success shadow-sm mb-6 rounded-xl border-none font-bold text-sm">
                            <span class="material-symbols-outlined">check_circle</span>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-error shadow-sm mb-6 rounded-xl border-none font-bold text-sm">
                            <span class="material-symbols-outlined">error</span>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </main>

            <!-- Mobile Bottom Navigation Bar (Sklops Mobile Reference UI) -->
            <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 flex items-center justify-around h-16 shadow-lg px-2">
                <a href="{{ route('tenant.dashboard') }}" class="flex flex-col items-center justify-center text-xs {{ request()->routeIs('tenant.dashboard') ? 'text-blue-600 font-bold' : 'text-gray-500 hover:text-gray-900' }}">
                    <span class="material-symbols-outlined text-[24px]">home</span>
                    <span class="text-[10px] mt-0.5">Home</span>
                </a>
                <a href="{{ route('hr.employees.index') }}" class="flex flex-col items-center justify-center text-xs {{ request()->routeIs('hr.employees.*') ? 'text-blue-600 font-bold' : 'text-gray-500 hover:text-gray-900' }}">
                    <span class="material-symbols-outlined text-[24px]">groups</span>
                    <span class="text-[10px] mt-0.5">People</span>
                </a>
                <button onclick="toggleSidebar()" class="flex flex-col items-center justify-center text-xs text-gray-500 hover:text-gray-900 focus:outline-none">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                    <span class="text-[10px] mt-0.5">Menu</span>
                </button>
            </nav>
        </div>

        @stack('scripts')

        <script>
            // Universal sidebar toggle function (Sklops Responsive Design up to 1024px)
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                const contentArea = document.getElementById('contentArea');

                // Check if we're on mobile/tablet (<= 1024px) or large desktop (> 1024px)
                if (window.innerWidth <= 1024) {
                    // Mobile & Tablet drawer behavior
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                } else {
                    // Desktop behavior
                    sidebar.classList.toggle('collapsed');
                    contentArea.classList.toggle('sidebar-collapsed');
                }
            }

            // Close sidebar when clicking overlay (mobile/tablet only)
            document.getElementById('sidebarOverlay').addEventListener('click', function() {
                if (window.innerWidth <= 1024) {
                    toggleSidebar();
                }
            });

            // Close sidebar on mobile/tablet when clicking nav links
            document.querySelectorAll('.sidebar nav a').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 1024) {
                        toggleSidebar();
                    }
                });
            });

            // Handle window resize cleanly across breakpoints
            window.addEventListener('resize', function() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                const contentArea = document.getElementById('contentArea');

                if (window.innerWidth > 1024) {
                    // Desktop: remove mobile drawer classes
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                } else {
                    // Mobile/Tablet: remove desktop collapse classes
                    sidebar.classList.remove('collapsed');
                    contentArea.classList.remove('sidebar-collapsed');
                }
            });

            // User dropdown functionality
            function toggleUserDropdown() {
                const dropdown = document.getElementById('userDropdown');
                if (dropdown) {
                    dropdown.classList.toggle('hidden');
                }
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const userMenuButton = document.getElementById('userMenuButton');
                const userDropdown = document.getElementById('userDropdown');

                if (userMenuButton && userDropdown && !userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                    userDropdown.classList.add('hidden');
                }
            });

            // Initialize sidebar state based on screen size
            document.addEventListener('DOMContentLoaded', function() {
                if (window.innerWidth <= 768) {
                    // Mobile: sidebar should be hidden by default
                    const sidebar = document.getElementById('sidebar');
                    if(sidebar) sidebar.classList.remove('show');
                }

                // Add click event to user menu button
                const userBtn = document.getElementById('userMenuButton');
                if(userBtn) {
                    userBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        toggleUserDropdown();
                    });
                }
            });
        </script>
    </body>
</html>
