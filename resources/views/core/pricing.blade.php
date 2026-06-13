@extends('layouts.marketing')

@section('title', 'Pricing - Solidrix')

@section('content')
<section class="pt-24 pb-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">Simple, predictable pricing</h1>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto mb-16">
            Pay for the modules you need. Transparent per-employee pricing with no hidden implementation fees.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto text-left">
            
            <!-- Tier 1 -->
            <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm flex flex-col">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Core HR</h3>
                <p class="text-gray-500 text-sm mb-6">The essential tools for organizing employee data.</p>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-gray-900">$4</span>
                    <span class="text-gray-500">/user/month</span>
                </div>
                <ul class="space-y-4 mb-8 flex-1 text-sm text-gray-600">
                    <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Employee Directory</li>
                    <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Document Storage</li>
                    <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Leave Management</li>
                    <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Basic Reporting</li>
                </ul>
                <a href="/contact" class="block w-full py-3 px-4 bg-gray-50 hover:bg-gray-100 text-gray-900 border border-gray-200 text-center font-medium rounded-lg transition">Start Free Trial</a>
            </div>

            <!-- Tier 2 -->
            <div class="bg-gray-900 rounded-2xl border border-gray-800 p-8 shadow-xl flex flex-col relative transform md:-translate-y-4">
                <div class="absolute top-0 right-8 transform -translate-y-1/2">
                    <span class="bg-indigo-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">Most Popular</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Growth</h3>
                <p class="text-gray-400 text-sm mb-6">Complete automation for payroll and attendance.</p>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-white">$8</span>
                    <span class="text-gray-400">/user/month</span>
                </div>
                <ul class="space-y-4 mb-8 flex-1 text-sm text-gray-300">
                    <li class="flex items-center"><svg class="w-5 h-5 text-indigo-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Everything in Core HR</li>
                    <li class="flex items-center"><svg class="w-5 h-5 text-indigo-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Automated Payroll Processing</li>
                    <li class="flex items-center"><svg class="w-5 h-5 text-indigo-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Geo-fenced Attendance</li>
                    <li class="flex items-center"><svg class="w-5 h-5 text-indigo-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Tax Compliance Automation</li>
                </ul>
                <a href="/contact" class="block w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-500 text-white border border-transparent text-center font-medium rounded-lg transition">Book a Demo</a>
            </div>

            <!-- Tier 3 -->
            <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm flex flex-col">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Enterprise</h3>
                <p class="text-gray-500 text-sm mb-6">Advanced recruitment and performance modules.</p>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-gray-900">$12</span>
                    <span class="text-gray-500">/user/month</span>
                </div>
                <ul class="space-y-4 mb-8 flex-1 text-sm text-gray-600">
                    <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Everything in Growth</li>
                    <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Applicant Tracking System</li>
                    <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Performance Reviews</li>
                    <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Dedicated Account Manager</li>
                </ul>
                <a href="/contact" class="block w-full py-3 px-4 bg-gray-50 hover:bg-gray-100 text-gray-900 border border-gray-200 text-center font-medium rounded-lg transition">Contact Sales</a>
            </div>

        </div>
    </div>
</section>
@endsection
