@extends('layouts.marketing')

@section('title', 'Project & CRM Operations Software | SolidrixHR')
@section('description', 'Go beyond HR. Manage clients, track projects, and assign tasks with the SolidrixHR Operations module.')

@section('content')
<!-- Hero Section -->
<div class="relative pt-24 pb-32 overflow-hidden bg-vibrant-hero border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-bold text-sm mb-8 border border-blue-200">
            <span class="flex w-2 h-2 rounded-full bg-orange-500 mr-2"></span>
            SolidrixHR Operations Module
        </div>
        
        <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight mb-8">
            HR Meets <span class="text-blue-600">Business Operations</span>
        </h1>
        
        <p class="mt-6 text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed mb-12">
            Why pay for separate project management tools? Track clients, manage project lifecycles, and assign tasks to your employees right where they already work.
        </p>
        
        <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
            <a href="/contact" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-xl transition-all shadow-lg">
                Book a Demo
            </a>
        </div>
        
        <!-- App Demo Image -->
        <div class="mt-16 max-w-5xl mx-auto rounded-xl shadow-2xl overflow-hidden border border-gray-200">
            <img src="{{ asset('images/demo/projects.png') }}" alt="Demo Interface" class="w-full h-auto object-cover object-top border-t border-gray-100">
        </div>
    </div>
</div>

<!-- Trust Bar -->
@include('components.marketing.trust-bar', [
    'items' => [
        ['value' => 'Integrated', 'label' => 'Task Management'],
        ['value' => 'Built-in', 'label' => 'CRM'],
        ['value' => 'Detailed', 'label' => 'Timesheets'],
        ['value' => 'Unified', 'label' => 'Team Collaboration'],
    ]
])

<!-- Features Grid -->
@include('components.marketing.features-grid', [
    'title' => 'Why Choose SolidrixHR Operations?',
    'subtitle' => 'Bridge the gap between managing your people and managing the work they actually do.',
    'features' => [
        [
            'icon' => '🤝',
            'title' => 'Client CRM',
            'description' => 'Maintain a database of your clients, their contact information, and associated projects.'
        ],
        [
            'icon' => '📁',
            'title' => 'Project Tracking',
            'description' => 'Create projects, set deadlines, define budgets, and monitor overall progress.'
        ],
        [
            'icon' => '✅',
            'title' => 'Task Assignments',
            'description' => 'Break projects down into actionable tasks and assign them directly to specific employees.'
        ],
        [
            'icon' => '⏱️',
            'title' => 'Timesheets',
            'description' => 'Employees can log the hours they spend on specific tasks and projects.'
        ],
        [
            'icon' => '📈',
            'title' => 'Status Boards',
            'description' => 'Visualize work via Kanban-style boards to see what is pending, in progress, and done.'
        ],
        [
            'icon' => '💵',
            'title' => 'Billable Tracking',
            'description' => 'Identify billable vs non-billable hours to ensure your projects remain profitable.'
        ]
    ]
])

<!-- Stakeholders Personas -->
@include('components.marketing.stakeholders', [
    'personas' => [
        [
            'icon' => '🏢',
            'title' => 'For Management',
            'points' => [
                'View project profitability and progress',
                'Ensure clients are being serviced properly',
                'Eliminate subscriptions for external project tools'
            ]
        ],
        [
            'icon' => '👨‍💼',
            'title' => 'For Project Managers',
            'points' => [
                'Assign tasks directly to team members',
                'Track time spent on specific deliverables',
                'Identify bottlenecks quickly'
            ]
        ],
        [
            'icon' => '👩‍💻',
            'title' => 'For Employees',
            'points' => [
                'See exactly what needs to be done today',
                'Log work hours easily via timesheets',
                'Keep all work in the same portal as HR'
            ]
        ]
    ]
])

<!-- 4 Steps Section -->
@include('components.marketing.steps', [
    'steps' => [
        [
            'title' => 'Add Client',
            'description' => 'Log the client details into your secure CRM database.'
        ],
        [
            'title' => 'Create Project',
            'description' => 'Set the scope, deadline, and assign it to a client.'
        ],
        [
            'title' => 'Assign Tasks',
            'description' => 'Break the work down and assign it to your employees.'
        ],
        [
            'title' => 'Track Time',
            'description' => 'Team logs hours on tasks until completion.'
        ]
    ]
])

<!-- Deep Dive Highlights -->
@include('components.marketing.deep-dive', [
    'sections' => [
        [
            'badge' => 'Context Switching',
            'title' => 'Stop Buying Too Many Tools',
            'description' => 'Small and medium businesses often suffer from "software fatigue" — using one tool for HR, another for timesheets, and a third for projects. SolidrixHR unifies them, saving you money and saving your team from constant tab-switching.',
            'points' => [
                'Reduced subscription costs',
                'One login for employees',
                'Consistent user interface'
            ]
        ],
        [
            'badge' => 'Workforce Alignment',
            'title' => 'Link Work Directly to Performance',
            'description' => 'Because Tasks and Projects live in the same system as Performance Management, managers have clear, objective data on exactly how much work an employee has completed when appraisal time arrives.',
            'points' => [
                'Objective record of tasks completed',
                'Clear visibility into time management',
                'Better alignment between HR and Operations'
            ]
        ]
    ]
])

<!-- FAQs -->
@include('components.marketing.faq', [
    'faqs' => [
        [
            'q' => 'Is this a full CRM system?',
            'a' => 'It is a lightweight CRM focused on operational delivery. It allows you to track client details and link them securely to the projects your team is executing.'
        ],
        [
            'q' => 'Can employees log time against tasks?',
            'a' => 'Yes, the Timesheets feature allows employees to log hours against specific tasks, giving you clear visibility into project resource consumption.'
        ],
        [
            'q' => 'Do clients get access to the system?',
            'a' => 'Currently, the system is designed for internal operations. Your team uses it to track work internally to ensure successful delivery to the client.'
        ],
        [
            'q' => 'Does timesheet data affect payroll?',
            'a' => 'Timesheets help track project costs and billable hours. Basic salary payroll is driven by the Attendance module, keeping operational tracking separate from compliance pay.'
        ]
    ]
])

<!-- Final CTA -->
<div class="bg-blue-600 py-20 border-t border-blue-500">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold text-white sm:text-4xl mb-6">
            Unify Your HR and Operations
        </h2>
        <p class="text-xl text-blue-100 mb-10">
            Start managing your projects and your people in one powerful platform.
        </p>
        <div class="flex justify-center">
            <a href="/contact" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-xl transition-all shadow-lg">
                Book a Demo
            </a>
        </div>
        
        <!-- App Demo Image -->
        <div class="mt-16 max-w-5xl mx-auto rounded-xl shadow-2xl overflow-hidden border border-gray-200">
            <img src="{{ asset('images/demo/projects.png') }}" alt="Demo Interface" class="w-full h-auto object-cover object-top border-t border-gray-100">
        </div>
    </div>
</div>

@endsection
