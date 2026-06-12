<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HRMS Solutions') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased bg-slate-50 selection:bg-indigo-500 selection:text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center p-6 sm:pt-0">
            <div class="mb-8 flex flex-col items-center">
                <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg mb-4">
                    <span class="material-symbols-outlined text-[36px]">business_center</span>
                </div>
                <a href="/" class="text-3xl font-bold tracking-tight text-slate-900">
                    {{ config('app.name', 'HRMS Solutions') }}
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-2 px-8 py-10 bg-white shadow-sm overflow-hidden rounded-2xl border border-slate-200">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
