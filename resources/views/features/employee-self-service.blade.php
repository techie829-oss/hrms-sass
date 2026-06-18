@extends('layouts.marketing')

@section('title', 'Employee Self Service Portal | SolidrixHR')
@section('description', 'Empower your employees with a self-service portal to manage leaves, view payslips, and update personal information autonomously.')

@section('content')
<div class="bg-vibrant-hero py-20 lg:py-32 overflow-hidden relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-6">
                Empower Your Team with <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Employee Self Service</span>
            </h1>
            <p class="text-xl text-slate-600 mb-10">
                Give your employees the autonomy they want. Let them manage their profiles, download payslips, request leaves, and track attendance seamlessly.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="/contact" class="px-8 py-3 rounded-lg text-white font-bold bg-blue-600 hover:bg-blue-700 shadow-md transition-all">Start Free Trial</a>
                <a href="#features" class="px-8 py-3 rounded-lg text-slate-700 font-bold bg-white border border-slate-200 hover:bg-slate-50 transition-all">Explore Features</a>
            </div>
        </div>
    </div>
</div>

<div id="features" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <!-- Feature 1 -->
            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 hover:shadow-lg transition-all">
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Payslip Access</h3>
                <p class="text-slate-600">Employees can easily view and download their monthly payslips and tax documents without waiting for HR.</p>
            </div>
            <!-- Feature 2 -->
            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 hover:shadow-lg transition-all">
                <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Leave Requests</h3>
                <p class="text-slate-600">Apply for time off, check leave balances, and view holiday calendars directly from the dashboard.</p>
            </div>
            <!-- Feature 3 -->
            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 hover:shadow-lg transition-all">
                <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Profile Updates</h3>
                <p class="text-slate-600">Keep emergency contacts, addresses, and personal details up-to-date with self-service profile management.</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-blue-600 py-16">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold text-white mb-6">Reduce HR Workload Today</h2>
        <p class="text-blue-100 mb-8 text-lg">Stop answering emails about leave balances and payslips. Give your employees the tools they need.</p>
        <a href="/contact" class="inline-block px-8 py-3 rounded-lg text-blue-600 font-bold bg-white hover:bg-gray-50 shadow-lg transition-all">Book a Free Demo</a>
    </div>
</div>
@endsection
