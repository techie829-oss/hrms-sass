@extends('layouts.landing')

@section('title', 'HRMS Solutions | Kinetic Horizon v2.0')

@section('content')
<!-- Animated Background Blobs -->
<div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
    <div class="absolute top-0 left-1/4 w-[40rem] h-[40rem] bg-primary/20 rounded-full mix-blend-multiply filter blur-[100px] opacity-70 animate-[blob_10s_infinite_alternate]"></div>
    <div class="absolute top-1/4 right-1/4 w-[30rem] h-[30rem] bg-tertiary/20 rounded-full mix-blend-multiply filter blur-[100px] opacity-70 animate-[blob_15s_infinite_alternate-reverse]"></div>
    <div class="absolute -bottom-32 left-1/2 w-[40rem] h-[40rem] bg-secondary/20 rounded-full mix-blend-multiply filter blur-[100px] opacity-70 animate-[blob_12s_infinite_alternate]"></div>
</div>

<div class="relative z-10">
    <!-- Hero Section -->
    <section class="landing-hero bg-transparent">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            <div class="lg:col-span-7">
                <div class="reveal inline-flex items-center gap-2 px-3 py-1 mb-6 md:mb-8 rounded-full bg-surface-container-high/50 backdrop-blur-md border border-primary/20 shadow-xl">
                    <span class="flex h-2 w-2 rounded-full bg-tertiary animate-pulse"></span>
                    <span class="text-[10px] md:text-[11px] font-bold tracking-wider uppercase text-on-surface font-label">New v2.0 Kinetic Update</span>
                </div>
                <h1 class="reveal reveal-delay-100 text-5xl md:text-7xl lg:text-8xl font-extrabold tracking-tight font-headline leading-[1.05] mb-6 md:mb-8 drop-shadow-sm">
                    Simplify Your HR, <br class="hidden md:block"> <span class="landing-gradient-text">Empower</span> Your People.
                </h1>
                <p class="reveal reveal-delay-200 text-lg md:text-xl text-on-surface-variant max-w-xl mb-8 md:mb-10 leading-relaxed font-medium">
                    Manage payroll, attendance, and recruitment in one unified sanctuary. Built for scaling teams that value architectural growth and talent experience.
                </p>
                <div class="reveal reveal-delay-300 flex flex-col sm:flex-row gap-4">
                    <a href="/contact" class="landing-btn-primary group">
                        Book a Demo <span class="inline-block transition-transform group-hover:translate-x-1 ml-1">→</span>
                    </a>
                    <a href="/modules" class="landing-btn-secondary">
                        <span class="material-symbols-outlined text-primary group-hover:rotate-12 transition-transform">widgets</span>
                        Explore Modules
                    </a>
                </div>
            </div>
            <div class="lg:col-span-5 relative mt-12 lg:mt-0 reveal reveal-delay-400">
                <div class="w-full aspect-[4/5] rounded-[2.5rem] overflow-hidden shadow-2xl bg-surface-container-lowest/80 backdrop-blur-xl border border-white/40 p-2 transform lg:rotate-2 hover:rotate-0 transition-transform duration-700 animate-[float_6s_ease-in-out_infinite]">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBiSFE24h9pkSEz2X87ZhwkiHwD1uHcGFmzK93mdOxA373fcK1kj1PIirFwxxJrpi1F-6WABhxBjDgXPbMvXLhxzvrm2oSsE7sicFKnqJzlMHlHOOaaAUYmzYYiPWEPsFBavQCqdtuvf_SHvEwEmdGhGGg0uxdPWp5jincupFeTcJLh9zWNtXiu4Y1KAaIAbUldDl5xZQA1zhBlx1r5zwruDJ6w3pjUg7Iq8kXcLamdCbk0UDA6ycJi5Ju5rhqO9kt4EVfUSECI5IBm" alt="Strategic HR Dashboard" class="w-full h-full object-cover rounded-[2rem] shadow-inner">
                </div>
                <!-- Floating Data Card -->
                <div class="absolute -bottom-6 -left-4 md:-bottom-8 md:-left-12 p-5 md:p-6 bg-surface-container-lowest/90 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/50 max-w-[200px] md:max-w-[240px] animate-[float_8s_ease-in-out_infinite_reverse]">
                    <div class="flex items-center gap-3 mb-3 md:mb-4">
                        <div class="p-2 bg-gradient-to-br from-tertiary to-primary rounded-lg text-white shadow-lg">
                            <span class="material-symbols-outlined text-sm md:text-base">analytics</span>
                        </div>
                        <span class="text-[10px] md:text-xs font-bold text-on-surface uppercase tracking-widest font-label">Engagement</span>
                    </div>
                    <div class="text-3xl md:text-4xl font-extrabold font-headline mb-1 text-on-surface">+24.8%</div>
                    <div class="text-[10px] md:text-[11px] text-on-surface-variant font-medium">Growth in cultural alignment</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trusted By Section (Marquee) -->
    <section class="py-12 md:py-16 bg-surface-container-lowest/50 backdrop-blur-lg border-y border-outline-variant/15 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <p class="text-center text-[10px] font-bold tracking-[0.2em] text-on-surface-variant uppercase mb-10 md:mb-12">Architecting growth for industry leaders</p>
            <div class="marquee-container">
                <div class="marquee-content opacity-50 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-500">
                    <div class="text-on-surface font-headline font-extrabold text-2xl tracking-tighter mx-8">LUMINA</div>
                    <div class="text-on-surface font-headline font-extrabold text-2xl tracking-tighter mx-8">FORGE</div>
                    <div class="text-on-surface font-headline font-extrabold text-2xl tracking-tighter mx-8">AETHER</div>
                    <div class="text-on-surface font-headline font-extrabold text-2xl tracking-tighter mx-8">ORBIT</div>
                    <div class="text-on-surface font-headline font-extrabold text-2xl tracking-tighter mx-8">VELOCITY</div>
                    <div class="text-on-surface font-headline font-extrabold text-2xl tracking-tighter mx-8">NEXUS</div>
                    <div class="text-on-surface font-headline font-extrabold text-2xl tracking-tighter mx-8">LUMINA</div>
                    <div class="text-on-surface font-headline font-extrabold text-2xl tracking-tighter mx-8">FORGE</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Feature Bento Grid -->
    <section class="landing-section-spacing bg-transparent">
        <div class="max-w-7xl mx-auto px-6">
            <div class="mb-12 md:mb-16 space-y-4 reveal">
                <h2 class="text-4xl md:text-6xl font-extrabold font-headline tracking-tight">Curation at <span class="landing-gradient-text">Scale.</span></h2>
                <p class="text-on-surface-variant text-lg md:text-xl leading-relaxed max-w-2xl font-medium">Everything you need to manage your most valuable asset, presented with editorial clarity and intentional flow.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 md:gap-8">
                <!-- Large Feature Card -->
                <div class="md:col-span-8 landing-card reveal">
                    <div class="relative z-10 flex flex-col lg:flex-row gap-8 items-center h-full">
                        <div class="flex-1 space-y-6">
                            <div class="inline-flex items-center gap-3 px-4 py-2 bg-primary/10 text-primary rounded-full shadow-inner border border-primary/20">
                                <span class="material-symbols-outlined text-sm md:text-base">badge</span>
                                <span class="text-[10px] md:text-xs font-bold uppercase tracking-wider font-label">Talent Architecture</span>
                            </div>
                            <h3 class="text-3xl md:text-4xl font-bold font-headline text-on-surface tracking-tight">Employee Lifecycle Management</h3>
                            <p class="text-on-surface-variant text-base md:text-lg leading-relaxed font-medium">Centralize profiles with an editorial touch. From precision hiring to long-term career curation.</p>
                            <a href="#" class="inline-flex items-center text-primary font-bold gap-2 group/link text-sm uppercase tracking-wider">
                                Explore Directory 
                                <span class="material-symbols-outlined bg-primary/10 rounded-full p-1 group-hover/link:translate-x-2 transition-transform">chevron_right</span>
                            </a>
                        </div>
                        <div class="flex-1 w-full lg:max-w-sm rounded-xl overflow-hidden border border-outline-variant/20 shadow-2xl">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAX-GkxOqG--KNIW-0vfPCNNpgbAaOpnqDjBUQ5XD4rwQVBEwHbABW1x7lAcwmWv9-pZ1hADpvgsj5bIYAowJ4rV6hqVmj8jDrrCDq6qU0Z6fBV0N9wArkA7qAywYS0tyI-3rRAgUcehQ7FOtOJl7nqLEC4GoJBB-8VDXIYaEYq7sr5Ne6o-x4WrKwRXs5QPOuAhGK3sdlBQ3zDHXzDxwX86KUkotvZulQ0mfwjD0aUyEkd0J34IOOFsMCTQwDY3kPKpYuYsGGFOze-" alt="Employee Directory" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                        </div>
                    </div>
                </div>
                <!-- Small Square Feature -->
                <div class="md:col-span-4 bg-gradient-to-br from-secondary to-[#5D4037] rounded-[2rem] p-8 md:p-10 shadow-2xl text-on-secondary flex flex-col justify-between group hover:-translate-y-2 transition-transform duration-500 reveal reveal-delay-100">
                    <div>
                        <div class="p-4 bg-white/20 backdrop-blur-sm rounded-2xl w-fit mb-6 md:mb-8 group-hover:scale-110 group-hover:rotate-6 transition-transform shadow-lg">
                            <span class="material-symbols-outlined text-white text-4xl">payments</span>
                        </div>
                        <h3 class="text-2xl md:text-3xl font-bold font-headline mb-3 text-white">Smart Payroll</h3>
                        <p class="text-white/80 text-base font-medium leading-relaxed">Automate complex calculations and tax filings with architectural precision.</p>
                    </div>
                    <a class="inline-flex items-center gap-2 font-bold text-sm group/link mt-8 text-white uppercase tracking-wider" href="#">
                        Learn More <span class="material-symbols-outlined group-hover/link:translate-x-2 transition-transform">arrow_forward</span>
                    </a>
                </div>
                <!-- Medium Feature -->
                <div class="md:col-span-4 landing-card reveal">
                    <div class="space-y-6 h-full flex flex-col">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-tertiary/20 to-tertiary/5 flex items-center justify-center text-tertiary group-hover:scale-110 group-hover:-rotate-6 transition-transform shadow-inner border border-tertiary/20">
                            <span class="material-symbols-outlined text-4xl">schedule</span>
                        </div>
                        <h3 class="text-2xl md:text-3xl font-bold text-on-surface font-headline tracking-tight">Time & Attendance</h3>
                        <p class="text-on-surface-variant text-base font-medium leading-relaxed flex-grow">Biometric integration and geo-fencing for the modern, remote-first workforce.</p>
                        <div class="mt-8 flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-tertiary/20 text-tertiary text-[10px] font-bold rounded-full uppercase tracking-wider">Real-time</span>
                            <span class="px-3 py-1 bg-surface-container text-on-surface-variant text-[10px] font-bold rounded-full uppercase tracking-wider">Mobile Ready</span>
                        </div>
                    </div>
                </div>
                <!-- Horizontal Long Feature -->
                <div class="md:col-span-8 bg-gradient-to-r from-primary/5 via-tertiary/5 to-secondary/5 backdrop-blur-md rounded-[2rem] p-8 md:p-10 flex flex-col md:flex-row items-center gap-8 md:gap-12 shadow-xl border border-outline-variant/20 group hover:-translate-y-2 hover:shadow-2xl hover:border-primary/30 transition-all duration-500 reveal reveal-delay-100">
                    <div class="flex-1">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-primary/20 to-primary/5 flex items-center justify-center text-primary mb-6 group-hover:scale-110 group-hover:rotate-12 transition-transform shadow-inner border border-primary/20">
                            <span class="material-symbols-outlined text-4xl">rocket_launch</span>
                        </div>
                        <h3 class="text-2xl md:text-4xl font-bold font-headline mb-4 text-on-surface tracking-tight">Recruitment Suite</h3>
                        <p class="text-on-surface-variant text-base md:text-lg font-medium leading-relaxed mb-6">Find and nurture talent through a seamless pipeline. Integrated job boards and AI-driven screening.</p>
                        <a class="inline-flex items-center text-primary font-bold gap-2 group/link text-sm uppercase tracking-wider" href="#">
                            Open Pipeline <span class="material-symbols-outlined bg-primary/10 rounded-full p-1 group-hover/link:translate-x-2 transition-transform">chevron_right</span>
                        </a>
                    </div>
                    <div class="w-full md:w-72 overflow-hidden rounded-2xl shadow-2xl border border-white/40">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAGzOtfyGlNKc_-LUzawTcSE4ZXMYrsrYMLtiDlh6Cec-KCC5SggxoLNWTtuVTa2fjfR63k4BFjI8ggPPpEFVs1ocECUve1sTIQd1Ip-HjoC5m_ATid0FybH7ZEqj4JuA2gfaRb-RMq4YLck5lia984D5xk3lRQTUd53EKsbPpMA8Ij5jAGBBHgwJxNaXQBETvvcGpdB31xWMETlwJ-Je9ejRL0GDHdscTngJ54ehw5bQPioIrU87maLk745IUmZ-hRZQxKnolFLUUJ" alt="Recruitment" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 hover:scale-105">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dashboard Detail Section -->
    <section class="landing-section-spacing bg-transparent px-6 overflow-hidden">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-16 lg:gap-24">
            <div class="lg:w-1/2 space-y-10 md:space-y-12 reveal">
                <h2 class="text-4xl md:text-6xl font-extrabold font-headline tracking-tight text-on-surface">One Interface.<br class="hidden md:block"><span class="landing-gradient-text">Infinite Control.</span></h2>
                <div class="space-y-6 md:space-y-8">
                    <div class="flex gap-5 md:gap-6 group">
                        <div class="shrink-0 w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-primary/20 to-primary/5 border border-primary/20 text-primary flex items-center justify-center font-bold text-xl shadow-lg group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">1</div>
                        <div>
                            <h4 class="text-xl md:text-2xl font-bold text-on-surface mb-2 font-headline tracking-tight group-hover:text-primary transition-colors">Executive Dashboard</h4>
                            <p class="text-on-surface-variant text-base leading-relaxed font-medium">Real-time analytics on turnover, cost-per-hire, and employee satisfaction levels with editorial clarity.</p>
                        </div>
                    </div>
                    <div class="flex gap-5 md:gap-6 group">
                        <div class="shrink-0 w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-primary/20 to-primary/5 border border-primary/20 text-primary flex items-center justify-center font-bold text-xl shadow-lg group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">2</div>
                        <div>
                            <h4 class="text-xl md:text-2xl font-bold text-on-surface mb-2 font-headline tracking-tight group-hover:text-primary transition-colors">Automated Compliance</h4>
                            <p class="text-on-surface-variant text-base leading-relaxed font-medium">Stay ahead of labor laws with automatic updates and documentation storage in your secure sanctuary.</p>
                        </div>
                    </div>
                    <div class="flex gap-5 md:gap-6 group">
                        <div class="shrink-0 w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-primary/20 to-primary/5 border border-primary/20 text-primary flex items-center justify-center font-bold text-xl shadow-lg group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">3</div>
                        <div>
                            <h4 class="text-xl md:text-2xl font-bold text-on-surface mb-2 font-headline tracking-tight group-hover:text-primary transition-colors">Employee Self-Service</h4>
                            <p class="text-on-surface-variant text-base leading-relaxed font-medium">Empower your team to manage their own leaves, benefits, and payslips through a clean, intuitive portal.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2 relative mt-12 lg:mt-0 reveal reveal-delay-200">
                <div class="bg-surface-container-lowest/80 backdrop-blur-xl p-3 rounded-[3rem] shadow-2xl transform lg:rotate-3 hover:rotate-0 transition-transform duration-700 border border-white/50">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCJPZxP1PPzCKuXPenzg9wJwy5R9VqjAYCCV5VizG7o883_3THlwTR1toegI2_rzrJ1gYNgIv0d4mYK7TRF4_lLSviNEYxw7sLBoufl2_GpxKCQ2DGnxZLinYfWJBbfolte582XcoKzkZqPUrm1VMJqeX8QIEz4LfZhpeQDuHR7K9k2ZDwvpDRMdAoZNfV_GtHihVavHaUvwa_IGN1yDJ7RbRvxO5f6bBGIUq35M-7Zb-DX97oYBhVjhaKqYk9di2thcB7-hwOEY69f" alt="Advanced Analytics" class="rounded-[2.5rem] w-full h-auto shadow-inner">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="landing-section-spacing bg-surface-container-low border-y border-outline-variant/10">
        <div class="max-w-7xl mx-auto px-6 flex flex-col lg:flex-row items-center gap-16 lg:gap-24">
            <div class="lg:w-1/2 relative w-full reveal">
                <div class="grid grid-cols-2 gap-4 md:gap-6">
                    <div class="space-y-4 md:space-y-6 pt-8 md:pt-12">
                        <div class="bg-surface-container-lowest/80 backdrop-blur-md p-8 md:p-10 rounded-[2.5rem] shadow-xl border border-white/40 group hover:-translate-y-2 transition-all duration-300">
                            <span class="text-4xl md:text-6xl font-extrabold font-headline block mb-2 text-primary tracking-tight">98%</span>
                            <p class="text-[10px] md:text-xs font-bold text-outline uppercase tracking-widest font-label">Retention Rate</p>
                        </div>
                        <div class="bg-gradient-to-br from-primary to-tertiary p-8 md:p-10 rounded-[2.5rem] shadow-2xl text-on-primary group hover:-translate-y-2 hover:shadow-primary/30 transition-all duration-300">
                            <span class="text-4xl md:text-6xl font-extrabold font-headline block mb-2 tracking-tight">12M+</span>
                            <p class="text-[10px] md:text-xs font-bold text-on-primary/70 uppercase tracking-widest font-label">Managed Lives</p>
                        </div>
                    </div>
                    <div class="space-y-4 md:space-y-6">
                        <div class="bg-gradient-to-br from-tertiary to-[#8c3a8c] p-8 md:p-10 rounded-[2.5rem] shadow-2xl text-on-tertiary group hover:-translate-y-2 hover:shadow-tertiary/30 transition-all duration-300">
                            <span class="text-4xl md:text-6xl font-extrabold font-headline block mb-2 tracking-tight">4.9/5</span>
                            <p class="text-[10px] md:text-xs font-bold text-on-tertiary/70 uppercase tracking-widest font-label">User Rating</p>
                        </div>
                        <div class="bg-surface-container-lowest/80 backdrop-blur-md p-8 md:p-10 rounded-[2.5rem] shadow-xl border border-white/40 group hover:-translate-y-2 transition-all duration-300">
                            <span class="text-4xl md:text-6xl font-extrabold font-headline block mb-2 text-secondary tracking-tight">25+</span>
                            <p class="text-[10px] md:text-xs font-bold text-outline uppercase tracking-widest font-label">Global Offices</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2 space-y-6 md:space-y-8 reveal reveal-delay-200">
                <div class="inline-block px-4 py-2 bg-secondary/10 text-secondary rounded-full text-[10px] md:text-xs font-bold font-label uppercase tracking-widest border border-secondary/20 shadow-inner">Global Reach</div>
                <h2 class="text-4xl md:text-6xl font-extrabold font-headline tracking-tight text-on-surface">Architected for the modern world.</h2>
                <p class="text-on-surface-variant text-lg md:text-xl leading-relaxed font-medium">
                    Our platform scales with your ambition. Whether you're a boutique studio or a global conglomerate, our architecture adapts to your cultural and operational needs seamlessly.
                </p>
                <div class="space-y-6 pt-4 border-t border-outline-variant/20">
                    <div class="flex items-start gap-5 group mt-6">
                        <div class="p-3 bg-gradient-to-br from-primary/20 to-primary/5 rounded-xl border border-primary/20 text-primary shadow-md group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-2xl">language</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-on-surface font-headline text-xl mb-1 group-hover:text-primary transition-colors">Universal Compliance</h4>
                            <p class="text-base text-on-surface-variant font-medium">Legal framework support for 120+ territories.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-5 group">
                        <div class="p-3 bg-gradient-to-br from-tertiary/20 to-tertiary/5 rounded-xl border border-tertiary/20 text-tertiary shadow-md group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-2xl">payments</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-on-surface font-headline text-xl mb-1 group-hover:text-tertiary transition-colors">Multi-Currency Engine</h4>
                            <p class="text-base text-on-surface-variant font-medium">Real-time forex adjustment and local payouts.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="landing-section-spacing bg-transparent px-6 relative z-10">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 md:mb-20 space-y-4 reveal">
                <h2 class="text-4xl md:text-6xl font-extrabold font-headline tracking-tight text-on-surface">Echoes of Success</h2>
                <p class="text-on-surface-variant text-lg md:text-xl font-medium">What leaders are saying about their new sanctuary.</p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12">
                <div class="landing-card reveal">
                    <span class="material-symbols-outlined text-6xl md:text-8xl text-primary/10 absolute top-8 right-8 pointer-events-none">format_quote</span>
                    <p class="text-xl md:text-2xl text-on-surface italic leading-relaxed mb-10 relative z-10 font-medium">"The editorial clarity of HRMS Solutions allowed us to scale from 50 to 500 employees without adding extra HR heads. It's truly an architectural feat."</p>
                    <div class="flex items-center gap-5 relative z-10">
                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-full overflow-hidden border-4 border-surface-container-lowest shadow-xl ring-2 ring-primary/20">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuB9F0VDDzlV4NgHgY6Qh3xxQY4GZk7-ToJAAWr1GsqNM__4s1cObRBUQQFky-eEfqL3hytDro9K5tiMlDukO6wKgUBKEemgxl_dJvwpeMk-hzNFhYkheFux6xc2u9VZ0E_s9Gn7afOAkBF2exxjb1jHNjur8YcB9caCxBrjI_i_Gimjk6ppLwWXmMobH6jWkTsDvRkJ5n970O7KB0futp67xohvMV1K4OHSYzGzWoMMW23a_1RjC6B4fT02RyGvqCvCY6vrTMnvBGvu" alt="Alexander Vance" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="font-bold text-on-surface text-lg md:text-xl font-headline tracking-tight">Alexander Vance</p>
                            <p class="text-[10px] md:text-xs text-on-surface-variant uppercase tracking-[0.2em] font-label font-bold text-primary">Director of People, Lumina Tech</p>
                        </div>
                    </div>
                </div>
                <div class="landing-card reveal reveal-delay-200">
                    <span class="material-symbols-outlined text-6xl md:text-8xl text-tertiary/10 absolute top-8 right-8 pointer-events-none">format_quote</span>
                    <p class="text-xl md:text-2xl text-on-surface italic leading-relaxed mb-10 relative z-10 font-medium">"We finally found a tool that respects our time. No clutter, just clean data and high-speed execution for our global payroll needs."</p>
                    <div class="flex items-center gap-5 relative z-10">
                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-full overflow-hidden border-4 border-surface-container-lowest shadow-xl ring-2 ring-tertiary/20">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDtZIZbvA63SfcPwiF1EW0aW4VMR0sENgUuiJL5xvumlLAkQbgKiFd8SHzf5I7TMAtTLUgRMzG8t6rQevCETy-6KX9-XduAbzUlDeon4R_OsNaSZW0NSmXkfnhBkbvKwZBWZpR31pMI4mmnhgkMdFZi9K1jILXFX83G3Yz4kmyNIiEuBnE-zfVqloiuSp6BU41BnmfEKbm6yuQYSaTTWrVbWbMKTT4rAFjjGTFTKqPRAesOe--3HKjN3jGIYJ-qr4T5R4ZMc88PnIP2" alt="Elena Rodriguez" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="font-bold text-on-surface text-lg md:text-xl font-headline tracking-tight">Elena Rodriguez</p>
                            <p class="text-[10px] md:text-xs text-on-surface-variant uppercase tracking-[0.2em] font-label font-bold text-tertiary">CFO, Forge Manufacturing</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section (Alpine) -->
    <section class="landing-section-spacing px-6 bg-surface-container-low border-y border-outline-variant/15">
        <div class="max-w-3xl mx-auto space-y-12 md:space-y-16 reveal">
            <div class="text-center space-y-4">
                <h2 class="text-4xl md:text-5xl font-extrabold text-on-surface font-headline tracking-tight">Common Inquiries</h2>
                <p class="text-on-surface-variant text-lg md:text-xl font-medium">Everything you need to know about starting your sanctuary.</p>
            </div>
            <div class="space-y-4 md:space-y-6">
                <!-- FAQ Item 1 -->
                <div x-data="{ open: false }" class="bg-surface-container-lowest/80 backdrop-blur-md p-6 md:p-8 rounded-2xl border border-white/40 shadow-xl transition-all duration-300" :class="open ? 'shadow-2xl border-primary/30 ring-1 ring-primary/20' : 'hover:border-outline-variant/30'">
                    <button @click="open = !open" class="flex items-center justify-between w-full text-left font-bold text-lg md:text-xl text-on-surface cursor-pointer font-headline focus:outline-none">
                        <span>How long does implementation take?</span>
                        <div class="shrink-0 w-8 h-8 rounded-full flex items-center justify-center transition-colors" :class="open ? 'bg-primary/10 text-primary' : 'bg-surface-container text-on-surface-variant'">
                            <span class="material-symbols-outlined transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
                        </div>
                    </button>
                    <div x-show="open" x-collapse.duration.300ms x-cloak>
                        <p class="mt-6 text-on-surface-variant leading-relaxed text-base font-medium">Most teams are fully onboarded within 7-14 business days, depending on the complexity of legacy data migration and existing infrastructure.</p>
                    </div>
                </div>
                <!-- FAQ Item 2 -->
                <div x-data="{ open: false }" class="bg-surface-container-lowest/80 backdrop-blur-md p-6 md:p-8 rounded-2xl border border-white/40 shadow-xl transition-all duration-300" :class="open ? 'shadow-2xl border-primary/30 ring-1 ring-primary/20' : 'hover:border-outline-variant/30'">
                    <button @click="open = !open" class="flex items-center justify-between w-full text-left font-bold text-lg md:text-xl text-on-surface cursor-pointer font-headline focus:outline-none">
                        <span>Is our sensitive employee data secure?</span>
                        <div class="shrink-0 w-8 h-8 rounded-full flex items-center justify-center transition-colors" :class="open ? 'bg-primary/10 text-primary' : 'bg-surface-container text-on-surface-variant'">
                            <span class="material-symbols-outlined transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
                        </div>
                    </button>
                    <div x-show="open" x-collapse.duration.300ms x-cloak>
                        <p class="mt-6 text-on-surface-variant leading-relaxed text-base font-medium">We use enterprise-grade AES-256 encryption and are SOC2 Type II compliant to ensure absolute sanctuary for your data at every level.</p>
                    </div>
                </div>
                <!-- FAQ Item 3 -->
                <div x-data="{ open: false }" class="bg-surface-container-lowest/80 backdrop-blur-md p-6 md:p-8 rounded-2xl border border-white/40 shadow-xl transition-all duration-300" :class="open ? 'shadow-2xl border-primary/30 ring-1 ring-primary/20' : 'hover:border-outline-variant/30'">
                    <button @click="open = !open" class="flex items-center justify-between w-full text-left font-bold text-lg md:text-xl text-on-surface cursor-pointer font-headline focus:outline-none">
                        <span>Can we integrate with our existing ERP?</span>
                        <div class="shrink-0 w-8 h-8 rounded-full flex items-center justify-center transition-colors" :class="open ? 'bg-primary/10 text-primary' : 'bg-surface-container text-on-surface-variant'">
                            <span class="material-symbols-outlined transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
                        </div>
                    </button>
                    <div x-show="open" x-collapse.duration.300ms x-cloak>
                        <p class="mt-6 text-on-surface-variant leading-relaxed text-base font-medium">Yes, we offer a robust API and pre-built connectors for major ERP and accounting suites like SAP, Oracle, and Tally.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="py-24 md:py-32 px-6 relative overflow-hidden reveal">
        <div class="max-w-7xl mx-auto rounded-[3rem] md:rounded-[4rem] bg-inverse-surface p-10 md:p-24 relative overflow-hidden text-center shadow-2xl border border-white/10">
            <div class="relative z-10 max-w-3xl mx-auto space-y-8 md:space-y-10">
                <h2 class="text-4xl md:text-7xl font-extrabold font-headline text-white tracking-tight leading-tight drop-shadow-lg">Ready to curate your culture?</h2>
                <p class="text-inverse-on-surface text-lg md:text-2xl leading-relaxed font-medium">Join 2,500+ forward-thinking organizations building the future of work with architectural precision.</p>
                <div class="flex flex-wrap justify-center gap-4 md:gap-6 pt-4">
                    <a href="/contact" class="w-full sm:w-auto px-10 py-5 bg-gradient-to-br from-primary to-tertiary text-white font-bold rounded-2xl shadow-xl text-lg hover:-translate-y-1 hover:shadow-primary/40 transition-all duration-300 border border-white/20">Book a Demo</a>
                    <a href="#" class="w-full sm:w-auto px-10 py-5 bg-white/10 backdrop-blur-md text-white font-bold rounded-2xl border border-white/20 text-lg hover:bg-white/20 hover:-translate-y-1 transition-all duration-300">Talk to Sales</a>
                </div>
            </div>
            <!-- Abstract Texture BG -->
            <div class="absolute inset-0 opacity-20 pointer-events-none">
                <div class="absolute -top-1/2 -left-1/2 w-[200%] h-[200%] bg-[radial-gradient(circle_at_30%_50%,#0053cd_0%,transparent_40%)] animate-[spin_20s_linear_infinite]"></div>
                <div class="absolute -bottom-1/2 -right-1/2 w-[200%] h-[200%] bg-[radial-gradient(circle_at_70%_50%,#8c3a8c_0%,transparent_40%)] animate-[spin_25s_linear_infinite_reverse]"></div>
            </div>
        </div>
    </section>
</div>

<!-- Reveal Animation Script -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const reveals = document.querySelectorAll('.reveal');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px"
        });

        reveals.forEach(reveal => {
            observer.observe(reveal);
        });
    });
</script>
@endsection

