@extends('layouts.marketing')

@section('title', 'About Us | Solidrix HRMS')
@section('description', 'Learn about Solidrix HRMS, our mission to simplify workforce management, and how we are empowering growing businesses with smart HR technology.')
@section('content')

{{-- Hero Section --}}
<section class="pt-28 pb-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6 max-w-3xl leading-tight">
            HR Software Built for <span class="text-blue-600">People</span>
        </h1>
        <p class="text-xl text-gray-500 max-w-3xl leading-relaxed">
            We started Solidrix because managing teams shouldn't mean drowning in spreadsheets. We build practical,
            <a href="/modules" class="text-blue-600 hover:underline font-semibold">cloud-based HR tools</a>
            that let you focus on your people, not your admin work.
        </p>
    </div>
</section>

{{-- Stats Bar --}}
<section class="py-10 border-y border-gray-100 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-3xl font-extrabold text-blue-600 mb-1">500+</div>
                <div class="text-sm text-gray-500 font-medium">Organizations</div>
            </div>
            <div>
                <div class="text-3xl font-extrabold text-blue-600 mb-1">50K+</div>
                <div class="text-sm text-gray-500 font-medium">Employees Managed</div>
            </div>
            <div>
                <div class="text-3xl font-extrabold text-blue-600 mb-1">99.9%</div>
                <div class="text-sm text-gray-500 font-medium">Uptime SLA</div>
            </div>
            <div>
                <div class="text-3xl font-extrabold text-blue-600 mb-1">7</div>
                <div class="text-sm text-gray-500 font-medium">Integrated Modules</div>
            </div>
        </div>
    </div>
</section>

{{-- Mission & Vision --}}
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white border border-gray-100 rounded-3xl p-10 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h2>
                <p class="text-gray-500 leading-relaxed text-[15px]">
                    We build tools that make payroll, attendance, and leave management invisible. No manual calculations, no missing records, and no confusing interfaces. Just a single system that works so well, you barely notice it's there.
                </p>
            </div>

            <div class="bg-white border border-gray-100 rounded-3xl p-10 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Our Vision</h2>
                <p class="text-gray-500 leading-relaxed text-[15px]">
                    To give every growing business access to 
                    <a href="/pricing" class="text-blue-600 hover:underline font-semibold">enterprise-grade HR tools</a>
                    without the enterprise price tag. We want to be the background engine that keeps your operations running smoothly every single day.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Our Story Timeline --}}
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">Our Story</h2>
            <p class="mt-4 text-gray-500 text-lg">How we went from an idea to an HR platform trusted by hundreds</p>
        </div>

        <div class="relative w-full px-4">
            <div class="absolute left-8 md:left-1/2 transform md:-translate-x-1/2 h-full w-0.5 bg-gradient-to-b from-blue-200 via-amber-200 to-transparent"></div>

            <div class="space-y-12">
                {{-- 2018 --}}
                <div class="relative flex flex-col md:flex-row items-center">
                    <div class="hidden md:flex absolute left-1/2 transform -translate-x-1/2 w-5 h-5 rounded-full bg-blue-500 border-4 border-white shadow-md z-10"></div>
                    <div class="md:hidden absolute left-4 w-4 h-4 rounded-full bg-blue-500 border-2 border-white shadow-sm z-10"></div>
                    <div class="flex-1 md:pr-12 md:text-right mb-8 md:mb-0 ml-12 md:ml-0">
                        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                            <span class="text-blue-600 font-bold text-xl mb-2 block">2018</span>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">The Idea</h3>
                            <p class="text-gray-500 leading-relaxed text-sm">
                                Founded by HR professionals who were frustrated by spreadsheet chaos, disconnected payroll systems,
                                and zero visibility into workforce data. We decided to build what we always needed.
                            </p>
                        </div>
                    </div>
                    <div class="flex-1 hidden md:block"></div>
                </div>

                {{-- 2020 --}}
                <div class="relative flex flex-col md:flex-row items-center">
                    <div class="hidden md:flex absolute left-1/2 transform -translate-x-1/2 w-5 h-5 rounded-full bg-amber-500 border-4 border-white shadow-md z-10"></div>
                    <div class="md:hidden absolute left-4 w-4 h-4 rounded-full bg-amber-500 border-2 border-white shadow-sm z-10"></div>
                    <div class="flex-1 hidden md:block"></div>
                    <div class="flex-1 md:pl-12 mb-8 md:mb-0 ml-12 md:ml-0">
                        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                            <span class="text-amber-500 font-bold text-xl mb-2 block">2020</span>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">First Launch</h3>
                            <p class="text-gray-500 leading-relaxed text-sm">
                                Launched our first version covering Core HR, Attendance, and Payroll — onboarded our first
                                50 clients. The response was clear: the market needed exactly this.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- 2022 --}}
                <div class="relative flex flex-col md:flex-row items-center">
                    <div class="hidden md:flex absolute left-1/2 transform -translate-x-1/2 w-5 h-5 rounded-full bg-blue-500 border-4 border-white shadow-md z-10"></div>
                    <div class="md:hidden absolute left-4 w-4 h-4 rounded-full bg-blue-500 border-2 border-white shadow-sm z-10"></div>
                    <div class="flex-1 md:pr-12 md:text-right mb-8 md:mb-0 ml-12 md:ml-0">
                        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                            <span class="text-blue-600 font-bold text-xl mb-2 block">2022</span>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Multi-Tenant SaaS</h3>
                            <p class="text-gray-500 leading-relaxed text-sm">
                                Rebuilt the platform as a full multi-tenant SaaS with secure data isolation, custom subdomains,
                                and role-based access — each client gets their own isolated environment.
                            </p>
                        </div>
                    </div>
                    <div class="flex-1 hidden md:block"></div>
                </div>

                {{-- 2024 --}}
                <div class="relative flex flex-col md:flex-row items-center">
                    <div class="hidden md:flex absolute left-1/2 transform -translate-x-1/2 w-6 h-6 rounded-full bg-blue-600 border-4 border-white shadow-xl z-20 animate-pulse"></div>
                    <div class="md:hidden absolute left-4 w-5 h-5 rounded-full bg-blue-600 border-2 border-white shadow-md z-10"></div>
                    <div class="flex-1 hidden md:block"></div>
                    <div class="flex-1 md:pl-12 ml-12 md:ml-0">
                        <div class="bg-white p-8 rounded-3xl shadow-xl border border-blue-100 hover:-translate-y-1 transition-all duration-300 ring-4 ring-blue-50">
                            <span class="text-blue-600 font-bold text-xl mb-2 block">2024 — Today</span>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Expanding & Growing</h3>
                            <p class="text-gray-500 leading-relaxed text-sm">
                                Now serving 500+ organizations across India with 7 fully integrated modules. We're
                                continuously innovating with Recruitment, Performance, and Operations modules live.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Core Values --}}
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">Our Core Values</h2>
            <p class="mt-4 text-gray-500 text-lg">The principles that drive everything we build</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 text-center">
                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">Excellence</h3>
                <p class="text-gray-500 text-sm leading-relaxed">We never ship half-baked features. Every module is tested, polished, and designed to just work.</p>
            </div>

            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 text-center">
                <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">People First</h3>
                <p class="text-gray-500 text-sm leading-relaxed">HR software should serve people, not the other way around. Every design decision starts with the end user.</p>
            </div>

            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 text-center">
                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">Innovation</h3>
                <p class="text-gray-500 text-sm leading-relaxed">We continuously improve — adding features based on real customer feedback, not assumptions.</p>
            </div>

            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 text-center">
                <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3">Transparency</h3>
                <p class="text-gray-500 text-sm leading-relaxed">No hidden fees, no lock-ins. Clear pricing, honest roadmaps, and open communication with our customers.</p>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-24 bg-gray-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-6">Ready to Modernize Your HR?</h2>
        <p class="text-xl text-gray-400 mb-10">
            Join hundreds of growing businesses that run their HR on Solidrix.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/contact" class="px-8 py-4 rounded-xl text-base font-bold text-white bg-blue-600 hover:bg-blue-700 transition shadow-lg">
                Book a Demo
            </a>
            <a href="/modules" class="px-8 py-4 rounded-xl text-base font-bold text-white bg-white/10 hover:bg-white/20 border border-white/20 transition">
                Explore Features
            </a>
        </div>
    </div>
</section>

@endsection
