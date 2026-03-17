<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HRMS Solutions') }} — Careers</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-body text-on-surface antialiased bg-surface-container-lowest">
        <!-- Top navbar -->
        <header class="border-b border-outline-variant/15 bg-white/80 backdrop-blur-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
                <a href="/" class="text-xl font-black tracking-tight font-headline text-primary">
                    {{ config('app.name', 'HRMS Solutions') }}
                </a>
                <nav class="flex items-center gap-6 text-sm font-semibold text-on-surface-variant">
                    <a href="{{ route('tenant.careers.index') }}" class="hover:text-primary transition-colors">All Jobs</a>
                </nav>
            </div>
        </header>

        <!-- Full width page content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="border-t border-outline-variant/15 mt-20 py-10 text-center text-sm text-on-surface-variant font-medium">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </footer>
    </body>
</html>
