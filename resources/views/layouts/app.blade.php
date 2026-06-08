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
    </head>
    <body class="font-body text-on-surface antialiased bg-surface selection:bg-primary selection:text-white">
        <!-- Elevated Glass Background Pattern -->
        <div class="fixed inset-0 z-[-1] bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-primary/5 via-surface to-surface"></div>

        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-surface/60 backdrop-blur-md border-b border-outline-variant/10 sticky top-16 z-40">
                    <div class="max-w-7xl mx-auto py-5 px-6">
                        <div class="text-xl font-bold text-on-surface">
                            {{ $header }}
                        </div>
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="py-12">
                <div class="max-w-7xl mx-auto px-6">
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
        </div>
        @stack('scripts')
    </body>
</html>
