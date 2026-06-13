@extends('layouts.marketing')

@section('title', 'Performance Management Software | SolidrixHR')
@section('description', 'Align your team with company objectives, set clear goals, and conduct structured appraisals with SolidrixHR.')

@section('content')
<!-- Hero Section -->
<div class="relative pt-24 pb-32 overflow-hidden bg-vibrant-hero border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-bold text-sm mb-8 border border-blue-200">
            <span class="flex w-2 h-2 rounded-full bg-orange-500 mr-2"></span>
            SolidrixHR Performance Module
        </div>
        
        <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight mb-8">
            Unlock Your Team's <span class="text-blue-600">Full Potential</span>
        </h1>
        
        <p class="mt-6 text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed mb-12">
            Move beyond subjective reviews. Set clear KPIs, track progress continuously, and conduct structured, data-driven appraisals.
        </p>
        
        <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
            <a href="/contact" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-xl transition-all shadow-lg">
                Book a Demo
            </a>
        </div>
        
        <!-- App Demo Image -->
        <div class="mt-16 max-w-5xl mx-auto rounded-xl shadow-2xl overflow-hidden border border-gray-200">
            <img src="{{ asset('images/demo/performance.png') }}" alt="Demo Interface" class="w-full h-auto object-cover object-top border-t border-gray-100">
        </div>
    </div>
</div>

<!-- Trust Bar -->
@include('components.marketing.trust-bar', [
    'items' => [
        ['value' => 'Clear', 'label' => 'Goal Setting'],
        ['value' => 'Continuous', 'label' => 'Feedback'],
        ['value' => 'Data-Driven', 'label' => 'Appraisals'],
        ['value' => 'Transparent', 'label' => 'Growth'],
    ]
])

<!-- Features Grid -->
@include('components.marketing.features-grid', [
    'title' => 'Why Choose SolidrixHR Performance?',
    'subtitle' => 'Turn annual reviews into continuous growth conversations that actually improve outcomes.',
    'features' => [
        [
            'icon' => '🎯',
            'title' => 'Goal Setting (KPIs)',
            'description' => 'Assign measurable Key Performance Indicators to employees aligned with department objectives.'
        ],
        [
            'icon' => '📈',
            'title' => 'Progress Tracking',
            'description' => 'Employees and managers can continuously update goal progress throughout the year.'
        ],
        [
            'icon' => '📝',
            'title' => 'Structured Appraisals',
            'description' => 'Conduct standardized reviews based on actual performance data, not just recent memory.'
        ],
        [
            'icon' => '⭐',
            'title' => 'Skill Evaluation',
            'description' => 'Rate employees on core competencies and soft skills relevant to their specific designations.'
        ],
        [
            'icon' => '💬',
            'title' => 'Continuous Feedback',
            'description' => 'Managers can leave regular notes to ensure employees always know where they stand.'
        ],
        [
            'icon' => '🏆',
            'title' => 'Performance Reports',
            'description' => 'Identify top performers and areas needing training across the entire organization.'
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
                'Ensure everyone is working towards company goals',
                'Identify future leaders based on objective data',
                'Standardize the appraisal process'
            ]
        ],
        [
            'icon' => '👨‍💼',
            'title' => 'For Managers',
            'points' => [
                'Easily track team goals in one place',
                'Provide structured, documented feedback',
                'Make evidence-based promotion recommendations'
            ]
        ],
        [
            'icon' => '👩‍💻',
            'title' => 'For Employees',
            'points' => [
                'Clear understanding of expectations',
                'No surprises during the annual review',
                'Visibility into their own career progression'
            ]
        ]
    ]
])

<!-- 4 Steps Section -->
@include('components.marketing.steps', [
    'steps' => [
        [
            'title' => 'Define KPIs',
            'description' => 'Set clear, measurable goals at the start of the quarter/year.'
        ],
        [
            'title' => 'Track Progress',
            'description' => 'Log updates and achievements against those goals regularly.'
        ],
        [
            'title' => 'Evaluate',
            'description' => 'Managers assess goal completion and core competencies.'
        ],
        [
            'title' => 'Review',
            'description' => 'Finalize the appraisal and discuss outcomes transparently.'
        ]
    ]
])

<!-- Deep Dive Highlights -->
@include('components.marketing.deep-dive', [
    'sections' => [
        [
            'badge' => 'Objective Measurement',
            'title' => 'Say Goodbye to Recency Bias',
            'description' => 'Traditional appraisals often fail because managers only remember what happened last month. SolidrixHR forces continuous tracking of specific KPIs, ensuring reviews are fair and data-driven.',
            'points' => [
                'Track goals quantitatively (e.g., Sales Target, Code Shipped)',
                'Historical record of achievements',
                'Separate objective KPIs from subjective skill ratings'
            ]
        ],
        [
            'badge' => 'Unified Experience',
            'title' => 'Tied to the Employee Journey',
            'description' => 'Performance doesn\'t live in a vacuum. Because it\'s part of the unified SolidrixHR platform, performance history stays with the employee profile forever.',
            'points' => [
                'Helps justify salary increments in Payroll',
                'Informs future promotion decisions in Core HR',
                'Accessible directly from the employee\'s main dashboard'
            ]
        ]
    ]
])

<!-- FAQs -->
@include('components.marketing.faq', [
    'faqs' => [
        [
            'q' => 'Can employees see their own goals?',
            'a' => 'Yes, goals are visible on the Employee Self-Service portal where staff can actively update their progress.'
        ],
        [
            'q' => 'Who can evaluate an employee?',
            'a' => 'Typically, the employee\'s designated reporting manager conducts the appraisal, ensuring direct accountability.'
        ],
        [
            'q' => 'Is this only for annual reviews?',
            'a' => 'No. While great for annual appraisals, you can use the system to track goals monthly or quarterly based on your company culture.'
        ],
        [
            'q' => 'Does it track soft skills?',
            'a' => 'Yes, alongside objective KPIs, managers can rate employees on qualitative skills like leadership, communication, and teamwork.'
        ]
    ]
])

<!-- Final CTA -->
<div class="bg-blue-600 py-20 border-t border-blue-500">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold text-white sm:text-4xl mb-6">
            Build a High-Performance Culture
        </h2>
        <p class="text-xl text-blue-100 mb-10">
            Start aligning your team's daily work with your long-term business goals.
        </p>
        <div class="flex justify-center">
            <a href="/contact" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-xl transition-all shadow-lg">
                Book a Demo
            </a>
        </div>
        
        <!-- App Demo Image -->
        <div class="mt-16 max-w-5xl mx-auto rounded-xl shadow-2xl overflow-hidden border border-gray-200">
            <img src="{{ asset('images/demo/performance.png') }}" alt="Demo Interface" class="w-full h-auto object-cover object-top border-t border-gray-100">
        </div>
    </div>
</div>

@endsection
