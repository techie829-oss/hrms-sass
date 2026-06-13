@extends('layouts.marketing')

@section('title', 'Leave Management System | SolidrixHR')
@section('description', 'Automate leave requests, track balances, and manage team holidays with SolidrixHR.')

@section('content')
<!-- Hero Section -->
<div class="relative pt-24 pb-32 overflow-hidden bg-vibrant-hero border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-bold text-sm mb-8 border border-blue-200">
            <span class="flex w-2 h-2 rounded-full bg-orange-500 mr-2"></span>
            SolidrixHR Leave Module
        </div>
        
        <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight mb-8">
            Effortless <span class="text-blue-600">Leave Management</span>
        </h1>
        
        <p class="mt-6 text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed mb-12">
            Say goodbye to email requests. Manage custom leave policies, track real-time balances, and streamline approvals in one place.
        </p>
        
        <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
            <a href="/contact" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-xl transition-all shadow-lg">
                Book a Demo
            </a>
        </div>
        
        <!-- App Demo Image -->
        <div class="mt-16 max-w-5xl mx-auto rounded-xl shadow-2xl overflow-hidden border border-gray-200">
            <img src="{{ asset('images/demo/leave.png') }}" alt="Demo Interface" class="w-full h-auto object-cover object-top border-t border-gray-100">
        </div>
    </div>
</div>

<!-- Trust Bar -->
@include('components.marketing.trust-bar', [
    'items' => [
        ['value' => 'Instant', 'label' => 'Approvals'],
        ['value' => 'Custom', 'label' => 'Leave Policies'],
        ['value' => 'Automated', 'label' => 'Balance Tracking'],
        ['value' => 'Shared', 'label' => 'Holiday Calendar'],
    ]
])

<!-- Features Grid -->
@include('components.marketing.features-grid', [
    'title' => 'Why Choose SolidrixHR for Leave?',
    'subtitle' => 'Designed to give employees transparency while giving HR total control.',
    'features' => [
        [
            'icon' => '🌴',
            'title' => 'Custom Leave Types',
            'description' => 'Create Sick, Casual, Earned, or Maternity leave types with custom accrual rules.'
        ],
        [
            'icon' => '⏳',
            'title' => 'Real-Time Balances',
            'description' => 'Employees always know exactly how many days they have left without asking HR.'
        ],
        [
            'icon' => '✓',
            'title' => 'Multi-Level Approvals',
            'description' => 'Route leave requests to the direct manager and HR for seamless approval workflows.'
        ],
        [
            'icon' => '📅',
            'title' => 'Holiday Calendar',
            'description' => 'Publish public and company holidays so everyone stays on the same page.'
        ],
        [
            'icon' => '🎁',
            'title' => 'Comp-Offs',
            'description' => 'Manage compensatory time off for employees working on weekends or holidays.'
        ],
        [
            'icon' => '🔗',
            'title' => 'Payroll Sync',
            'description' => 'Approved unpaid leaves automatically calculate as LOP during the monthly payroll run.'
        ]
    ]
])

<!-- Stakeholders Personas -->
@include('components.marketing.stakeholders', [
    'personas' => [
        [
            'icon' => '🏢',
            'title' => 'For HR Admins',
            'points' => [
                'No manual tracking in spreadsheets',
                'Configure complex accrual rules',
                'Global view of company availability'
            ]
        ],
        [
            'icon' => '👨‍💼',
            'title' => 'For Managers',
            'points' => [
                'Approve leaves with one click',
                'Avoid team resource conflicts',
                'View upcoming team absences'
            ]
        ],
        [
            'icon' => '👩‍💻',
            'title' => 'For Employees',
            'points' => [
                'Request leave from anywhere',
                'Check transparent leave balances',
                'Track request approval status'
            ]
        ]
    ]
])

<!-- 4 Steps Section -->
@include('components.marketing.steps', [
    'steps' => [
        [
            'title' => 'Set Policies',
            'description' => 'Define your leave types and yearly quotas.'
        ],
        [
            'title' => 'Add Holidays',
            'description' => 'Publish the official company holiday calendar.'
        ],
        [
            'title' => 'Employee Request',
            'description' => 'Staff apply for leave via their dashboard.'
        ],
        [
            'title' => 'Manager Approval',
            'description' => 'Managers review and approve instantly.'
        ]
    ]
])

<!-- Deep Dive Highlights -->
@include('components.marketing.deep-dive', [
    'sections' => [
        [
            'badge' => 'Self-Service',
            'title' => 'Empower Your Employees',
            'description' => 'SolidrixHR provides an intuitive Employee Self-Service (ESS) portal. Employees can check their leave history, current balances, and upcoming company holidays without interrupting the HR team.',
            'points' => [
                'View used vs. available balances',
                'Upload medical certificates for sick leave',
                'Check team availability before applying'
            ]
        ],
        [
            'badge' => 'Compliance',
            'title' => 'Customizable Accrual Rules',
            'description' => 'Every organization has unique leave policies. SolidrixHR allows you to configure rules that match your local labor laws and company culture.',
            'points' => [
                'Monthly or annual leave crediting',
                'Carry-forward and encashment rules',
                'Probation period restrictions'
            ]
        ]
    ]
])

<!-- FAQs -->
@include('components.marketing.faq', [
    'faqs' => [
        [
            'q' => 'Can I create different leave types?',
            'a' => 'Yes, you can create unlimited leave types (e.g., Casual, Sick, Bereavement) and set independent rules for each.'
        ],
        [
            'q' => 'How does Comp-Off work?',
            'a' => 'Employees can raise a Comp-Off request for working extra days. Once approved, the days are added to their Comp-Off leave balance.'
        ],
        [
            'q' => 'Are leave balances automatically updated?',
            'a' => 'Absolutely. Once a leave request is approved, the balance is instantly deducted. Unpaid leaves will also flag for payroll deduction.'
        ],
        [
            'q' => 'Can managers see who else is on leave?',
            'a' => 'Yes, managers can view a calendar of their direct reports to ensure adequate coverage before approving new leave requests.'
        ]
    ]
])

<!-- Final CTA -->
<div class="bg-blue-600 py-20 border-t border-blue-500">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold text-white sm:text-4xl mb-6">
            Ready to Streamline Leave Management?
        </h2>
        <p class="text-xl text-blue-100 mb-10">
            Provide a better experience for your team and save hours of HR admin work.
        </p>
        <div class="flex justify-center">
            <a href="/contact" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-xl transition-all shadow-lg">
                Book a Demo
            </a>
        </div>
        
        <!-- App Demo Image -->
        <div class="mt-16 max-w-5xl mx-auto rounded-xl shadow-2xl overflow-hidden border border-gray-200">
            <img src="{{ asset('images/demo/leave.png') }}" alt="Demo Interface" class="w-full h-auto object-cover object-top border-t border-gray-100">
        </div>
    </div>
</div>

@endsection
