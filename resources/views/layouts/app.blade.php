<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HRMS Solutions') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')

        <!-- Static Theme Colors -->
        <style>
            :root {
                --color-primary-50: #eff6ff;
                --color-primary-100: #dbeafe;
                --color-primary-500: #3b82f6;
                --color-primary-600: #2563eb;
                --color-primary-700: #1d4ed8;
                --color-primary-900: #1e3a8a;
                --color-secondary-50: #f8fafc;
                --color-secondary-100: #f1f5f9;
                --color-secondary-500: #64748b;
                --color-secondary-600: #475569;
                --color-secondary-700: #334155;
                --color-secondary-900: #0f172a;
                --color-accent-50: #fef3c7;
                --color-accent-500: #f59e0b;
                --color-accent-600: #d97706;
                --color-accent-700: #b45309;
                --color-success: #10b981;
                --color-warning: #f59e0b;
                --color-error: #ef4444;
                --color-info: #3b82f6;
            }

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

            /* Layout Architecture (Sklops Responsive Compact Design) */
            body {
                overflow-x: hidden;
                background-color: #f8fafc; /* Slate 50 */
                font-family: 'Inter', system-ui, -apple-system, sans-serif;
            }

            .sidebar {
                height: 100vh;
                overflow-y: auto;
                background-color: #0f172a; /* Slate 900 for premium HRMS look */
                border-right: 1px solid #1e293b;
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
                background-color: rgba(15, 23, 42, 0.5);
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
                border-radius: 1rem;
                border: 1px solid #e2e8f0; /* slate-200 */
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
            }

            .stat-card {
                background: white;
                border-radius: 1rem;
                border: 1px solid #e2e8f0;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
                transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .stat-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
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
    <body class="font-body text-gray-900 antialiased bg-gray-50 selection:bg-primary-600 selection:text-white">
        <!-- Sidebar Overlay for Mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            @include('layouts.sidebar')
        </div>

        <!-- Content Area -->
        <div class="content-area" id="contentArea">
            <!-- Header -->
            @include('layouts.header')

            <!-- Page Content -->
            <main class="pt-4 pb-24 lg:py-6 px-4 sm:px-6 lg:px-8">
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
            <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-slate-200 grid grid-cols-3 h-16 shadow-[0_-4px_16px_rgba(0,0,0,0.06)]">
                <a href="{{ route('tenant.dashboard') }}" class="flex flex-col items-center justify-center gap-0.5 w-full h-full {{ request()->routeIs('tenant.dashboard') ? 'text-primary font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}">
                    <span class="material-symbols-outlined text-2xl">home</span>
                    <span class="text-[10px] leading-none">Home</span>
                </a>
                <a href="{{ route('hr.employees.index') }}" class="flex flex-col items-center justify-center gap-0.5 w-full h-full {{ request()->routeIs('hr.employees.*') ? 'text-primary font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }}">
                    <span class="material-symbols-outlined text-2xl">groups</span>
                    <span class="text-[10px] leading-none">People</span>
                </a>
                <button type="button" onclick="toggleSidebar()" class="flex flex-col items-center justify-center gap-0.5 w-full h-full text-slate-500 hover:text-slate-800 font-medium focus:outline-none">
                    <span class="material-symbols-outlined text-2xl">menu</span>
                    <span class="text-[10px] leading-none">Menu</span>
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

                // Check if we're on mobile/tablet (<= 1024px) or desktop (> 1024px)
                if (window.innerWidth <= 1024) {
                    // Mobile & Tablet behavior
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
                    // Desktop: remove mobile classes
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                } else {
                    // Mobile/Tablet: remove desktop classes
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
