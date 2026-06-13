@extends('layouts.marketing')

@section('title', 'Complete HRMS Software for Modern Teams | SolidrixHR')
@section('description', 'SolidrixHR is a complete HRMS system combining Attendance, Payroll, Leave, Recruitment, and Performance into one unified platform.')

@section('content')
<!-- Hero Section -->
<div class="relative pt-24 pb-32 overflow-hidden bg-vibrant-hero border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-bold text-sm mb-8 border border-blue-200">
            <span class="flex w-2 h-2 rounded-full bg-orange-500 mr-2"></span>
            The All-in-One HR Platform
        </div>
        
        <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight mb-8">
            Complete <span class="text-blue-600">HR Management</span> System
        </h1>
        
        <p class="mt-6 text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed mb-12">
            Consolidate your tools. SolidrixHR brings Attendance, Payroll, Leave, Recruitment, and Performance into one secure, easy-to-use platform.
        </p>
        
        <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
            <a href="/contact" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                Book a Demo
            </a>
            <a href="/features" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-blue-600 bg-white border-2 border-blue-100 hover:border-blue-600 hover:bg-blue-50 rounded-xl transition-all">
                Explore Modules
            </a>
        </div>
        
        <!-- App Demo Image -->
        <div class="mt-16 max-w-5xl mx-auto rounded-xl shadow-2xl overflow-hidden border border-gray-200">
            <img src="{{ asset('images/demo/dashboard.png') }}" alt="Demo Interface" class="w-full h-auto object-cover object-top border-t border-gray-100">
        </div>
    </div>
</div>

<!-- Trust Bar -->
@include('components.marketing.trust-bar', [
    'items' => [
        ['value' => '7', 'label' => 'Core Modules'],
        ['value' => '100%', 'label' => 'Data Security'],
        ['value' => 'Real-Time', 'label' => 'Sync Engine'],
        ['value' => 'Cloud', 'label' => 'Native Architecture'],
    ]
])

<!-- Features Grid -->
@include('components.marketing.features-grid', [
    'title' => 'Why Choose SolidrixHR?',
    'subtitle' => 'Stop juggling spreadsheets and disparate tools. Run your operations smoothly from a single source of truth.',
    'features' => [
        [
            'icon' => '👥',
            'title' => 'Core HR & Directory',
            'description' => 'Maintain detailed employee records, departments, designations, and secure document storage.'
        ],
        [
            'icon' => '⏱️',
            'title' => 'Attendance Management',
            'description' => 'Track shifts, web clock-ins, and enforce strict attendance policies in real-time.'
        ],
        [
            'icon' => '💰',
            'title' => 'Automated Payroll',
            'description' => 'Custom salary structures, one-click payroll runs, and instant payslip generation.'
        ],
        [
            'icon' => '🏖️',
            'title' => 'Leave & Holidays',
            'description' => 'Manage leave balances, custom policies, comp-offs, and team holiday calendars.'
        ],
        [
            'icon' => '🎯',
            'title' => 'Performance & Goals',
            'description' => 'Set objective goals, track KPIs, and conduct structured appraisals effortlessly.'
        ],
        [
            'icon' => '🚀',
            'title' => 'Recruitment Pipeline',
            'description' => 'Publish job postings, track applications, and schedule interviews in one place.'
        ]
    ]
])

<!-- Stakeholders Personas -->
@include('components.marketing.stakeholders', [
    'personas' => [
        [
            'icon' => '👔',
            'title' => 'For Management',
            'points' => [
                'Complete visibility into workforce metrics',
                'Reduce compliance and payroll risks',
                'Align employee goals with business objectives'
            ]
        ],
        [
            'icon' => '🛡️',
            'title' => 'For HR Teams',
            'points' => [
                'Automate repetitive administrative tasks',
                'Centralize employee data and documents',
                'Streamline recruitment and onboarding'
            ]
        ],
        [
            'icon' => '📱',
            'title' => 'For Employees',
            'points' => [
                'Self-service portal for payslips & leaves',
                'Easy web clock-in and attendance tracking',
                'Clear visibility into goals and performance'
            ]
        ]
    ]
])

<!-- 4 Steps Section -->
@include('components.marketing.steps', [
    'steps' => [
        [
            'title' => 'System Setup',
            'description' => 'Configure your departments, designations, and company policies.'
        ],
        [
            'title' => 'Module Activation',
            'description' => 'Turn on the specific modules you need, like Payroll or Attendance.'
        ],
        [
            'title' => 'Data Import',
            'description' => 'Easily bring in your existing employee data and balances.'
        ],
        [
            'title' => 'Rollout',
            'description' => 'Invite your team and start managing HR seamlessly.'
        ]
    ]
])

<!-- Deep Dive Highlights -->
@include('components.marketing.deep-dive', [
    'sections' => [
        [
            'badge' => 'Unified Dashboard',
            'title' => 'One Platform, Total Control',
            'description' => 'SolidrixHR provides a single, unified interface for all your HR needs. No more switching tabs or copying data between incompatible systems.',
            'points' => [
                'Centralized employee timeline and history',
                'Real-time notifications for pending approvals',
                'Role-based access control for security'
            ]
        ],
        [
            'badge' => 'Beyond HR',
            'title' => 'Integrated Operations & CRM',
            'description' => 'Unlike traditional HRMS, SolidrixHR includes built-in operational tools to manage your core business alongside your team.',
            'points' => [
                'Track Clients and Leads natively',
                'Manage Projects and assign Tasks',
                'Integrated Timesheets linked to Payroll'
            ]
        ]
    ]
])

<!-- FAQs -->
@include('components.marketing.faq', [
    'faqs' => [
        [
            'q' => 'Is SolidrixHR suitable for my industry?',
            'a' => 'Yes, SolidrixHR is highly customizable. You can configure custom leave policies, salary structures, and attendance shifts to match the specific needs of manufacturing, IT, retail, or service industries.'
        ],
        [
            'q' => 'Do I have to use all the modules?',
            'a' => 'No. SolidrixHR is modular. You can start with Core HR and Attendance, and later activate Payroll, Recruitment, or Performance management as your company grows.'
        ],
        [
            'q' => 'Is my data secure?',
            'a' => 'Absolutely. We use robust cloud infrastructure, role-based access control, and encrypted databases to ensure your employee and payroll data is strictly confidential.'
        ],
        [
            'q' => 'Can employees access their own data?',
            'a' => 'Yes, SolidrixHR includes an Employee Self-Service (ESS) portal where staff can view payslips, request leave, punch in/out, and check their performance goals.'
        ]
    ]
])

<!-- Final CTA -->
<div class="bg-blue-600 py-20 border-t border-blue-500">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold text-white sm:text-4xl mb-6">
            Ready to Digitize Your HR Operations?
        </h2>
        <p class="text-xl text-blue-100 mb-10">
            Join modern organizations that trust SolidrixHR to manage their daily workforce.
        </p>
        <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
            <a href="/contact" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                Start Free Trial
            </a>
            <a href="/contact" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-blue-600 bg-white hover:bg-blue-50 rounded-xl transition-all">
                Book a Demo
            </a>
        </div>
        
        <!-- App Demo Image -->
        <div class="mt-16 max-w-5xl mx-auto rounded-xl shadow-2xl overflow-hidden border border-gray-200">
            <img src="{{ asset('images/demo/dashboard.png') }}" alt="Demo Interface" class="w-full h-auto object-cover object-top border-t border-gray-100">
        </div>
    </div>
</div>

@endsection
