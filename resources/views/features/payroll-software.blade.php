@extends('layouts.marketing')

@section('title', 'Payroll Processing Software | SolidrixHR')
@section('description', 'Automate payroll processing, configure custom salary structures, and generate payslips instantly with SolidrixHR.')

@section('content')
<!-- Hero Section -->
<div class="relative pt-24 pb-32 overflow-hidden bg-vibrant-hero border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-bold text-sm mb-8 border border-blue-200">
            <span class="flex w-2 h-2 rounded-full bg-orange-500 mr-2"></span>
            SolidrixHR Payroll Module
        </div>
        
        <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight mb-8">
            Run Payroll in <span class="text-blue-600">Minutes, Not Days</span>
        </h1>
        
        <p class="mt-6 text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed mb-12">
            Configure complex salary structures, automatically sync attendance data, and generate accurate payslips with absolute zero errors.
        </p>
        
        <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
            <a href="/contact" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-xl transition-all shadow-lg">
                Book a Demo
            </a>
        </div>
        
        <!-- App Demo Image -->
        <div class="mt-16 max-w-5xl mx-auto rounded-xl shadow-2xl overflow-hidden border border-gray-200">
            <img src="{{ asset('images/demo/payroll.png') }}" alt="Demo Interface" class="w-full h-auto object-cover object-top border-t border-gray-100">
        </div>
    </div>
</div>

<!-- Trust Bar -->
@include('components.marketing.trust-bar', [
    'items' => [
        ['value' => 'Automated', 'label' => 'Calculations'],
        ['value' => '1-Click', 'label' => 'Payslip Generation'],
        ['value' => 'Custom', 'label' => 'Salary Components'],
        ['value' => '100%', 'label' => 'Attendance Sync'],
    ]
])

<!-- Features Grid -->
@include('components.marketing.features-grid', [
    'title' => 'Why Choose SolidrixHR Payroll?',
    'subtitle' => 'Eliminate calculation errors and compliance headaches with a robust payroll engine.',
    'features' => [
        [
            'icon' => '💰',
            'title' => 'Salary Structures',
            'description' => 'Build flexible salary templates matching your company policies.'
        ],
        [
            'icon' => '🧩',
            'title' => 'Custom Components',
            'description' => 'Define earning and deduction components like Basic, HRA, PF, and Tax easily.'
        ],
        [
            'icon' => '🔄',
            'title' => 'Attendance Sync',
            'description' => 'Automatically deducts pay based on LOP (Loss of Pay) data from the Attendance module.'
        ],
        [
            'icon' => '📄',
            'title' => 'Instant Payslips',
            'description' => 'Generate professional PDF payslips and publish them to employee dashboards.'
        ],
        [
            'icon' => '🚀',
            'title' => 'Batch Payroll Runs',
            'description' => 'Process salaries for the entire organization or specific departments in one click.'
        ],
        [
            'icon' => '📊',
            'title' => 'Payroll Reports',
            'description' => 'Download detailed payout reports for bank transfers and accounting reconciliation.'
        ]
    ]
])

<!-- Stakeholders Personas -->
@include('components.marketing.stakeholders', [
    'personas' => [
        [
            'icon' => '🏢',
            'title' => 'For HR & Finance',
            'points' => [
                'No more manual spreadsheet calculations',
                'Ensure accurate deductions for LOP',
                'Download ready-to-use bank transfer files'
            ]
        ],
        [
            'icon' => '👨‍💼',
            'title' => 'For Management',
            'points' => [
                'Approve payroll batches securely',
                'Track total salary expenditures',
                'Ensure strict financial compliance'
            ]
        ],
        [
            'icon' => '👩‍💻',
            'title' => 'For Employees',
            'points' => [
                'Access payslips anytime from the portal',
                'Clear breakdown of earnings and deductions',
                'Download PDFs for loan applications'
            ]
        ]
    ]
])

<!-- 4 Steps Section -->
@include('components.marketing.steps', [
    'steps' => [
        [
            'title' => 'Set Components',
            'description' => 'Define earnings (Basic, HRA) and deductions (PF, Tax).'
        ],
        [
            'title' => 'Assign Structures',
            'description' => 'Link templates to employees based on their roles.'
        ],
        [
            'title' => 'Sync Attendance',
            'description' => 'Fetch LOP and unpaid leave data automatically.'
        ],
        [
            'title' => 'Run & Publish',
            'description' => 'Process the batch and publish payslips to the portal.'
        ]
    ]
])

<!-- Deep Dive Highlights -->
@include('components.marketing.deep-dive', [
    'sections' => [
        [
            'badge' => 'Customization Engine',
            'title' => 'Flexible Salary Components',
            'description' => 'Every business has different compensation structures. SolidrixHR lets you define custom formulas and fixed amounts for both earnings and deductions.',
            'points' => [
                'Create unlimited salary components',
                'Set components as taxable or non-taxable',
                'Apply percentage-based formulas (e.g. HRA = 40% of Basic)'
            ]
        ],
        [
            'badge' => 'Automation',
            'title' => 'Seamless Attendance Sync',
            'description' => 'Payroll is only as accurate as your attendance data. Because SolidrixHR is unified, your approved attendance logs automatically determine Loss of Pay (LOP) for the month.',
            'points' => [
                'Automatic deduction for unpaid leaves',
                'Calculates pro-rata salary for mid-month joiners',
                'Eliminates manual data entry errors'
            ]
        ]
    ]
])

<!-- FAQs -->
@include('components.marketing.faq', [
    'faqs' => [
        [
            'q' => 'Can I run payroll for specific departments only?',
            'a' => 'Yes, SolidrixHR allows you to create specific Payroll Runs, meaning you can process payments for different departments or locations separately.'
        ],
        [
            'q' => 'How are payslips distributed?',
            'a' => 'Once a payroll run is finalized, payslips are automatically published to the Employee Self-Service dashboard. Employees can view and download them as PDFs.'
        ],
        [
            'q' => 'Does it handle mid-month joiners?',
            'a' => 'Yes, the system calculates pro-rata salaries automatically based on the employee\'s joining date and the working days in that specific month.'
        ],
        [
            'q' => 'Can I add manual bonuses or deductions?',
            'a' => 'Absolutely. Before finalizing a payroll run, you can add ad-hoc bonuses, incentives, or manual deductions for specific employees.'
        ]
    ]
])

<!-- Final CTA -->
<div class="bg-blue-600 py-20 border-t border-blue-500">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold text-white sm:text-4xl mb-6">
            Make Payday Stress-Free
        </h2>
        <p class="text-xl text-blue-100 mb-10">
            Join the companies running zero-error payroll with SolidrixHR.
        </p>
        <div class="flex justify-center">
            <a href="/contact" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-xl transition-all shadow-lg">
                Book a Demo
            </a>
        </div>
        
        <!-- App Demo Image -->
        <div class="mt-16 max-w-5xl mx-auto rounded-xl shadow-2xl overflow-hidden border border-gray-200">
            <img src="{{ asset('images/demo/payroll.png') }}" alt="Demo Interface" class="w-full h-auto object-cover object-top border-t border-gray-100">
        </div>
    </div>
</div>

@endsection
