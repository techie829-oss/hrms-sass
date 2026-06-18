@extends('layouts.marketing')

@section('title', 'HRMS for Manufacturing | SolidrixHR')
@section('description', 'Robust HRMS for manufacturing and industrial companies. Handle complex shifts, multi-location attendance, and compliance tracking seamlessly.')

@section('content')
<!-- Hero Section -->
<div class="relative pt-24 pb-32 overflow-hidden bg-vibrant-hero border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-bold text-sm mb-8 border border-blue-200">
            <span class="flex w-2 h-2 rounded-full bg-orange-500 mr-2"></span>
            Manufacturing HR
        </div>
        
        <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight mb-8">
            Built for the Shop Floor: <span class="text-blue-600">Manufacturing HRMS</span>
        </h1>
        
        <p class="mt-6 text-xl text-slate-600 max-w-3xl leading-relaxed mb-12">
            Manage rotating shifts, overtime calculations, statutory compliance, and multi-location workforces with an HR system built for heavy lifting.
        </p>
        
        <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6">
            <a href="/contact" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                Book a Demo
            </a>
            <a href="/features" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-blue-600 bg-white border-2 border-blue-100 hover:border-blue-600 hover:bg-blue-50 rounded-xl transition-all">
                Explore Modules
            </a>
        </div>
    </div>
</div>

<!-- Features Grid -->
@include('components.marketing.features-grid', [
    'title' => 'Industrial-Grade HR Operations',
    'subtitle' => 'We handle the complexity of manufacturing HR so you can focus on production.',
    'features' => [
        [
            'icon' => '⏱️',
            'title' => 'Complex Shift Management',
            'description' => 'Easily schedule rotating shifts, night shifts, and handle multi-location attendance tracking.'
        ],
        [
            'icon' => '🏭',
            'title' => 'Biometric Integration',
            'description' => 'Sync seamlessly with your shop floor biometric devices for accurate, real-time attendance.'
        ],
        [
            'icon' => '💰',
            'title' => 'Overtime & Piece-Rate',
            'description' => 'Automate complex overtime calculations and variable pay structures tied directly to payroll.'
        ],
        [
            'icon' => '📋',
            'title' => 'Statutory Compliance',
            'description' => 'Generate compliance reports for PF, ESI, PT, and factory acts automatically.'
        ],
        [
            'icon' => '👷',
            'title' => 'Contract Labor Tracking',
            'description' => 'Maintain records, attendance, and vendor billing for your contract workforce alongside regular employees.'
        ],
        [
            'icon' => '🛡️',
            'title' => 'Safety & Training Records',
            'description' => 'Track health and safety training certifications, renewals, and compliance mandates digitally.'
        ]
    ]
])
@endsection
