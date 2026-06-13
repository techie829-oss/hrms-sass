@extends('layouts.marketing')

@section('title', 'Features | All HR Modules | Solidrix HRMS')

@section('content')

{{-- Hero --}}
<section class="pt-28 pb-16 bg-white text-center">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6 tracking-tight">
            Everything You Need to <span class="text-blue-600">Run HR</span>
        </h1>
        <p class="text-xl text-gray-500 max-w-2xl mx-auto mb-10">
            7 fully integrated modules — built to eliminate spreadsheets, automate payroll, and give your team a single source of truth.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/contact" class="px-8 py-4 rounded-xl text-base font-bold text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm">
                Book a Demo
            </a>
            <a href="/pricing" class="px-8 py-4 rounded-xl text-base font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                See Pricing
            </a>
        </div>
    </div>
</section>

{{-- Modules Grid --}}
<section class="pb-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- Core HR --}}
            <a href="/employee-management-system"
                class="group bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">Core HR</h3>
                    <span class="text-xs font-semibold px-2.5 py-1 bg-blue-50 text-blue-600 rounded-full">Module 1</span>
                </div>
                <p class="text-gray-500 text-sm leading-relaxed mb-5">Employee profiles, departments, designations, org chart, and secure document storage — your people database.</p>
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-1.5 h-1.5 rounded-full bg-blue-400 shrink-0"></span>Employee Profiles & Org Chart</div>
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-1.5 h-1.5 rounded-full bg-blue-400 shrink-0"></span>Department & Designation Management</div>
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-1.5 h-1.5 rounded-full bg-blue-400 shrink-0"></span>Document Vault</div>
                </div>
            </a>

            {{-- Attendance --}}
            <a href="/attendance-management-software"
                class="group bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-amber-500 transition-colors">Attendance</h3>
                    <span class="text-xs font-semibold px-2.5 py-1 bg-amber-50 text-amber-600 rounded-full">Module 2</span>
                </div>
                <p class="text-gray-500 text-sm leading-relaxed mb-5">Shift assignments, web check-in/out, daily summaries, overtime tracking, and policy enforcement — automated.</p>
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0"></span>Web & Mobile Clock-In</div>
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0"></span>Shift & Roster Management</div>
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0"></span>Overtime & Late Deductions</div>
                </div>
            </a>

            {{-- Payroll --}}
            <a href="/payroll-software"
                class="group bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-green-600 transition-colors">Payroll</h3>
                    <span class="text-xs font-semibold px-2.5 py-1 bg-green-50 text-green-600 rounded-full">Module 3</span>
                </div>
                <p class="text-gray-500 text-sm leading-relaxed mb-5">Salary structures, pay components, automated payroll runs, digital payslips, and statutory compliance.</p>
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-1.5 h-1.5 rounded-full bg-green-400 shrink-0"></span>Automated Payroll Engine</div>
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-1.5 h-1.5 rounded-full bg-green-400 shrink-0"></span>Digital Payslips</div>
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-1.5 h-1.5 rounded-full bg-green-400 shrink-0"></span>PF, ESI & TDS Compliance</div>
                </div>
            </a>

            {{-- Leave --}}
            <a href="/leave-management-system"
                class="group bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-purple-600 transition-colors">Leave Management</h3>
                    <span class="text-xs font-semibold px-2.5 py-1 bg-purple-50 text-purple-600 rounded-full">Module 4</span>
                </div>
                <p class="text-gray-500 text-sm leading-relaxed mb-5">Leave balances, custom policies, holiday calendars, approval workflows, and comp-off tracking.</p>
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-1.5 h-1.5 rounded-full bg-purple-400 shrink-0"></span>Custom Leave Policies</div>
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-1.5 h-1.5 rounded-full bg-purple-400 shrink-0"></span>Multi-Level Approvals</div>
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-1.5 h-1.5 rounded-full bg-purple-400 shrink-0"></span>Holiday & Comp-Off Management</div>
                </div>
            </a>

            {{-- Recruitment --}}
            <a href="/recruitment-software"
                class="group bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">Recruitment</h3>
                    <span class="text-xs font-semibold px-2.5 py-1 bg-blue-50 text-blue-600 rounded-full">Module 5</span>
                </div>
                <p class="text-gray-500 text-sm leading-relaxed mb-5">Job postings, applicant tracking pipeline, interview scheduling, and offer letter generation.</p>
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-1.5 h-1.5 rounded-full bg-blue-400 shrink-0"></span>Job Board & ATS Pipeline</div>
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-1.5 h-1.5 rounded-full bg-blue-400 shrink-0"></span>Interview Scheduling</div>
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-1.5 h-1.5 rounded-full bg-blue-400 shrink-0"></span>Offer Letter Generation</div>
                </div>
            </a>

            {{-- Performance --}}
            <a href="/performance-management-software"
                class="group bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-amber-500 transition-colors">Performance</h3>
                    <span class="text-xs font-semibold px-2.5 py-1 bg-amber-50 text-amber-600 rounded-full">Module 6</span>
                </div>
                <p class="text-gray-500 text-sm leading-relaxed mb-5">Goal setting, KPI tracking, 360° feedback, and structured appraisal cycles with manager reviews.</p>
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0"></span>Goal & KPI Tracking</div>
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0"></span>360° Feedback Cycles</div>
                    <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0"></span>Appraisal Management</div>
                </div>
            </a>

            {{-- Operations --}}
            <a href="/project-management-software"
                class="group bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 md:col-span-2 lg:col-span-3">
                <div class="flex flex-col lg:flex-row lg:items-center gap-6">
                    <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-green-600 transition-colors">Operations & Projects</h3>
                            <span class="text-xs font-semibold px-2.5 py-1 bg-green-50 text-green-600 rounded-full">Module 7</span>
                        </div>
                        <p class="text-gray-500 text-sm leading-relaxed">Manage clients, leads, projects, tasks, and timesheets — bridge the gap between your HR and business operations in one platform.</p>
                    </div>
                    <div class="grid grid-cols-3 gap-4 lg:w-96 shrink-0">
                        <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-1.5 h-1.5 rounded-full bg-green-400 shrink-0"></span>Client & Lead Management</div>
                        <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-1.5 h-1.5 rounded-full bg-green-400 shrink-0"></span>Task & Project Tracking</div>
                        <div class="flex items-center gap-2 text-xs text-gray-500"><span class="w-1.5 h-1.5 rounded-full bg-green-400 shrink-0"></span>Timesheet & Billing</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-24 bg-gray-900">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-6">All 7 Modules. One Platform. One Price.</h2>
        <p class="text-xl text-gray-400 mb-10">No per-module pricing. Get everything when you subscribe.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/contact" class="px-8 py-4 rounded-xl text-base font-bold text-white bg-blue-600 hover:bg-blue-700 transition">
                Book a Demo
            </a>
            <a href="/pricing" class="px-8 py-4 rounded-xl text-base font-bold text-white bg-white/10 hover:bg-white/20 border border-white/20 transition">
                View Pricing
            </a>
        </div>
    </div>
</section>

@endsection
