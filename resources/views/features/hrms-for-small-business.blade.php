@extends('layouts.marketing')

@section('title', 'HRMS for Small Business | SolidrixHR')
@section('description', 'SolidrixHR provides an affordable, easy-to-use HRMS tailored for small businesses. Manage attendance, payroll, and compliance without the enterprise price tag.')

@section('content')
<!-- Hero Section -->
<div class="relative pt-24 pb-32 overflow-hidden bg-vibrant-hero border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-bold text-sm mb-8 border border-blue-200">
            <span class="flex w-2 h-2 rounded-full bg-orange-500 mr-2"></span>
            Small Business HR
        </div>
        
        <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight mb-8">
            Big-Company HR Tools for <span class="text-blue-600">Small Businesses</span>
        </h1>
        
        <p class="mt-6 text-xl text-slate-600 max-w-3xl leading-relaxed mb-12">
            Stop wasting time on spreadsheets. Automate payroll, track attendance, and stay compliant with an HRMS that fits your budget and grows with you.
        </p>
        
        <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6">
            <a href="/contact" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                Book a Demo
            </a>
            <a href="/pricing" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-blue-600 bg-white border-2 border-blue-100 hover:border-blue-600 hover:bg-blue-50 rounded-xl transition-all">
                View Pricing
            </a>
        </div>
    </div>
</div>

<!-- Features Grid -->
@include('components.marketing.features-grid', [
    'title' => 'Why Small Businesses Love SolidrixHR',
    'subtitle' => 'Everything you need to manage your team, packaged in a simple interface that requires zero training.',
    'features' => [
        [
            'icon' => '⚡',
            'title' => 'Quick Setup',
            'description' => 'Get up and running in days, not months. Easy data imports and intuitive design.'
        ],
        [
            'icon' => '💰',
            'title' => 'Affordable Pricing',
            'description' => 'Pay only for what you use. Transparent, per-employee pricing designed for tight budgets.'
        ],
        [
            'icon' => '📊',
            'title' => 'Automated Payroll',
            'description' => 'Never make a calculation error again. Generate accurate payslips in just one click.'
        ],
        [
            'icon' => '🛡️',
            'title' => 'Built-in Compliance',
            'description' => 'Stay on the right side of local labor laws with automated tax deductions and reports.'
        ],
        [
            'icon' => '📱',
            'title' => 'Employee Self-Service',
            'description' => 'Let employees view payslips and apply for leave on their own from their devices.'
        ],
        [
            'icon' => '🤝',
            'title' => 'Friendly Support',
            'description' => 'Our team is here to help you every step of the way, just a call or chat away.'
        ]
    ]
])
@endsection
