@extends('layouts.marketing')

@section('title', 'HRMS for IT Companies & Tech Teams | SolidrixHR')
@section('description', 'SolidrixHR provides an agile, robust HRMS for fast-paced IT companies. Manage remote teams, complex shifts, timesheets, and performance.')

@section('content')
<div class="bg-vibrant-hero py-20 lg:py-32 overflow-hidden relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-6">
                Built for Agile <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">IT Companies</span>
            </h1>
            <p class="text-xl text-slate-600 mb-10">
                Manage your tech talent, track remote work attendance, handle project timesheets, and streamline appraisals all in one modern platform.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="/contact" class="px-8 py-3 rounded-lg text-white font-bold bg-blue-600 hover:bg-blue-700 shadow-md transition-all">Start Free Trial</a>
                <a href="/pricing" class="px-8 py-3 rounded-lg text-slate-700 font-bold bg-white border border-slate-200 hover:bg-slate-50 transition-all">View Pricing</a>
            </div>
        </div>
    </div>
</div>

<div class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 mb-6">Solve Tech Team Challenges</h2>
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-slate-900 mb-2">Remote Work & Biometrics</h4>
                            <p class="text-slate-600">Integrate with biometric systems or allow geo-fenced mobile attendance for your hybrid tech workforce.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-slate-900 mb-2">Project Timesheets</h4>
                            <p class="text-slate-600">Track billable hours, assign employees to different tech projects, and generate profitability reports.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-slate-900 mb-2">Quarterly Appraisals</h4>
                            <p class="text-slate-600">Run 360-degree performance reviews and track OKRs to retain your top engineering talent.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100">
                <div class="aspect-video bg-white rounded-xl shadow-sm border border-slate-100 flex items-center justify-center">
                    <span class="text-slate-400 font-medium">Dashboard Preview</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
