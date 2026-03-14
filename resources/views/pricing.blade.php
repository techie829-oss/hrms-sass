@extends('layouts.landing')

@section('title', 'Pricing | HRMS Solutions')

@section('content')
<!-- Pricing Hero -->
<section x-data="{ yearly: true }" class="py-20 md:py-32 px-6 md:px-12 bg-hero-gradient overflow-hidden">
    <div class="max-w-7xl mx-auto text-center space-y-6 md:space-y-8 relative z-10">
        <div class="inline-flex items-center px-4 py-1.5 bg-primary/10 text-primary rounded-full text-[10px] md:text-xs font-bold tracking-widest uppercase premium-shadow border border-primary/10">
            Hybrid Infrastructure
        </div>
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-on-surface leading-[1.1] tracking-tight font-headline">
            Architect Your Workforce <br class="hidden md:block"><span class="text-gradient">Without Compromise.</span>
        </h1>
        <p class="text-base md:text-xl text-on-surface-variant max-w-2xl mx-auto font-medium leading-relaxed">
            Choose between shared scalability or dedicated isolation. All plans include our core HR engine with architectural precision.
        </p>

        <!-- Toggle -->
        <div class="flex items-center justify-center gap-4 pt-6 md:pt-8">
            <span class="text-sm font-bold text-on-surface font-label uppercase tracking-widest" :class="!yearly ? 'text-primary' : 'text-on-surface/60'">Monthly</span>
            <div @click="yearly = !yearly" class="w-14 h-7 bg-surface-container-high rounded-full relative p-1 cursor-pointer border border-outline-variant/20 premium-shadow group">
                <div class="w-5 h-5 bg-primary rounded-full absolute transition-all duration-300 shadow-sm group-hover:scale-110" :class="yearly ? 'right-1' : 'left-1'"></div>
            </div>
            <span class="text-sm font-bold text-on-surface font-label uppercase tracking-widest" :class="yearly ? 'text-primary' : 'text-on-surface/60'">Yearly</span>
            <span class="px-3 py-1 bg-tertiary/10 text-tertiary text-[10px] font-black rounded-full uppercase tracking-tighter border border-tertiary/20">Save 20%</span>
        </div>
    </div>
</section>

<!-- Pricing Cards -->
<section class="pb-32 px-6 md:px-12 bg-surface">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <!-- Free -->
        <div class="bg-surface-container-low p-8 rounded-[2rem] border border-outline-variant/15 flex flex-col space-y-10 hover:bg-surface-bright transition-all premium-shadow group">
            <div class="space-y-2">
                <p class="text-[10px] font-bold text-outline uppercase tracking-[0.2em] font-label">Entry Tier</p>
                <h3 class="text-4xl font-extrabold text-on-surface font-headline">₹0<span class="text-sm font-medium text-on-surface-variant ml-1 uppercase tracking-widest">/mo</span></h3>
            </div>
            <ul class="space-y-5 flex-1">
                <li class="flex items-start gap-3 text-sm text-on-surface font-medium leading-relaxed">
                    <span class="material-symbols-outlined text-tertiary text-xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    <span>10 employee sanctuary</span>
                </li>
                <li class="flex items-start gap-3 text-sm text-on-surface font-medium leading-relaxed">
                    <span class="material-symbols-outlined text-tertiary text-xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    <span>3 core modules</span>
                </li>
                <li class="flex items-start gap-3 text-sm text-on-surface-variant leading-relaxed">
                    <span class="material-symbols-outlined text-xl">database</span>
                    <span>Shared Tenancy</span>
                </li>
            </ul>
            <a href="/contact" class="w-full py-4 bg-surface-container-lowest text-on-surface font-black uppercase tracking-widest text-xs rounded-xl border border-outline-variant/15 hover:bg-surface-container-low transition-all text-center premium-shadow group-hover:scale-[1.02]">Request Demo</a>
        </div>

        <!-- Starter -->
        <div class="bg-surface-container-low p-8 rounded-[2rem] border border-outline-variant/15 flex flex-col space-y-10 hover:bg-surface-bright transition-all premium-shadow group">
            <div class="space-y-2">
                <p class="text-[10px] font-bold text-outline uppercase tracking-[0.2em] font-label">Growth Tier</p>
                <h3 class="text-4xl font-extrabold text-on-surface font-headline">
                    <span x-text="yearly ? '₹799' : '₹999'"></span><span class="text-sm font-medium text-on-surface-variant ml-1 uppercase tracking-widest">/mo</span>
                </h3>
            </div>
            <ul class="space-y-5 flex-1">
                <li class="flex items-start gap-3 text-sm text-on-surface font-medium leading-relaxed">
                    <span class="material-symbols-outlined text-tertiary text-xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    <span>50 employee sanctuary</span>
                </li>
                <li class="flex items-start gap-3 text-sm text-on-surface font-medium leading-relaxed">
                    <span class="material-symbols-outlined text-tertiary text-xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    <span>5 core modules</span>
                </li>
                <li class="flex items-start gap-3 text-sm text-on-surface-variant leading-relaxed">
                    <span class="material-symbols-outlined text-xl">mail</span>
                    <span>Email Priority Support</span>
                </li>
            </ul>
            <a href="/contact" class="w-full py-4 bg-surface-container-lowest text-on-surface font-black uppercase tracking-widest text-xs rounded-xl border border-outline-variant/15 hover:bg-surface-container-low transition-all text-center premium-shadow group-hover:scale-[1.02]">Choose Starter</a>
        </div>

        <!-- Professional -->
        <div class="bg-surface-container-low p-8 rounded-[2rem] border-4 border-primary ring-8 ring-primary/5 flex flex-col space-y-10 relative premium-shadow group scale-105 z-10">
            <div class="absolute -top-5 left-1/2 -translate-x-1/2 bg-primary px-5 py-1.5 rounded-full text-[10px] font-black text-on-primary uppercase tracking-widest whitespace-nowrap">Architect Choice</div>
            <div class="space-y-2">
                <p class="text-[10px] font-black text-primary uppercase tracking-[0.2em] font-label">Enterprise Ready</p>
                <h3 class="text-4xl font-extrabold text-on-surface font-headline">
                    <span x-text="yearly ? '₹2,399' : '₹2,999'"></span><span class="text-sm font-medium text-on-surface-variant ml-1 uppercase tracking-widest">/mo</span>
                </h3>
            </div>
            <ul class="space-y-5 flex-1">
                <li class="flex items-start gap-3 text-sm text-on-surface font-bold leading-relaxed">
                    <span class="material-symbols-outlined text-tertiary text-xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    <span>200 employee sanctuary</span>
                </li>
                <li class="flex items-start gap-3 text-sm text-on-surface font-bold leading-relaxed">
                    <span class="material-symbols-outlined text-tertiary text-xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    <span>Unlimited Module Access</span>
                </li>
                <li class="flex items-start gap-3 text-sm text-primary font-black leading-relaxed">
                    <span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">terminal</span>
                    <span>Dedicated Infrastructure</span>
                </li>
            </ul>
            <a href="/contact" class="w-full py-4 primary-gradient text-on-primary font-black uppercase tracking-widest text-xs rounded-xl shadow-xl hover:opacity-90 transition-all text-center premium-shadow group-hover:scale-[1.02]">Start Professional</a>
        </div>

        <!-- Enterprise -->
        <div class="bg-inverse-surface p-8 rounded-[2rem] flex flex-col space-y-10 premium-shadow group">
            <div class="space-y-2">
                <p class="text-[10px] font-bold text-inverse-on-surface uppercase tracking-[0.2em] font-label">Custom Scale</p>
                <h3 class="text-4xl font-extrabold text-white font-headline">
                    <span x-text="yearly ? '₹6,399+' : '₹7,999+'"></span><span class="text-sm font-medium text-inverse-on-surface ml-1 uppercase tracking-widest">/mo</span>
                </h3>
            </div>
            <ul class="space-y-5 flex-1">
                <li class="flex items-start gap-3 text-sm text-white font-medium leading-relaxed">
                    <span class="material-symbols-outlined text-tertiary-fixed text-xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    <span>Infinite employee capacity</span>
                </li>
                <li class="flex items-start gap-3 text-sm text-inverse-on-surface font-medium leading-relaxed">
                    <span class="material-symbols-outlined text-xl">verified_user</span>
                    <span>White-glove data migration</span>
                </li>
                <li class="flex items-start gap-3 text-sm text-inverse-on-surface font-medium leading-relaxed">
                    <span class="material-symbols-outlined text-xl">support_agent</span>
                    <span>Dedicated Success Partner</span>
                </li>
            </ul>
            <a href="#" class="w-full py-4 bg-white/10 text-white font-black uppercase tracking-widest text-xs rounded-xl border border-white/20 hover:bg-white/20 transition-all text-center group-hover:scale-[1.02]">Connect with Sales</a>
        </div>
    </div>
</section>

<!-- Module Add-ons -->
<section class="py-32 px-6 md:px-12 bg-surface-container-low border-y border-outline-variant/15">
    <div class="max-w-7xl mx-auto space-y-20">
        <div class="flex flex-col md:flex-row justify-between items-end gap-10">
            <div class="max-w-xl space-y-6">
                <h2 class="text-3xl md:text-5xl font-extrabold text-on-surface tracking-tight font-headline">Refine Your <br><span class="text-tertiary">Architectural Stack.</span></h2>
                <p class="text-on-surface-variant text-lg font-medium leading-relaxed">Expand your capabilities with specialized modules designed for complex administrative workflows. Precision-engineered for every role.</p>
            </div>
            <div class="px-5 py-2.5 bg-primary/10 text-primary text-[10px] font-black rounded-xl tracking-widest uppercase border border-primary/10 premium-shadow">Modular Precision</div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="p-10 bg-surface-container-lowest rounded-[2.5rem] border border-outline-variant/15 space-y-8 group hover:bg-surface-bright transition-all premium-shadow border-b-8 border-b-primary/20">
                <div class="p-4 bg-primary/10 rounded-2xl w-fit text-primary group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-4xl">payments</span>
                </div>
                <div class="space-y-4">
                    <h4 class="text-2xl font-bold text-on-surface font-headline">Global Payroll</h4>
                    <p class="text-sm text-on-surface-variant font-medium leading-relaxed">Automated multi-currency compliance, localized tax filings, and instant disbursal.</p>
                </div>
                <div class="pt-6 border-t border-outline-variant/10">
                    <p class="text-2xl font-bold text-on-surface font-headline">₹499 <span class="text-[10px] text-on-surface-variant uppercase tracking-[0.2em] font-label font-black ml-1">/active user</span></p>
                </div>
            </div>
            <div class="p-10 bg-surface-container-lowest rounded-[2.5rem] border border-outline-variant/15 space-y-8 group hover:bg-surface-bright transition-all premium-shadow border-b-8 border-b-secondary/20">
                <div class="p-4 bg-secondary/10 rounded-2xl w-fit text-secondary group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-4xl">person_search</span>
                </div>
                <div class="space-y-4">
                    <h4 class="text-2xl font-bold text-on-surface font-headline">Recruitment Pro</h4>
                    <p class="text-sm text-on-surface-variant font-medium leading-relaxed">AI-driven candidate matching, automated screening, and seamless onboarding pipelines.</p>
                </div>
                <div class="pt-6 border-t border-outline-variant/10">
                    <p class="text-2xl font-bold text-on-surface font-headline">₹349 <span class="text-[10px] text-on-surface-variant uppercase tracking-[0.2em] font-label font-black ml-1">/active user</span></p>
                </div>
            </div>
            <div class="p-10 bg-surface-container-lowest rounded-[2.5rem] border border-outline-variant/15 space-y-8 group hover:bg-surface-bright transition-all premium-shadow border-b-8 border-b-tertiary/20">
                <div class="p-4 bg-tertiary/10 rounded-2xl w-fit text-tertiary group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-4xl">query_stats</span>
                </div>
                <div class="space-y-4">
                    <h4 class="text-2xl font-bold text-on-surface font-headline">Strategy Engine</h4>
                    <p class="text-sm text-on-surface-variant font-medium leading-relaxed">360 degree feedback loops, strategic skill mapping, and predictive retention analytics.</p>
                </div>
                <div class="pt-6 border-t border-outline-variant/10">
                    <p class="text-2xl font-bold text-on-surface font-headline">₹299 <span class="text-[10px] text-on-surface-variant uppercase tracking-[0.2em] font-label font-black ml-1">/active user</span></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing FAQ -->
<section class="py-32 px-6 md:px-12 bg-surface">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-20">
        <div class="lg:col-span-1 space-y-10 lg:sticky lg:top-32 h-fit">
            <h2 class="text-4xl md:text-5xl font-extrabold text-on-surface tracking-tight font-headline leading-tight">Common <br><span class="text-gradient">Inquiries.</span></h2>
            <p class="text-on-surface-variant text-lg font-medium leading-relaxed">Find clarity on our billing cycles, module upgrades, and administrative compliance architecture.</p>
            <div class="p-10 bg-surface-container-low rounded-[2.5rem] border border-outline-variant/15 space-y-6 premium-shadow">
                <p class="font-bold text-primary text-lg">Still seeking clarity?</p>
                <a href="/contact" class="inline-flex items-center gap-3 text-sm font-black uppercase tracking-widest text-on-surface hover:text-primary transition-all group">
                    Contact Specialist <span class="material-symbols-outlined text-lg group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </a>
            </div>
        </div>
        <div class="lg:col-span-2 space-y-8">
            <div class="space-y-6 p-8 md:p-10 bg-surface-container-lowest rounded-[2.5rem] border border-outline-variant/10 hover:border-primary/30 transition-all premium-shadow group">
                <p class="text-primary font-bold font-headline text-xl flex items-center gap-4">
                    <span class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-xs">01</span>
                    Can I switch plans later?
                </p>
                <p class="text-sm md:text-base text-on-surface-variant leading-relaxed font-medium pl-12">Yes, administrative structures are fluid. You can upgrade or downgrade your plan at any time. Upgrades take effect immediately with pro-rated billing, while downgrades are applied at the start of your next cycle.</p>
            </div>
            <div class="space-y-6 p-8 md:p-10 bg-surface-container-lowest rounded-[2.5rem] border border-outline-variant/10 hover:border-primary/30 transition-all premium-shadow group">
                <p class="text-primary font-bold font-headline text-xl flex items-center gap-4">
                    <span class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-xs">02</span>
                    What is the difference between Shared and Dedicated tenancy?
                </p>
                <p class="text-sm md:text-base text-on-surface-variant leading-relaxed font-medium pl-12">Shared tenancy runs on high-performance clustered infrastructure suitable for most SMBs. Dedicated tenancy provides a completely isolated database and application environment for maximum security and performance customization, recommended for Professional and Enterprise tiers.</p>
            </div>
            <div class="space-y-6 p-8 md:p-10 bg-surface-container-lowest rounded-[2.5rem] border border-outline-variant/10 hover:border-primary/30 transition-all premium-shadow group">
                <p class="text-primary font-bold font-headline text-xl flex items-center gap-4">
                    <span class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-xs">03</span>
                    How do module limits work in Starter?
                </p>
                <p class="text-sm md:text-base text-on-surface-variant leading-relaxed font-medium pl-12">The Starter plan allows you to select any 5 modules from our ecosystem. You can swap these modules once per quarter to suit your seasonal HR needs or tactical shifts.</p>
            </div>
        </div>
    </div>
</section>

<!-- Final CTA -->
<section class="py-20 px-6 md:px-12">
    <div class="max-w-7xl mx-auto rounded-[3rem] md:rounded-[4rem] bg-inverse-surface p-12 lg:p-24 relative overflow-hidden group">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-12">
            <div class="text-center md:text-left space-y-6">
                <h2 class="text-3xl md:text-5xl font-extrabold text-white font-headline tracking-tight leading-tight">Ready to architect <br class="hidden md:block">your workforce?</h2>
                <p class="text-inverse-on-surface text-lg font-medium opacity-90">Join 2,500+ forward-thinking organizations building the future of work.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-6">
                <a href="/contact" class="px-10 py-5 primary-gradient text-on-primary font-black uppercase tracking-widest text-xs rounded-2xl shadow-xl hover:scale-105 transition-transform premium-shadow text-center">Request Architecture Demo</a>
                <a href="/contact" class="px-10 py-5 bg-white/10 text-white font-black uppercase tracking-widest text-xs rounded-2xl border border-white/20 hover:bg-white/20 transition-all text-center">Contact Expert</a>
            </div>
        </div>
        <!-- Texture -->
        <div class="absolute inset-0 opacity-10 pointer-events-none group-hover:scale-110 transition-transform duration-[5000ms]">
            <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_30%_50%,#0053cd_0%,transparent_50%)]"></div>
            <div class="absolute bottom-0 right-0 w-full h-full bg-[radial-gradient(circle_at_70%_50%,#8c3a8c_0%,transparent_50%)]"></div>
        </div>
    </div>
</section>
@endsection
