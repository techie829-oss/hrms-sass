@extends('layouts.landing')

@section('title', 'HR Modules | HRMS Solutions')

@section('content')
<!-- Modules Hero -->
<section class="py-20 md:py-32 px-6 md:px-12 bg-hero-gradient overflow-hidden">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center gap-12 lg:gap-16 relative z-10">
        <div class="md:w-1/2 space-y-6 md:space-y-8 text-center md:text-left">
            <div class="inline-flex items-center px-4 py-1.5 bg-primary/10 text-primary rounded-full text-[10px] md:text-xs font-bold tracking-widest uppercase premium-shadow border border-primary/10 mx-auto md:mx-0">
                Modular Architecture
            </div>
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-on-surface leading-[1.1] tracking-tight font-headline">
                Modular Architecture for <br class="hidden md:block"><span class="text-gradient">Modern Teams.</span>
            </h1>
            <p class="text-base md:text-lg text-on-surface-variant max-w-xl font-medium leading-relaxed mx-auto md:mx-0">
                Scale your HR operations with precision. Our modular design allows you to pick exactly what you need today, and expand effortlessly tomorrow.
            </p>
            <div class="flex flex-col sm:flex-row items-center gap-4 pt-4">
                <a href="/contact" class="w-full sm:auto primary-gradient px-8 py-4 text-on-primary font-bold rounded-xl shadow-lg hover:scale-105 transition-transform premium-shadow text-center">Book a Demo</a>
                <a href="/pricing" class="w-full sm:auto text-on-surface font-semibold flex items-center justify-center gap-2 px-6 py-4 rounded-xl hover:bg-surface-container-low transition-colors">
                    View Pricing <span class="material-symbols-outlined">payments</span>
                </a>
            </div>
        </div>
        <div class="md:w-1/2 relative h-[350px] md:h-[450px] flex items-center justify-center mt-12 md:mt-0 w-full">
            <div class="absolute inset-0 bg-primary/5 blur-3xl rounded-full"></div>
            <div class="relative z-10 grid grid-cols-2 gap-4 md:gap-6 w-full max-w-md">
                <div class="bg-surface-container-lowest p-6 md:p-8 rounded-2xl premium-shadow border border-outline-variant/10 mt-8 transform hover:-translate-y-2 transition-transform group">
                    <span class="material-symbols-outlined text-primary text-3xl md:text-4xl mb-4" style="font-variation-settings: 'FILL' 1;">widgets</span>
                    <div class="h-2 w-12 bg-primary/20 rounded-full mb-3"></div>
                    <div class="h-2 w-20 bg-primary/10 rounded-full"></div>
                </div>
                <div class="bg-surface-container-lowest p-6 md:p-8 rounded-2xl premium-shadow border border-outline-variant/10 transform hover:-translate-y-2 transition-transform group">
                    <span class="material-symbols-outlined text-tertiary text-3xl md:text-4xl mb-4" style="font-variation-settings: 'FILL' 1;">auto_awesome_motion</span>
                    <div class="h-2 w-16 bg-tertiary/20 rounded-full mb-3"></div>
                    <div class="h-2 w-8 bg-tertiary/10 rounded-full"></div>
                </div>
                <div class="bg-surface-container-lowest p-6 md:p-8 rounded-2xl premium-shadow border border-outline-variant/10 -mt-4 transform hover:-translate-y-2 transition-transform group">
                    <span class="material-symbols-outlined text-secondary text-3xl md:text-4xl mb-4" style="font-variation-settings: 'FILL' 1;">layers</span>
                    <div class="h-2 w-20 bg-secondary/20 rounded-full mb-3"></div>
                    <div class="h-2 w-12 bg-secondary/10 rounded-full"></div>
                </div>
                <div class="bg-surface-container-lowest p-6 md:p-8 rounded-2xl premium-shadow border border-outline-variant/10 mt-4 transform hover:-translate-y-2 transition-transform group">
                    <span class="material-symbols-outlined text-primary-fixed text-3xl md:text-4xl mb-4" style="font-variation-settings: 'FILL' 1;">hub</span>
                    <div class="h-2 w-10 bg-primary-fixed/20 rounded-full mb-3"></div>
                    <div class="h-2 w-16 bg-primary-fixed/10 rounded-full"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modules Grid -->
<section id="discovery" class="py-24 md:py-32 px-6 md:px-12 bg-surface">
    <div class="max-w-7xl mx-auto space-y-16 md:space-y-24">
        <div class="flex flex-col md:flex-row justify-between items-end gap-8 mb-12 md:mb-16">
            <div class="max-w-xl space-y-4">
                <h2 class="text-3xl md:text-5xl font-extrabold text-on-surface font-headline tracking-tight">Powerful Specialized Modules</h2>
                <p class="text-on-surface-variant text-base md:text-lg leading-relaxed font-medium">Enterprise-grade capabilities tailored for every facet of your people management strategy.</p>
            </div>
            <div class="hidden md:flex gap-3 pb-2">
                <div class="w-3 h-3 rounded-full bg-primary premium-shadow"></div>
                <div class="w-3 h-3 rounded-full bg-outline-variant/30"></div>
                <div class="w-3 h-3 rounded-full bg-outline-variant/30"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            <!-- HR Core -->
            <div class="bg-surface-container-low p-8 rounded-[2rem] border border-outline-variant/15 space-y-6 hover:bg-surface-bright transition-all premium-shadow group">
                <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-3xl">badge</span>
                </div>
                <h3 class="text-2xl font-bold text-on-surface font-headline">HR Core</h3>
                <p class="text-sm md:text-base text-on-surface-variant leading-relaxed font-medium">The foundation of your organization's data and structure.</p>
                <ul class="space-y-3 mb-8 pt-4">
                    <li class="flex items-center gap-3 text-sm font-bold text-on-surface">
                        <span class="material-symbols-outlined text-tertiary text-lg" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                        Employee profiles
                    </li>
                    <li class="flex items-center gap-3 text-sm font-bold text-on-surface">
                        <span class="material-symbols-outlined text-tertiary text-lg" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                        Document management
                    </li>
                </ul>
                <a class="text-primary font-black uppercase tracking-widest text-xs flex items-center gap-2 group-hover:gap-3 transition-all" href="#">
                    Learn More <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>

            <!-- Attendance -->
            <div class="bg-surface-container-low p-8 rounded-[2rem] border border-outline-variant/15 space-y-6 hover:bg-surface-bright transition-all premium-shadow group">
                <div class="w-14 h-14 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-3xl">timer</span>
                </div>
                <h3 class="text-2xl font-bold text-on-surface font-headline">Attendance</h3>
                <p class="text-sm md:text-base text-on-surface-variant leading-relaxed font-medium">Real-time tracking for the distributed and hybrid workforce.</p>
                <ul class="space-y-3 mb-8 pt-4">
                    <li class="flex items-center gap-3 text-sm font-bold text-on-surface">
                        <span class="material-symbols-outlined text-tertiary text-lg" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                        Smart Check-in/out
                    </li>
                    <li class="flex items-center gap-3 text-sm font-bold text-on-surface">
                        <span class="material-symbols-outlined text-tertiary text-lg" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                        Biometric & Geo-fencing
                    </li>
                </ul>
                <a class="text-primary font-black uppercase tracking-widest text-xs flex items-center gap-2 group-hover:gap-3 transition-all" href="#">
                    Learn More <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>

            <!-- Leave Management -->
            <div class="bg-surface-container-low p-8 rounded-[2rem] border border-outline-variant/15 space-y-6 hover:bg-surface-bright transition-all premium-shadow group">
                <div class="w-14 h-14 rounded-xl bg-tertiary/10 flex items-center justify-center text-tertiary group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-3xl">event_available</span>
                </div>
                <h3 class="text-2xl font-bold text-on-surface font-headline">Leave Management</h3>
                <p class="text-sm md:text-base text-on-surface-variant leading-relaxed font-medium">Simplify time-off requests with automated policy enforcement.</p>
                <ul class="space-y-3 mb-8 pt-4">
                    <li class="flex items-center gap-3 text-sm font-bold text-on-surface">
                        <span class="material-symbols-outlined text-tertiary text-lg" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                        Approval workflows
                    </li>
                    <li class="flex items-center gap-3 text-sm font-bold text-on-surface">
                        <span class="material-symbols-outlined text-tertiary text-lg" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                        Accrual tracking
                    </li>
                </ul>
                <a class="text-primary font-black uppercase tracking-widest text-xs flex items-center gap-2 group-hover:gap-3 transition-all" href="#">
                    Learn More <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>
        </div>

        <!-- Reports & Analytics Wide Card -->
        <div class="mt-16 bg-surface-container-low p-8 md:p-12 rounded-[2.5rem] md:rounded-[3rem] border border-outline-variant/15 flex flex-col lg:flex-row items-center justify-between gap-10 premium-shadow">
            <div class="flex-1 space-y-6 md:space-y-8">
                <div class="flex items-center gap-4 md:gap-6">
                    <div class="p-4 bg-primary/10 rounded-2xl text-primary">
                        <span class="material-symbols-outlined text-4xl">analytics</span>
                    </div>
                    <h3 class="font-headline text-3xl md:text-4xl font-extrabold text-on-surface">Reports & Analytics</h3>
                </div>
                <p class="text-on-surface-variant text-base md:text-lg max-w-2xl leading-relaxed font-medium">Turn workforce data into strategic insights with custom HR dashboards and predictive turnover trends using our advanced architecture.</p>
                <div class="flex flex-wrap gap-4 md:gap-6">
                    <span class="bg-surface-container-lowest px-5 py-2.5 rounded-xl text-[10px] md:text-xs font-black uppercase tracking-widest text-on-surface flex items-center gap-3 border border-outline-variant/10 premium-shadow">
                        <span class="w-2.5 h-2.5 rounded-full bg-primary animate-pulse"></span> Custom Dashboards
                    </span>
                    <span class="bg-surface-container-lowest px-5 py-2.5 rounded-xl text-[10px] md:text-xs font-black uppercase tracking-widest text-on-surface flex items-center gap-3 border border-outline-variant/10 premium-shadow">
                        <span class="w-2.5 h-2.5 rounded-full bg-tertiary animate-pulse"></span> Predictive Trends
                    </span>
                </div>
            </div>
            <div class="w-full lg:w-auto">
                <a href="#" class="w-full lg:w-auto primary-gradient text-on-primary px-10 py-5 rounded-2xl font-black uppercase tracking-widest text-sm shadow-xl inline-block text-center hover:scale-105 transition-transform">See Insights</a>
            </div>
        </div>
    </div>
</section>

<!-- 'Pick Your Mix' Section -->
<section class="py-24 md:py-40 px-6 bg-surface-container-low border-y border-outline-variant/15 overflow-hidden">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-16 lg:gap-24">
        <div class="w-full lg:w-1/2 space-y-10 md:space-y-12">
            <h2 class="text-4xl md:text-6xl font-extrabold text-on-surface font-headline tracking-tight leading-[1.1]">Pick Your <span class="text-gradient">Mix.</span></h2>
            <p class="text-on-surface-variant text-lg md:text-xl leading-relaxed font-medium">
                Why pay for what you don't use? Build a tailored HR environment by selecting the modules that matter most to your business operations.
            </p>
            <div class="space-y-6 md:space-y-8">
                <div class="flex items-start gap-6 p-6 md:p-8 rounded-[2rem] bg-surface-container-lowest premium-shadow border border-primary/10 group">
                    <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center font-black text-xl group-hover:scale-110 transition-transform">1</div>
                    <div class="space-y-2">
                        <h4 class="font-headline font-bold text-on-surface text-xl md:text-2xl">Select Foundation</h4>
                        <p class="text-on-surface-variant text-base font-medium">Start with HR Core or Attendance modules as your architectural base.</p>
                    </div>
                </div>
                <div class="flex items-start gap-6 p-6 md:p-8 rounded-[2rem] border border-outline-variant/20 hover:bg-surface-container-lowest transition-all group">
                    <div class="w-12 h-12 rounded-full bg-outline-variant/20 text-on-surface-variant flex items-center justify-center font-black text-xl group-hover:bg-primary/10 group-hover:text-primary transition-all">2</div>
                    <div class="space-y-2">
                        <h4 class="font-headline font-bold text-on-surface text-xl md:text-2xl">Add Growth Modules</h4>
                        <p class="text-on-surface-variant text-base font-medium">Stack Payroll, Recruitment, or Performance management modules.</p>
                    </div>
                </div>
                <div class="flex items-start gap-6 p-6 md:p-8 rounded-[2rem] border border-outline-variant/20 hover:bg-surface-container-lowest transition-all group">
                    <div class="w-12 h-12 rounded-full bg-outline-variant/20 text-on-surface-variant flex items-center justify-center font-black text-xl group-hover:bg-primary/10 group-hover:text-primary transition-all">3</div>
                    <div class="space-y-2">
                        <h4 class="font-headline font-bold text-on-surface text-xl md:text-2xl">Deploy Instantly</h4>
                        <p class="text-on-surface-variant text-base font-medium">Fully integrated environment with a single, secure enterprise login.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full lg:w-1/2 relative bg-surface-dim rounded-[3rem] p-8 md:p-16 min-h-[450px] md:min-h-[550px] flex items-center justify-center border border-outline-variant/10 premium-shadow mt-12 lg:mt-0">
            <div class="absolute inset-0 overflow-hidden rounded-[3rem] opacity-30">
                <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary-container rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-tertiary-container rounded-full blur-3xl"></div>
            </div>
            <!-- Interactive preview visual -->
            <div class="relative grid grid-cols-2 gap-4 md:gap-6 w-full">
                <div class="col-span-2 bg-surface-container-lowest/90 backdrop-blur-xl p-6 rounded-2xl shadow-xl border border-white/40 flex items-center justify-between mb-2">
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-outlined text-primary text-3xl" style="font-variation-settings: 'FILL' 1;">check_box</span>
                        <span class="font-headline font-black text-on-surface uppercase tracking-widest text-sm">Module Builder</span>
                    </div>
                    <span class="text-[10px] font-black text-primary px-3 py-1.5 bg-primary/10 rounded-full premium-shadow uppercase tracking-tighter">3 Active</span>
                </div>
                <div class="bg-surface-container-lowest p-6 rounded-2xl shadow-lg border-t-4 border-primary hover:-translate-y-1 transition-transform cursor-pointer">
                    <div class="flex items-center justify-between mb-4">
                        <span class="material-symbols-outlined text-primary text-3xl">badge</span>
                        <span class="material-symbols-outlined text-primary text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    </div>
                    <div class="text-xs font-bold text-on-surface mb-1">HR Core</div>
                    <div class="text-[9px] text-on-surface-variant uppercase tracking-widest font-label font-black">Active</div>
                </div>
                <div class="bg-surface-container-lowest p-6 rounded-2xl shadow-lg border-t-4 border-tertiary hover:-translate-y-1 transition-transform cursor-pointer">
                    <div class="flex items-center justify-between mb-4">
                        <span class="material-symbols-outlined text-tertiary text-3xl">trending_up</span>
                        <span class="material-symbols-outlined text-tertiary text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    </div>
                    <div class="text-xs font-bold text-on-surface mb-1">Strategy</div>
                    <div class="text-[9px] text-on-surface-variant uppercase tracking-widest font-label font-black">Active</div>
                </div>
                <div class="bg-surface-container-lowest p-6 rounded-2xl shadow-lg border-t-4 border-secondary hover:-translate-y-1 transition-transform cursor-pointer">
                    <div class="flex items-center justify-between mb-4">
                        <span class="material-symbols-outlined text-secondary text-3xl">payments</span>
                        <span class="material-symbols-outlined text-secondary text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    </div>
                    <div class="text-xs font-bold text-on-surface mb-1">Payroll</div>
                    <div class="text-[9px] text-on-surface-variant uppercase tracking-widest font-label font-black">Active</div>
                </div>
                <div class="bg-white/40 backdrop-blur-sm p-6 rounded-2xl border-2 border-dashed border-outline-variant/40 flex flex-col items-center justify-center gap-3 group cursor-pointer hover:bg-white/60 transition-all">
                    <span class="material-symbols-outlined text-outline-variant text-3xl group-hover:scale-110 transition-transform">add_circle</span>
                    <div class="text-[9px] font-black text-on-surface-variant uppercase tracking-widest font-label">Add Module</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Final CTA -->
<section class="py-24 md:py-40 px-6 relative overflow-hidden bg-surface">
    <div class="max-w-4xl mx-auto text-center space-y-10 md:space-y-12 relative z-10">
        <h2 class="text-4xl md:text-7xl font-extrabold text-on-surface font-headline tracking-tight leading-[1.1]">Ready to build your <br><span class="text-gradient">custom</span> HR suite?</h2>
        <p class="text-on-surface-variant text-lg md:text-xl max-w-2xl mx-auto leading-relaxed font-medium">Join 1,200+ companies that have streamlined their HR operations with our modular cloud platform built for architectural excellence.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-6 pt-6">
            <a href="/contact" class="px-12 py-5 bg-primary text-on-primary font-bold rounded-2xl shadow-xl hover:scale-105 transition-transform primary-gradient text-center">Book a Demo</a>
            <a href="/contact" class="px-12 py-5 bg-surface-container-lowest text-on-surface font-bold rounded-2xl border border-outline-variant/15 hover:bg-surface-container-low transition-all premium-shadow text-center">Talk to Sales</a>
        </div>
    </div>
</section>
@endsection
