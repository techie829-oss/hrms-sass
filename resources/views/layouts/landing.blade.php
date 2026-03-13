<!DOCTYPE html>
<html class="scroll-smooth" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'HRMS Solutions'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface text-on-surface font-body selection:bg-primary-fixed selection:text-on-primary-fixed">
    <!-- TopNavBar -->
    <nav class="sticky top-0 z-50 glass-nav border-b border-outline-variant/15">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-12">
                <a href="/" class="text-2xl font-extrabold tracking-tight font-headline text-primary">
                    {{ config('app.name', 'HRMS Solutions') }}
                </a>
                <div class="hidden md:flex items-center gap-8">
                    <a class="text-sm font-medium text-on-surface hover:text-primary transition-colors" href="/">Home</a>
                    <a class="text-sm font-medium text-on-surface hover:text-primary transition-colors" href="/modules">Modules</a>
                    <a class="text-sm font-medium text-on-surface hover:text-primary transition-colors" href="/pricing">Pricing</a>
                    <a class="text-sm font-medium text-on-surface hover:text-primary transition-colors" href="/about">About</a>
                    <a class="text-sm font-medium text-on-surface hover:text-primary transition-colors" href="/contact">Contact</a>
                </div>
            </div>
            <div class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 text-sm font-semibold text-primary hover:bg-primary/5 transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-semibold text-primary hover:bg-primary/5 transition-colors">Login</a>
                        <a href="/contact" class="px-6 py-2.5 text-sm font-bold bg-primary text-on-primary rounded-xl hover:opacity-90 transition-all premium-shadow primary-gradient">Book a Demo</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-surface-container-low pt-24 pb-12 border-t border-outline-variant/15">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 mb-20">
                <div class="md:col-span-4">
                    <span class="text-2xl font-extrabold tracking-tight font-headline text-primary block mb-6">{{ config('app.name', 'HRMS Solutions') }}</span>
                    <p class="text-on-surface-variant text-sm leading-relaxed mb-8 max-w-xs">
                        Designing the future of organizational excellence through intentional architecture and human-centric design.
                    </p>
                    <div class="flex gap-4">
                        <a class="p-2 bg-surface-container text-on-surface rounded-lg hover:bg-primary hover:text-white transition-all" href="#"><span class="material-symbols-outlined text-sm">public</span></a>
                        <a class="p-2 bg-surface-container text-on-surface rounded-lg hover:bg-primary hover:text-white transition-all" href="#"><span class="material-symbols-outlined text-sm">alternate_email</span></a>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <h5 class="font-bold text-on-surface mb-6 uppercase tracking-widest text-[10px]">Company</h5>
                    <ul class="space-y-4">
                        <li><a class="text-sm text-on-surface-variant hover:text-primary transition-colors" href="/about">About</a></li>
                        <li><a class="text-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Careers</a></li>
                        <li><a class="text-sm text-on-surface-variant hover:text-primary transition-colors" href="/contact">Contact</a></li>
                    </ul>
                </div>
                <div class="md:col-span-2">
                    <h5 class="font-bold text-on-surface mb-6 uppercase tracking-widest text-[10px]">Product</h5>
                    <ul class="space-y-4">
                        <li><a class="text-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Features</a></li>
                        <li><a class="text-sm text-on-surface-variant hover:text-primary transition-colors" href="/pricing">Pricing</a></li>
                        <li><a class="text-sm text-on-surface-variant hover:text-primary transition-colors" href="/modules">Modules</a></li>
                    </ul>
                </div>
                <div class="md:col-span-2">
                    <h5 class="font-bold text-on-surface mb-6 uppercase tracking-widest text-[10px]">Legal</h5>
                    <ul class="space-y-4">
                        <li><a class="text-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Privacy Policy</a></li>
                        <li><a class="text-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="md:col-span-2">
                    <h5 class="font-bold text-on-surface mb-6 uppercase tracking-widest text-[10px]">Social</h5>
                    <ul class="space-y-4">
                        <li><a class="text-sm text-on-surface-variant hover:text-primary transition-colors" href="#">LinkedIn</a></li>
                        <li><a class="text-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Twitter</a></li>
                    </ul>
                </div>
            </div>
            <div class="pt-12 border-t border-outline-variant/15 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-[11px] font-medium text-outline">© {{ date('Y') }} {{ config('app.name', 'HRMS Solutions') }}. All rights reserved.</p>
                <div class="flex gap-8">
                    <span class="text-[11px] font-bold text-primary uppercase tracking-widest">Kinetic Horizon v2.0</span>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
