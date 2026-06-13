@extends('layouts.marketing')

@section('title', 'Recruitment Software | SolidrixHR')
@section('description', 'Streamline your hiring process, manage job postings, and track candidates with the SolidrixHR Recruitment module.')

@section('content')
<!-- Hero Section -->
<div class="relative pt-24 pb-32 overflow-hidden bg-vibrant-hero border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-bold text-sm mb-8 border border-blue-200">
            <span class="flex w-2 h-2 rounded-full bg-orange-500 mr-2"></span>
            SolidrixHR Recruitment Module
        </div>
        
        <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight mb-8">
            Hire the Best <span class="text-blue-600">Talent, Faster</span>
        </h1>
        
        <p class="mt-6 text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed mb-12">
            Publish job openings, track applicants through a visual pipeline, schedule interviews, and convert candidates to employees instantly.
        </p>
        
        <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
            <a href="/contact" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-xl transition-all shadow-lg">
                Book a Demo
            </a>
        </div>
        
        <!-- App Demo Image -->
        <div class="mt-16 max-w-5xl mx-auto rounded-xl shadow-2xl overflow-hidden border border-gray-200">
            <img src="{{ asset('images/demo/recruitment.png') }}" alt="Demo Interface" class="w-full h-auto object-cover object-top border-t border-gray-100">
        </div>
    </div>
</div>

<!-- Trust Bar -->
@include('components.marketing.trust-bar', [
    'items' => [
        ['value' => 'Centralized', 'label' => 'Job Board'],
        ['value' => 'Visual', 'label' => 'Candidate Pipeline'],
        ['value' => '1-Click', 'label' => 'Onboarding'],
        ['value' => 'Collaborative', 'label' => 'Hiring'],
    ]
])

<!-- Features Grid -->
@include('components.marketing.features-grid', [
    'title' => 'Why Choose SolidrixHR Recruitment?',
    'subtitle' => 'Bring structure to your hiring process and never lose track of a great candidate.',
    'features' => [
        [
            'icon' => '📢',
            'title' => 'Job Postings',
            'description' => 'Create detailed job descriptions and publish active openings for your HR team to manage.'
        ],
        [
            'icon' => '📥',
            'title' => 'Applicant Tracking',
            'description' => 'Collect resumes and track candidates through stages like Applied, Interviewing, and Offered.'
        ],
        [
            'icon' => '🤝',
            'title' => 'Interview Scheduling',
            'description' => 'Schedule interviews, invite candidates, and ensure your hiring managers are prepared.'
        ],
        [
            'icon' => '📝',
            'title' => 'Evaluation Notes',
            'description' => 'Keep internal notes and feedback on candidates so the whole hiring team stays aligned.'
        ],
        [
            'icon' => '✅',
            'title' => 'Offer Management',
            'description' => 'Move candidates to the offer stage and prepare their documents directly within the portal.'
        ],
        [
            'icon' => '🚀',
            'title' => 'Seamless Onboarding',
            'description' => 'Convert a hired candidate into an active employee in the Core HR module with one single click.'
        ]
    ]
])

<!-- Stakeholders Personas -->
@include('components.marketing.stakeholders', [
    'personas' => [
        [
            'icon' => '🏢',
            'title' => 'For HR Recruiters',
            'points' => [
                'Manage all open positions in one place',
                'Easily track where candidates are in the pipeline',
                'No more lost resumes in email inboxes'
            ]
        ],
        [
            'icon' => '👨‍💼',
            'title' => 'For Hiring Managers',
            'points' => [
                'View candidate profiles before interviews',
                'Leave structured feedback and notes',
                'Collaborate on the final hiring decision'
            ]
        ],
        [
            'icon' => '👩‍💻',
            'title' => 'For Candidates',
            'points' => [
                'Clear communication and scheduling',
                'Faster response times from the company',
                'Smooth transition to onboarding if hired'
            ]
        ]
    ]
])

<!-- 4 Steps Section -->
@include('components.marketing.steps', [
    'steps' => [
        [
            'title' => 'Create Job',
            'description' => 'Define the role, requirements, and assign hiring managers.'
        ],
        [
            'title' => 'Add Candidates',
            'description' => 'Input applicant details and upload their resumes.'
        ],
        [
            'title' => 'Interview',
            'description' => 'Move candidates through your customized hiring stages.'
        ],
        [
            'title' => 'Hire & Onboard',
            'description' => 'Convert the successful candidate directly into an employee.'
        ]
    ]
])

<!-- Deep Dive Highlights -->
@include('components.marketing.deep-dive', [
    'sections' => [
        [
            'badge' => 'Visual Pipeline',
            'title' => 'Know Where Everyone Stands',
            'description' => 'SolidrixHR gives you a clear view of your entire hiring funnel. At a glance, see how many candidates are being screened, interviewed, or offered for every open role.',
            'points' => [
                'Customizable hiring stages',
                'Filter candidates by job posting',
                'Quickly identify bottlenecks in the process'
            ]
        ],
        [
            'badge' => 'Unified System',
            'title' => 'From Candidate to Employee',
            'description' => 'The biggest pain point in recruitment is data entry. When you hire someone in SolidrixHR, their data automatically transfers to the Core HR module, saving you from typing it all over again.',
            'points' => [
                'One-click employee creation',
                'Retains interview notes in employee history',
                'Seamless handoff to payroll and attendance'
            ]
        ]
    ]
])

<!-- FAQs -->
@include('components.marketing.faq', [
    'faqs' => [
        [
            'q' => 'Can multiple people evaluate a candidate?',
            'a' => 'Yes, hiring managers and HR personnel can both leave internal notes and evaluations on a candidate\'s profile.'
        ],
        [
            'q' => 'What happens to the data of rejected candidates?',
            'a' => 'Candidate data remains securely in the system for future reference, allowing you to build a talent pool for upcoming roles.'
        ],
        [
            'q' => 'Do I have to manually re-enter data when a candidate is hired?',
            'a' => 'No. SolidrixHR features a one-click conversion tool that takes the candidate\'s details and instantly generates a new employee profile in the Core HR module.'
        ],
        [
            'q' => 'Can I customize the interview stages?',
            'a' => 'Yes, you can track candidates through standard stages (Applied, Interview, Offered, Hired, Rejected) to match your workflow.'
        ]
    ]
])

<!-- Final CTA -->
<div class="bg-blue-600 py-20 border-t border-blue-500">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold text-white sm:text-4xl mb-6">
            Build Your Dream Team
        </h2>
        <p class="text-xl text-blue-100 mb-10">
            Stop losing great candidates to disorganized processes. 
        </p>
        <div class="flex justify-center">
            <a href="/contact" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-xl transition-all shadow-lg">
                Book a Demo
            </a>
        </div>
        
        <!-- App Demo Image -->
        <div class="mt-16 max-w-5xl mx-auto rounded-xl shadow-2xl overflow-hidden border border-gray-200">
            <img src="{{ asset('images/demo/recruitment.png') }}" alt="Demo Interface" class="w-full h-auto object-cover object-top border-t border-gray-100">
        </div>
    </div>
</div>

@endsection
