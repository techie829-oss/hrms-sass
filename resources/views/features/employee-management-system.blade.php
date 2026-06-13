@extends('layouts.marketing')

@section('title', 'Employee Management System | SolidrixHR')
@section('description', 'Centralize employee data, departments, designations, and secure document storage with SolidrixHR Core HR.')

@section('content')
<!-- Hero Section -->
<div class="relative pt-24 pb-32 overflow-hidden bg-vibrant-hero border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-bold text-sm mb-8 border border-blue-200">
            <span class="flex w-2 h-2 rounded-full bg-orange-500 mr-2"></span>
            SolidrixHR Core HR Module
        </div>
        
        <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight mb-8">
            The Single Source of Truth for <span class="text-blue-600">Employee Data</span>
        </h1>
        
        <p class="mt-6 text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed mb-12">
            Organize departments, manage designations, and maintain secure digital records for every employee. Goodbye, physical file cabinets.
        </p>
        
        <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
            <a href="/contact" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-xl transition-all shadow-lg">
                Book a Demo
            </a>
        </div>
        
        <!-- App Demo Image -->
        <div class="mt-16 max-w-5xl mx-auto rounded-xl shadow-2xl overflow-hidden border border-gray-200">
            <img src="{{ asset('images/demo/employees.png') }}" alt="Demo Interface" class="w-full h-auto object-cover object-top border-t border-gray-100">
        </div>
    </div>
</div>

<!-- Trust Bar -->
@include('components.marketing.trust-bar', [
    'items' => [
        ['value' => 'Secure', 'label' => 'Document Vault'],
        ['value' => 'Unlimited', 'label' => 'Employee Profiles'],
        ['value' => 'Custom', 'label' => 'Departments'],
        ['value' => 'Cloud', 'label' => 'Storage'],
    ]
])

<!-- Features Grid -->
@include('components.marketing.features-grid', [
    'title' => 'Why Choose SolidrixHR Core HR?',
    'subtitle' => 'Build a strong organizational foundation with powerful directory and record management.',
    'features' => [
        [
            'icon' => '🗂️',
            'title' => 'Centralized Directory',
            'description' => 'A complete database of your workforce with contact info, emergency contacts, and joining details.'
        ],
        [
            'icon' => '🏢',
            'title' => 'Department Mapping',
            'description' => 'Structure your company cleanly by assigning employees to specific departments and locations.'
        ],
        [
            'icon' => '⭐',
            'title' => 'Role & Designations',
            'description' => 'Manage job titles, reporting managers, and organizational hierarchy seamlessly.'
        ],
        [
            'icon' => '🔒',
            'title' => 'Secure Documents',
            'description' => 'Store IDs, contracts, and compliance documents safely in the employee’s digital vault.'
        ],
        [
            'icon' => '💳',
            'title' => 'Bank Details',
            'description' => 'Securely capture and manage employee banking info required for smooth payroll runs.'
        ],
        [
            'icon' => '👋',
            'title' => 'Offboarding',
            'description' => 'Handle employee exits professionally, securing data access immediately upon resignation.'
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
                'No more searching through file cabinets',
                'Ensure all compliance documents are collected',
                'Maintain a clear organizational structure'
            ]
        ],
        [
            'icon' => '👨‍💼',
            'title' => 'For Management',
            'points' => [
                'View accurate headcount reports',
                'Understand department costs and structure',
                'Ensure data privacy and security'
            ]
        ],
        [
            'icon' => '👩‍💻',
            'title' => 'For Employees',
            'points' => [
                'Update emergency contacts themselves',
                'Access company directory easily',
                'View their own official documents'
            ]
        ]
    ]
])

<!-- 4 Steps Section -->
@include('components.marketing.steps', [
    'steps' => [
        [
            'title' => 'Define Structure',
            'description' => 'Create your company departments and designations.'
        ],
        [
            'title' => 'Add Employees',
            'description' => 'Input basic details and assign reporting managers.'
        ],
        [
            'title' => 'Collect Documents',
            'description' => 'Upload IDs and contracts to the secure vault.'
        ],
        [
            'title' => 'Maintain',
            'description' => 'Update records as roles or salaries change.'
        ]
    ]
])

<!-- Deep Dive Highlights -->
@include('components.marketing.deep-dive', [
    'sections' => [
        [
            'badge' => 'Document Vault',
            'title' => 'Digital, Secure, and Organized',
            'description' => 'Never lose an important document again. SolidrixHR provides a secure vault for each employee where you can upload and categorize files.',
            'points' => [
                'Store offer letters and contracts',
                'Upload identity proofs (Aadhar, PAN, SSN)',
                'Restrict access based on HR roles'
            ]
        ],
        [
            'badge' => 'Organizational Structure',
            'title' => 'Clear Reporting Lines',
            'description' => 'As your company grows, understanding who reports to whom becomes critical. SolidrixHR maps relationships natively to ensure workflows (like leave approvals) function automatically.',
            'points' => [
                'Assign direct managers',
                'Map employees to specific departments',
                'Track designation history over time'
            ]
        ]
    ]
])

<!-- FAQs -->
@include('components.marketing.faq', [
    'faqs' => [
        [
            'q' => 'Is the document storage secure?',
            'a' => 'Yes, all uploaded documents are securely stored in the cloud and access is strictly restricted to authorized HR administrators.'
        ],
        [
            'q' => 'Can employees update their own information?',
            'a' => 'Employees can request updates to basic details (like phone numbers and emergency contacts) to keep the directory accurate.'
        ],
        [
            'q' => 'How does this connect to payroll?',
            'a' => 'The Core HR module captures the necessary banking details and salary structures that the Payroll module uses to process salaries.'
        ],
        [
            'q' => 'Can we manage multiple branches/locations?',
            'a' => 'Yes, you can structure your employee directory by grouping them under specific departments or organizational tags.'
        ]
    ]
])

<!-- Final CTA -->
<div class="bg-blue-600 py-20 border-t border-blue-500">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold text-white sm:text-4xl mb-6">
            Get Your Employee Data Organized
        </h2>
        <p class="text-xl text-blue-100 mb-10">
            Start building a secure, scalable foundation for your workforce today.
        </p>
        <div class="flex justify-center">
            <a href="/contact" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-xl transition-all shadow-lg">
                Book a Demo
            </a>
        </div>
        
        <!-- App Demo Image -->
        <div class="mt-16 max-w-5xl mx-auto rounded-xl shadow-2xl overflow-hidden border border-gray-200">
            <img src="{{ asset('images/demo/employees.png') }}" alt="Demo Interface" class="w-full h-auto object-cover object-top border-t border-gray-100">
        </div>
    </div>
</div>

@endsection
