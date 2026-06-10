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
    </head>
    <body class="font-body text-on-surface antialiased bg-surface selection:bg-primary selection:text-white">
        <!-- Elevated Glass Background Pattern -->
        <div class="fixed inset-0 z-[-1] bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-primary/10 via-surface to-surface"></div>
        
        <div class="min-h-screen flex flex-col sm:justify-center items-center p-6 sm:pt-0">
            <div class="mb-6 flex flex-col items-center">
                <div class="w-16 h-16 bg-gradient-to-br from-primary to-primary-dim rounded-2xl flex items-center justify-center text-white shadow-lg mb-4">
                    <span class="material-symbols-outlined text-[36px]">view_cozy</span>
                </div>
                <a href="/" class="text-3xl font-black tracking-tight font-headline text-on-surface">
                    {{ config('app.name', 'HRMS Solutions') }}
                </a>
            </div>

            <div class="w-full sm:max-w-[400px] mt-2 px-8 py-10 bg-surface/80 backdrop-blur-2xl shadow-2xl overflow-hidden rounded-[2.5rem] border border-outline-variant/20 relative">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
