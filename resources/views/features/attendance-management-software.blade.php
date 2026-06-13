@extends('layouts.marketing')

@section('title', 'Attendance Management Software | SolidrixHR')
@section('description', 'Track employee attendance with web clock-ins, custom shifts, and strict policy enforcement.')

@section('content')
<!-- Hero Section -->
<div class="relative pt-24 pb-32 overflow-hidden bg-vibrant-hero border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-bold text-sm mb-8 border border-blue-200">
            <span class="flex w-2 h-2 rounded-full bg-orange-500 mr-2"></span>
            SolidrixHR Attendance Module
        </div>
        
        <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight mb-8">
            Track Attendance Without <span class="text-blue-600">Spreadsheets</span>
        </h1>
        
        <p class="mt-6 text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed mb-12">
            Eliminate manual tracking. Assign custom shifts, enforce attendance policies, and monitor real-time web clock-ins effortlessly.
        </p>
        
        <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
            <a href="/contact" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                Book a Demo
            </a>
        </div>
        
        <!-- App Demo Image -->
        <div class="mt-16 max-w-5xl mx-auto rounded-xl shadow-2xl overflow-hidden border border-gray-200">
            <img src="{{ asset('images/demo/attendance.png') }}" alt="Demo Interface" class="w-full h-auto object-cover object-top border-t border-gray-100">
        </div>
    </div>
</div>

<!-- Trust Bar -->
@include('components.marketing.trust-bar', [
    'items' => [
        ['value' => 'Web', 'label' => 'Clock-in'],
        ['value' => 'Real-Time', 'label' => 'Log Sync'],
        ['value' => 'Custom', 'label' => 'Shift Planning'],
        ['value' => 'Auto', 'label' => 'Daily Summaries'],
    ]
])

<!-- Features Grid -->
@include('components.marketing.features-grid', [
    'title' => 'Why Choose SolidrixHR Attendance?',
    'subtitle' => 'Built to handle complex attendance policies and shift rotations for modern businesses.',
    'features' => [
        [
            'icon' => '⏱️',
            'title' => 'Web Clock-In/Out',
            'description' => 'Employees can easily log their attendance via the web portal. Simple, secure, and instant.'
        ],
        [
            'icon' => '📅',
            'title' => 'Shift Management',
            'description' => 'Create multiple shifts with grace periods and assign them to individuals or entire departments.'
        ],
        [
            'icon' => '⚖️',
            'title' => 'Policy Enforcement',
            'description' => 'Define strict attendance policies. Automatically flag late arrivals, early departures, and half-days.'
        ],
        [
            'icon' => '📊',
            'title' => 'Daily Summaries',
            'description' => 'Get automated daily attendance reports outlining total hours worked, overtime, and shortfalls.'
        ],
        [
            'icon' => '🔔',
            'title' => 'Manager Approvals',
            'description' => 'Managers can review and approve attendance regularization requests directly from their dashboard.'
        ],
        [
            'icon' => '🔄',
            'title' => 'Payroll Integration',
            'description' => 'Approved attendance data flows directly into the payroll module for accurate salary calculation.'
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
                'Set up complex shifts and rosters',
                'No manual reconciliation of timesheets',
                'Automated policy enforcement'
            ]
        ],
        [
            'icon' => '👨‍💼',
            'title' => 'For Managers',
            'points' => [
                'See who is present in real-time',
                'Approve regularization requests easily',
                'Identify chronic absenteeism'
            ]
        ],
        [
            'icon' => '👩‍💻',
            'title' => 'For Employees',
            'points' => [
                'One-click web punch-in',
                'View own attendance logs and shortfalls',
                'Request regularization online'
            ]
        ]
    ]
])

<!-- 4 Steps Section -->
@include('components.marketing.steps', [
    'steps' => [
        [
            'title' => 'Create Shifts',
            'description' => 'Define your standard working hours and grace periods.'
        ],
        [
            'title' => 'Set Policies',
            'description' => 'Configure rules for late marks and half-days.'
        ],
        [
            'title' => 'Assign Staff',
            'description' => 'Link employees or entire departments to shifts.'
        ],
        [
            'title' => 'Track Logs',
            'description' => 'Employees clock in, and data syncs in real-time.'
        ]
    ]
])

<!-- Deep Dive Highlights -->
@include('components.marketing.deep-dive', [
    'sections' => [
        [
            'badge' => 'Attendance Enforcement',
            'title' => 'Policies that work automatically',
            'description' => 'Say goodbye to manually checking who came in late. SolidrixHR automatically applies your custom policies to daily logs.',
            'points' => [
                'Automated Late Mark deductions',
                'Half-day calculation based on working hours',
                'Grace period configurations'
            ]
        ],
        [
            'badge' => 'Unified Ecosystem',
            'title' => 'Seamless Payroll Integration',
            'description' => 'Attendance isn\'t just about knowing who is at work; it directly impacts pay. SolidrixHR ensures LOP (Loss of Pay) is accurately calculated.',
            'points' => [
                'Approved logs feed directly to payroll',
                'No manual CSV exports required',
                'Accurate overtime calculations'
            ]
        ]
    ]
])

<!-- FAQs -->
@include('components.marketing.faq', [
    'faqs' => [
        [
            'q' => 'How do employees log their attendance?',
            'a' => 'Employees log their attendance using a secure Web Clock-in button available on their self-service dashboard.'
        ],
        [
            'q' => 'Can I assign different shifts to different departments?',
            'a' => 'Yes, you can create unlimited shift profiles and assign them flexibly at the employee or department level.'
        ],
        [
            'q' => 'What happens if an employee forgets to clock out?',
            'a' => 'Employees can raise an Attendance Regularization request, which their manager can review and approve.'
        ],
        [
            'q' => 'Does this integrate with the payroll module?',
            'a' => 'Yes. Once attendance is finalized for the month, it automatically calculates LOP days and feeds directly into the payroll run.'
        ]
    ]
])

<!-- Final CTA -->
<div class="bg-blue-600 py-20 border-t border-blue-500">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold text-white sm:text-4xl mb-6">
            Ready to Automate Attendance?
        </h2>
        <p class="text-xl text-blue-100 mb-10">
            Stop chasing timesheets and start focusing on your people.
        </p>
        <div class="flex justify-center">
            <a href="/contact" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-xl transition-all shadow-lg">
                Book a Demo
            </a>
        </div>
        
        <!-- App Demo Image -->
        <div class="mt-16 max-w-5xl mx-auto rounded-xl shadow-2xl overflow-hidden border border-gray-200">
            <img src="{{ asset('images/demo/attendance.png') }}" alt="Demo Interface" class="w-full h-auto object-cover object-top border-t border-gray-100">
        </div>
    </div>
</div>

@endsection
