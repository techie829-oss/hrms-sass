@extends('layouts.marketing')

@section('title', 'Solidrix - HRMS Software That Keeps Operations in One Place')

@section('content')

<!-- Hero Section -->
<section class="relative pt-24 pb-16 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-6xl font-bold tracking-tight text-gray-900 mb-6 max-w-4xl mx-auto">
            HRMS Software That Keeps HR Operations in One Place
        </h1>
        <p class="text-lg md:text-xl text-gray-600 mb-10 max-w-2xl mx-auto leading-relaxed">
            Attendance, payroll, leave management, recruitment, and employee records &mdash; without the spreadsheet chaos.
        </p>
        <div class="flex flex-col sm:flex-row justify-center items-center gap-4 mb-16">
            <a href="/contact" class="px-8 py-3 rounded-lg text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-sm w-full sm:w-auto">
                Book a Demo
            </a>
            <a href="/features" class="px-8 py-3 rounded-lg text-base font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 transition shadow-sm w-full sm:w-auto">
                View Features
            </a>
        </div>
        
        <!-- Hero Mockup Container -->
        <div class="relative max-w-5xl mx-auto rounded-xl shadow-2xl border border-gray-100 overflow-hidden bg-gray-50">
            <!-- Mac window frame -->
            <div class="flex items-center px-4 py-3 bg-white border-b border-gray-100">
                <div class="flex space-x-2">
                    <div class="w-3 h-3 rounded-full bg-red-400"></div>
                    <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                    <div class="w-3 h-3 rounded-full bg-green-400"></div>
                </div>
            </div>
            <!-- Image Drop-in -->
            <div class="aspect-[16/9] w-full bg-gray-100 flex items-center justify-center text-gray-400">
                <!-- Replace src with actual dashboard screenshot -->
                <img src="{{ asset('images/demo/dashboard.png') }}" alt="Solidrix Dashboard" class="w-full h-full object-cover object-top">
            </div>
        </div>
    </div>
</section>

<!-- Trust Section -->
<section class="py-12 border-y border-gray-100 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-center items-center gap-12 md:gap-24 text-gray-400 font-medium text-sm uppercase tracking-wider">
            <span>Verified 99.9% Uptime</span>
            <span>Secure Cloud Infrastructure</span>
            <span>Automated Payroll Processing</span>
            <span>GPS-Enabled Attendance</span>
        </div>
    </div>
</section>

<!-- Core Solutions (Bento Grid) -->
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Everything your team needs</h2>
            <p class="text-lg text-gray-600">No inflated claims. No unnecessary complexity. Just practical tools.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Attendance -->
            <a href="/attendance-management-software" class="block p-8 bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow group">
                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Attendance</h3>
                <p class="text-gray-600 text-sm leading-relaxed">Geofencing, biometric sync, and real-time tracking for distributed teams.</p>
            </a>

            <!-- Payroll -->
            <a href="/payroll-software" class="block p-8 bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow group">
                <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Payroll</h3>
                <p class="text-gray-600 text-sm leading-relaxed">Automated calculations, tax compliance, and one-click payslip generation.</p>
            </a>

            <!-- Leave -->
            <a href="/leave-management-system" class="block p-8 bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow group">
                <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Leave Management</h3>
                <p class="text-gray-600 text-sm leading-relaxed">Custom policies, multi-level approvals, and shared team calendars.</p>
            </a>

            <!-- Recruitment -->
            <a href="/recruitment-software" class="block p-8 bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow group">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Recruitment</h3>
                <p class="text-gray-600 text-sm leading-relaxed">Applicant tracking, interview scheduling, and seamless onboarding flows.</p>
            </a>

            <!-- Performance -->
            <a href="/performance-management-software" class="block p-8 bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow group">
                <div class="w-10 h-10 bg-rose-50 text-rose-600 rounded-lg flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Performance</h3>
                <p class="text-gray-600 text-sm leading-relaxed">Continuous feedback, goal setting, and objective performance reviews.</p>
            </a>

            <!-- Reports -->
            <a href="/features" class="block p-8 bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow group">
                <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Reports</h3>
                <p class="text-gray-600 text-sm leading-relaxed">Exportable data, compliance registers, and deep analytics across all modules.</p>
            </a>
        </div>
    </div>
</section>

<!-- See the platform in action (Interactive Demo) -->
<section class="py-24 bg-white" x-data="{ activeTab: 'dashboard' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">See the platform in action</h2>
            <p class="text-lg text-gray-600">A clean interface designed for actual human beings.</p>
        </div>

        <div class="flex justify-center space-x-2 sm:space-x-4 mb-12 overflow-x-auto pb-2">
            <button @click="activeTab = 'dashboard'" :class="{'bg-gray-900 text-white': activeTab === 'dashboard', 'bg-gray-100 text-gray-600 hover:bg-gray-200': activeTab !== 'dashboard'}" class="px-5 py-2.5 rounded-full text-sm font-medium transition whitespace-nowrap">
                Employee Dashboard
            </button>
            <button @click="activeTab = 'kiosk'" :class="{'bg-gray-900 text-white': activeTab === 'kiosk', 'bg-gray-100 text-gray-600 hover:bg-gray-200': activeTab !== 'kiosk'}" class="px-5 py-2.5 rounded-full text-sm font-medium transition whitespace-nowrap">
                Attendance Kiosk
            </button>
            <button @click="activeTab = 'ecosystem'" :class="{'bg-gray-900 text-white': activeTab === 'ecosystem', 'bg-gray-100 text-gray-600 hover:bg-gray-200': activeTab !== 'ecosystem'}" class="px-5 py-2.5 rounded-full text-sm font-medium transition whitespace-nowrap">
                Module Ecosystem
            </button>
        </div>

        <div class="max-w-5xl mx-auto rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <!-- Dashboard Image -->
            <div x-show="activeTab === 'dashboard'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="aspect-[16/9] w-full bg-gray-100 relative">
                <img src="{{ asset('images/demo/employees.png') }}" alt="Employee Dashboard" class="w-full h-full object-cover object-top">
            </div>
            
            <!-- Kiosk Image -->
            <div x-show="activeTab === 'kiosk'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="aspect-[16/9] w-full bg-gray-100 relative">
                <img src="{{ asset('images/demo/attendance.png') }}" alt="Attendance Kiosk" class="w-full h-full object-cover object-top">
            </div>

            <!-- Ecosystem Image -->
            <div x-show="activeTab === 'ecosystem'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="aspect-[16/9] w-full bg-gray-100 relative">
                <img src="{{ asset('images/demo/projects.png') }}" alt="Module Ecosystem" class="w-full h-full object-cover object-top">
            </div>
        </div>
    </div>
</section>

<!-- Final CTA -->
<section class="py-24 bg-gray-900 text-white text-center">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-5xl font-bold mb-6">Ready to simplify your HR operations?</h2>
        <p class="text-xl text-gray-400 mb-10">Join growing organizations that trust Solidrix for their daily workforce management.</p>
        <a href="/contact" class="inline-flex px-8 py-3 rounded-lg text-lg font-medium text-gray-900 bg-white hover:bg-gray-100 transition shadow-sm">
            Book a Demo
        </a>
    </div>
</section>

@endsection
