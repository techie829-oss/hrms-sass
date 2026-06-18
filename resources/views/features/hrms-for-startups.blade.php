@extends('layouts.marketing')

@section('title', 'HRMS for Startups | SolidrixHR')
@section('description', 'SolidrixHR provides a flexible, scalable HRMS for fast-growing startups. Automate onboarding, manage equity, and scale your culture seamlessly.')

@section('content')
<!-- Hero Section -->
<div class="relative pt-24 pb-32 overflow-hidden bg-vibrant-hero border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-bold text-sm mb-8 border border-blue-200">
            <span class="flex w-2 h-2 rounded-full bg-orange-500 mr-2"></span>
            Startup HR
        </div>
        
        <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight mb-8">
            HR Software Built for <span class="text-blue-600">Fast-Growing Startups</span>
        </h1>
        
        <p class="mt-6 text-xl text-slate-600 max-w-3xl leading-relaxed mb-12">
            Focus on building your product, not drowning in HR paperwork. SolidrixHR scales with you from your first hire to your 500th.
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
    'title' => 'Designed for Scale and Speed',
    'subtitle' => 'Modern features for modern teams. We give you the foundation to build a great company culture.',
    'features' => [
        [
            'icon' => '🚀',
            'title' => 'Seamless Onboarding',
            'description' => 'Automate your onboarding flows. Get new hires up to speed instantly with digital document signing and self-service.'
        ],
        [
            'icon' => '📈',
            'title' => 'Scalable Infrastructure',
            'description' => 'Our cloud-native platform handles 10 employees or 1,000 without missing a beat.'
        ],
        [
            'icon' => '🤝',
            'title' => 'Recruitment Pipeline',
            'description' => 'Built-in ATS to help you track applicants, schedule interviews, and hire the best talent quickly.'
        ],
        [
            'icon' => '🎯',
            'title' => 'Performance Management',
            'description' => 'Align your fast-moving team with objective OKRs and continuous performance feedback.'
        ],
        [
            'icon' => '🧩',
            'title' => 'Flexible Policies',
            'description' => 'Adapt quickly. Easily change leave, attendance, and payroll policies as your startup evolves.'
        ],
        [
            'icon' => '💸',
            'title' => 'Startup-Friendly Pricing',
            'description' => 'We know capital is precious. Get enterprise-grade tools on a startup budget.'
        ]
    ]
])
@endsection
