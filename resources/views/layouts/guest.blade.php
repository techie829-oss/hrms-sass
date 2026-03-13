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
    <body class="font-body text-on-surface antialiased bg-hero-gradient">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="mb-8">
                <a href="/" class="text-3xl font-black tracking-tight font-headline text-primary">
                    {{ config('app.name', 'HRMS Solutions') }}
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-10 py-12 bg-surface-container-lowest premium-shadow overflow-hidden rounded-[2rem] border border-outline-variant/15">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
