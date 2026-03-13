@extends('layouts.landing')

@section('title', 'HRMS Solutions | Kinetic Horizon v2.0')

@section('content')
<!-- Hero Section -->
<section class="relative pt-20 md:pt-24 pb-24 md:pb-32 overflow-hidden bg-hero-gradient">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
        <div class="lg:col-span-7">
            <div class="inline-flex items-center gap-2 px-3 py-1 mb-6 md:mb-8 rounded-full bg-surface-container-high border border-primary/10 premium-shadow">
                <span class="flex h-2 w-2 rounded-full bg-tertiary animate-pulse"></span>
                <span class="text-[10px] md:text-[11px] font-bold tracking-wider uppercase text-on-tertiary-fixed-variant font-label">New v2.0 Kinetic Update</span>
            </div>
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold tracking-tight font-headline leading-[1.1] mb-6 md:mb-8">
                Simplify Your HR, <br class="hidden md:block"> <span class="text-gradient">Empower</span> Your People.
            </h1>
            <p class="text-base md:text-lg text-on-surface-variant max-w-xl mb-8 md:mb-10 leading-relaxed font-medium">
                Manage payroll, attendance, and recruitment in one unified sanctuary. Built for scaling teams that value architectural growth and talent experience.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="/contact" class="px-8 py-4 bg-primary text-on-primary font-bold rounded-xl premium-shadow hover:scale-[1.02] transition-transform primary-gradient text-center">
                    Book a Demo
                </a>
                <a href="#" class="px-8 py-4 bg-surface-container-lowest text-on-surface font-bold rounded-xl premium-shadow flex items-center justify-center gap-2 border border-outline-variant/10 hover:bg-surface-container-low transition-all">
                    <span class="material-symbols-outlined">play_circle</span>
                    View Showreel
                </a>
            </div>
        </div>
        <div class="lg:col-span-5 relative mt-12 lg:mt-0">
            <div class="w-full aspect-[4/5] rounded-[2rem] overflow-hidden premium-shadow bg-surface-container-lowest border-4 border-surface-container-lowest p-2 transform lg:rotate-2 hover:rotate-0 transition-transform duration-700">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBiSFE24h9pkSEz2X87ZhwkiHwD1uHcGFmzK93mdOxA373fcK1kj1PIirFwxxJrpi1F-6WABhxBjDgXPbMvXLhxzvrm2oSsE7sicFKnqJzlMHlHOOaaAUYmzYYiPWEPsFBavQCqdtuvf_SHvEwEmdGhGGg0uxdPWp5jincupFeTcJLh9zWNtXiu4Y1KAaIAbUldDl5xZQA1zhBlx1r5zwruDJ6w3pjUg7Iq8kXcLamdCbk0UDA6ycJi5Ju5rhqO9kt4EVfUSECI5IBm" alt="Strategic HR Dashboard" class="w-full h-full object-cover rounded-[1.75rem]">
            </div>
            <!-- Floating Data Card -->
            <div class="absolute -bottom-6 -left-4 md:-bottom-8 md:-left-12 p-5 md:p-6 bg-surface-container-lowest/90 backdrop-blur-xl rounded-2xl premium-shadow border border-outline-variant/20 max-w-[200px] md:max-w-[240px]">
                <div class="flex items-center gap-3 mb-3 md:mb-4">
                    <div class="p-2 bg-tertiary/10 rounded-lg text-tertiary">
                        <span class="material-symbols-outlined text-sm md:text-base">analytics</span>
                    </div>
                    <span class="text-[10px] md:text-xs font-bold text-on-surface uppercase tracking-widest font-label">Engagement</span>
                </div>
                <div class="text-2xl md:text-3xl font-bold font-headline mb-1">+24.8%</div>
                <div class="text-[10px] md:text-[11px] text-on-surface-variant font-medium">Growth in cultural alignment</div>
            </div>
        </div>
    </div>
</section>

<!-- Trusted By Section -->
<section class="py-12 md:py-16 bg-surface border-y border-outline-variant/15">
    <div class="max-w-7xl mx-auto px-6">
        <p class="text-center text-[9px] md:text-[10px] font-bold tracking-[0.2em] text-outline uppercase mb-10 md:mb-12">Architecting growth for industry leaders</p>
        <div class="flex flex-wrap justify-center items-center gap-8 md:gap-24 opacity-60 grayscale hover:grayscale-0 transition-all duration-500">
            <div class="text-on-surface font-headline font-extrabold text-xl md:text-2xl tracking-tighter">LUMINA</div>
            <div class="text-on-surface font-headline font-extrabold text-xl md:text-2xl tracking-tighter">FORGE</div>
            <div class="text-on-surface font-headline font-extrabold text-xl md:text-2xl tracking-tighter">AETHER</div>
            <div class="text-on-surface font-headline font-extrabold text-xl md:text-2xl tracking-tighter">ORBIT</div>
            <div class="text-on-surface font-headline font-extrabold text-xl md:text-2xl tracking-tighter">VELOCITY</div>
        </div>
    </div>
</section>

<!-- Feature Bento Grid -->
<section class="py-24 md:py-32 bg-surface-container-low">
    <div class="max-w-7xl mx-auto px-6">
        <div class="mb-12 md:mb-16 space-y-4">
            <h2 class="text-3xl md:text-5xl font-extrabold font-headline tracking-tight">Curation at <span class="text-tertiary">Scale.</span></h2>
            <p class="text-on-surface-variant text-base md:text-lg leading-relaxed max-w-2xl font-medium">Everything you need to manage your most valuable asset, presented with editorial clarity and intentional flow.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 md:gap-8">
            <!-- Large Feature Card -->
            <div class="md:col-span-8 bg-surface-container-lowest rounded-[2rem] p-8 md:p-10 premium-shadow relative overflow-hidden group border border-outline-variant/10">
                <div class="relative z-10 flex flex-col lg:flex-row gap-8 items-center">
                    <div class="flex-1 space-y-6">
                        <div class="inline-flex items-center gap-3 px-4 py-2 bg-primary/10 text-primary rounded-full">
                            <span class="material-symbols-outlined text-sm md:text-base">badge</span>
                            <span class="text-[10px] md:text-xs font-bold uppercase tracking-wider font-label">Talent Architecture</span>
                        </div>
                        <h3 class="text-2xl md:text-3xl font-bold font-headline text-on-surface">Employee Lifecycle Management</h3>
                        <p class="text-on-surface-variant text-sm md:text-base leading-relaxed font-medium">Centralize profiles with an editorial touch. From precision hiring to long-term career curation.</p>
                        <a href="#" class="inline-flex items-center text-primary font-bold gap-2 group/link text-sm">Explore Directory <span class="material-symbols-outlined group-hover/link:translate-x-1 transition-transform">chevron_right</span></a>
                    </div>
                    <div class="flex-1 w-full lg:max-w-sm">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAX-GkxOqG--KNIW-0vfPCNNpgbAaOpnqDjBUQ5XD4rwQVBEwHbABW1x7lAcwmWv9-pZ1hADpvgsj5bIYAowJ4rV6hqVmj8jDrrCDq6qU0Z6fBV0N9wArkA7qAywYS0tyI-3rRAgUcehQ7FOtOJl7nqLEC4GoJBB-8VDXIYaEYq7sr5Ne6o-x4WrKwRXs5QPOuAhGK3sdlBQ3zDHXzDxwX86KUkotvZulQ0mfwjD0aUyEkd0J34IOOFsMCTQwDY3kPKpYuYsGGFOze-" alt="Employee Directory" class="rounded-xl shadow-lg grayscale group-hover:grayscale-0 transition-all duration-700 w-full">
                    </div>
                </div>
            </div>
            <!-- Small Square Feature -->
            <div class="md:col-span-4 bg-secondary rounded-[2rem] p-8 md:p-10 premium-shadow text-on-secondary flex flex-col justify-between group">
                <div>
                    <div class="p-3 bg-white/20 rounded-2xl w-fit mb-6 md:mb-8 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-white text-3xl">payments</span>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold font-headline mb-3">Smart Payroll</h3>
                    <p class="text-on-secondary/80 text-sm md:text-base font-medium leading-relaxed">Automate complex calculations and tax filings with architectural precision.</p>
                </div>
                <a class="inline-flex items-center gap-2 font-bold text-sm group/link mt-8" href="#">
                    Learn More <span class="material-symbols-outlined group-hover/link:translate-x-1 transition-transform">arrow_forward</span>
                </a>
            </div>
            <!-- Medium Feature -->
            <div class="md:col-span-4 bg-surface-container-highest rounded-[2rem] p-8 border border-outline-variant/20 premium-shadow flex flex-col justify-between group hover:bg-surface-container-high transition-colors">
                <div class="space-y-6">
                    <div class="w-14 h-14 rounded-xl bg-tertiary/10 flex items-center justify-center text-tertiary group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">schedule</span>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-on-surface font-headline">Time & Attendance</h3>
                    <p class="text-on-surface-variant text-sm md:text-base font-medium leading-relaxed">Biometric integration and geo-fencing for the modern, remote-first or hybrid workforce.</p>
                </div>
                <div class="mt-8 flex flex-wrap gap-2">
                    <span class="px-3 py-1 bg-tertiary/20 text-tertiary text-[9px] md:text-[10px] font-bold rounded-full uppercase">Real-time</span>
                    <span class="px-3 py-1 bg-surface-container text-on-surface-variant text-[9px] md:text-[10px] font-bold rounded-full uppercase">Mobile Ready</span>
                </div>
            </div>
            <!-- Horizontal Long Feature -->
            <div class="md:col-span-8 bg-gradient-to-br from-surface-container to-surface-container-high rounded-[2rem] p-8 md:p-10 flex flex-col md:flex-row items-center gap-8 md:gap-10 premium-shadow border border-outline-variant/10 group">
                <div class="flex-1">
                    <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">rocket_launch</span>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold font-headline mb-4 text-on-surface">Recruitment Suite</h3>
                    <p class="text-on-surface-variant text-sm md:text-base font-medium leading-relaxed mb-6">Find and nurture talent through a seamless pipeline. Integrated job boards and AI-driven screening.</p>
                    <a class="inline-flex items-center text-primary font-bold gap-2 group/link text-sm" href="#">Open Pipeline <span class="material-symbols-outlined group-hover/link:translate-x-1 transition-transform">chevron_right</span></a>
                </div>
                <div class="w-full md:w-64 overflow-hidden rounded-xl shadow-lg border border-outline-variant/10">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAGzOtfyGlNKc_-LUzawTcSE4ZXMYrsrYMLtiDlh6Cec-KCC5SggxoLNWTtuVTa2fjfR63k4BFjI8ggPPpEFVs1ocECUve1sTIQd1Ip-HjoC5m_ATid0FybH7ZEqj4JuA2gfaRb-RMq4YLck5lia984D5xk3lRQTUd53EKsbPpMA8Ij5jAGBBHgwJxNaXQBETvvcGpdB31xWMETlwJ-Je9ejRL0GDHdscTngJ54ehw5bQPioIrU87maLk745IUmZ-hRZQxKnolFLUUJ" alt="Recruitment" class="w-full h-auto grayscale group-hover:grayscale-0 transition-all duration-700">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Dashboard Detail Section -->
<section class="py-24 md:py-32 bg-surface px-6 overflow-hidden">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-16 lg:gap-20">
        <div class="lg:w-1/2 space-y-10 md:space-y-12">
            <h2 class="text-3xl md:text-5xl font-extrabold font-headline tracking-tight text-on-surface">One Interface.<br class="hidden md:block"><span class="text-gradient">Infinite Control.</span></h2>
            <div class="space-y-6 md:space-y-8">
                <div class="flex gap-5 md:gap-6 group">
                    <div class="shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold premium-shadow group-hover:scale-110 transition-transform">1</div>
                    <div>
                        <h4 class="text-lg md:text-xl font-bold text-on-surface mb-2 font-headline">Executive Dashboard</h4>
                        <p class="text-on-surface-variant text-sm md:text-base leading-relaxed font-medium">Real-time analytics on turnover, cost-per-hire, and employee satisfaction levels with editorial clarity.</p>
                    </div>
                </div>
                <div class="flex gap-5 md:gap-6 group">
                    <div class="shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold premium-shadow group-hover:scale-110 transition-transform">2</div>
                    <div>
                        <h4 class="text-lg md:text-xl font-bold text-on-surface mb-2 font-headline">Automated Compliance</h4>
                        <p class="text-on-surface-variant text-sm md:text-base leading-relaxed font-medium">Stay ahead of labor laws with automatic updates and documentation storage in your secure sanctuary.</p>
                    </div>
                </div>
                <div class="flex gap-5 md:gap-6 group">
                    <div class="shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold premium-shadow group-hover:scale-110 transition-transform">3</div>
                    <div>
                        <h4 class="text-lg md:text-xl font-bold text-on-surface mb-2 font-headline">Employee Self-Service</h4>
                        <p class="text-on-surface-variant text-sm md:text-base leading-relaxed font-medium">Empower your team to manage their own leaves, benefits, and payslips through a clean, intuitive portal.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:w-1/2 relative mt-12 lg:mt-0">
            <div class="bg-surface-container-lowest p-2 rounded-[2.5rem] shadow-deep-breath transform lg:rotate-3 hover:rotate-0 transition-transform duration-700 border-4 border-surface-container-low premium-shadow">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCJPZxP1PPzCKuXPenzg9wJwy5R9VqjAYCCV5VizG7o883_3THlwTR1toegI2_rzrJ1gYNgIv0d4mYK7TRF4_lLSviNEYxw7sLBoufl2_GpxKCQ2DGnxZLinYfWJBbfolte582XcoKzkZqPUrm1VMJqeX8QIEz4LfZhpeQDuHR7K9k2ZDwvpDRMdAoZNfV_GtHihVavHaUvwa_IGN1yDJ7RbRvxO5f6bBGIUq35M-7Zb-DX97oYBhVjhaKqYk9di2thcB7-hwOEY69f" alt="Advanced Analytics" class="rounded-[2rem] w-full h-auto">
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-24 md:py-32 bg-hero-gradient">
    <div class="max-w-7xl mx-auto px-6 flex flex-col lg:flex-row items-center gap-16 lg:gap-20">
        <div class="lg:w-1/2 relative w-full">
            <div class="grid grid-cols-2 gap-4 md:gap-6">
                <div class="space-y-4 md:space-y-6 pt-8 md:pt-12">
                    <div class="bg-surface-container-lowest p-6 md:p-8 rounded-[2rem] premium-shadow border border-outline-variant/10 group hover:-translate-y-1 transition-transform">
                        <span class="text-3xl md:text-5xl font-extrabold font-headline block mb-2 text-primary">98%</span>
                        <p class="text-[9px] md:text-xs font-bold text-outline uppercase tracking-widest font-label">Retention Rate</p>
                    </div>
                    <div class="bg-primary p-6 md:p-8 rounded-[2rem] premium-shadow text-on-primary primary-gradient group hover:-translate-y-1 transition-transform">
                        <span class="text-3xl md:text-5xl font-extrabold font-headline block mb-2">12M+</span>
                        <p class="text-[9px] md:text-xs font-bold text-on-primary/70 uppercase tracking-widest font-label">Managed Lives</p>
                    </div>
                </div>
                <div class="space-y-4 md:space-y-6">
                    <div class="bg-tertiary p-6 md:p-8 rounded-[2rem] premium-shadow text-on-tertiary group hover:-translate-y-1 transition-transform">
                        <span class="text-3xl md:text-5xl font-extrabold font-headline block mb-2">4.9/5</span>
                        <p class="text-[9px] md:text-xs font-bold text-on-tertiary/70 uppercase tracking-widest font-label">User Rating</p>
                    </div>
                    <div class="bg-surface-container-lowest p-6 md:p-8 rounded-[2rem] premium-shadow border border-outline-variant/10 group hover:-translate-y-1 transition-transform">
                        <span class="text-3xl md:text-5xl font-extrabold font-headline block mb-2 text-secondary">25+</span>
                        <p class="text-[9px] md:text-xs font-bold text-outline uppercase tracking-widest font-label">Global Offices</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:w-1/2 space-y-6 md:space-y-8">
            <div class="inline-block px-4 py-2 bg-secondary/10 text-secondary rounded-full text-[10px] md:text-xs font-bold font-label uppercase tracking-widest">Global Reach</div>
            <h2 class="text-3xl md:text-5xl font-extrabold font-headline tracking-tight text-on-surface">Architected for the modern world.</h2>
            <p class="text-on-surface-variant text-base md:text-lg leading-relaxed font-medium">
                Our platform scales with your ambition. Whether you're a boutique studio or a global conglomerate, our architecture adapts to your cultural and operational needs seamlessly.
            </p>
            <div class="space-y-6 pt-4">
                <div class="flex items-start gap-4 group">
                    <div class="p-2 bg-primary/10 rounded-lg text-primary group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined">language</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-on-surface font-headline text-lg">Universal Compliance</h4>
                        <p class="text-sm text-on-surface-variant font-medium">Legal framework support for 120+ territories.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 group">
                    <div class="p-2 bg-tertiary/10 rounded-lg text-tertiary group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined">payments</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-on-surface font-headline text-lg">Multi-Currency Engine</h4>
                        <p class="text-sm text-on-surface-variant font-medium">Real-time forex adjustment and local payouts.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-24 md:py-32 bg-surface px-6">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16 md:mb-20 space-y-4">
            <h2 class="text-3xl md:text-5xl font-extrabold font-headline tracking-tight text-on-surface">Echoes of Success</h2>
            <p class="text-on-surface-variant text-base md:text-lg font-medium">What leaders are saying about their new sanctuary.</p>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12">
            <div class="bg-surface-container-lowest p-8 md:p-12 rounded-[2.5rem] premium-shadow border border-outline-variant/10 relative group hover:-translate-y-2 transition-transform duration-500">
                <span class="material-symbols-outlined text-5xl md:text-7xl text-primary/10 absolute top-8 right-8">format_quote</span>
                <p class="text-xl md:text-2xl text-on-surface italic leading-relaxed mb-10 relative z-10 font-medium">"The editorial clarity of HRMS Solutions allowed us to scale from 50 to 500 employees without adding extra HR heads. It's truly an architectural feat."</p>
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 md:w-16 md:h-16 rounded-full overflow-hidden border-2 border-primary/20 premium-shadow">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuB9F0VDDzlV4NgHgY6Qh3xxQY4GZk7-ToJAAWr1GsqNM__4s1cObRBUQQFky-eEfqL3hytDro9K5tiMlDukO6wKgUBKEemgxl_dJvwpeMk-hzNFhYkheFux6xc2u9VZ0E_s9Gn7afOAkBF2exxjb1jHNjur8YcB9caCxBrjI_i_Gimjk6ppLwWXmMobH6jWkTsDvRkJ5n970O7KB0futp67xohvMV1K4OHSYzGzWoMMW23a_1RjC6B4fT02RyGvqCvCY6vrTMnvBGvu" alt="Alexander Vance" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <p class="font-bold text-on-surface text-base md:text-lg font-headline">Alexander Vance</p>
                        <p class="text-[10px] md:text-xs text-on-surface-variant uppercase tracking-[0.2em] font-label font-bold">Director of People, Lumina Tech</p>
                    </div>
                </div>
            </div>
            <div class="bg-surface-container-lowest p-8 md:p-12 rounded-[2.5rem] premium-shadow border border-outline-variant/10 relative group hover:-translate-y-2 transition-transform duration-500">
                <span class="material-symbols-outlined text-5xl md:text-7xl text-tertiary/10 absolute top-8 right-8">format_quote</span>
                <p class="text-xl md:text-2xl text-on-surface italic leading-relaxed mb-10 relative z-10 font-medium">"We finally found a tool that respects our time. No clutter, just clean data and high-speed execution for our global payroll needs."</p>
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 md:w-16 md:h-16 rounded-full overflow-hidden border-2 border-tertiary/20 premium-shadow">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDtZIZbvA63SfcPwiF1EW0aW4VMR0sENgUuiJL5xvumlLAkQbgKiFd8SHzf5I7TMAtTLUgRMzG8t6rQevCETy-6KX9-XduAbzUlDeon4R_OsNaSZW0NSmXkfnhBkbvKwZBWZpR31pMI4mmnhgkMdFZi9K1jILXFX83G3Yz4kmyNIiEuBnE-zfVqloiuSp6BU41BnmfEKbm6yuQYSaTTWrVbWbMKTT4rAFjjGTFTKqPRAesOe--3HKjN3jGIYJ-qr4T5R4ZMc88PnIP2" alt="Elena Rodriguez" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <p class="font-bold text-on-surface text-base md:text-lg font-headline">Elena Rodriguez</p>
                        <p class="text-[10px] md:text-xs text-on-surface-variant uppercase tracking-[0.2em] font-label font-bold">CFO, Forge Manufacturing</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-24 md:py-32 px-6 bg-surface-container-low border-y border-outline-variant/15">
    <div class="max-w-3xl mx-auto space-y-12 md:space-y-16">
        <div class="text-center space-y-4">
            <h2 class="text-3xl md:text-4xl font-extrabold text-on-surface font-headline tracking-tight">Common Inquiries</h2>
            <p class="text-on-surface-variant text-base md:text-lg font-medium">Everything you need to know about starting your sanctuary.</p>
        </div>
        <div class="space-y-4 md:space-y-6">
            <div class="bg-surface-container-lowest p-6 md:p-8 rounded-2xl border border-outline-variant/10 premium-shadow group">
                <details class="group/details">
                    <summary class="flex items-center justify-between w-full text-left font-bold text-lg md:text-xl text-on-surface cursor-pointer list-none font-headline">
                        <span>How long does implementation take?</span>
                        <span class="material-symbols-outlined group-open/details:rotate-180 transition-transform text-primary">expand_more</span>
                    </summary>
                    <p class="mt-6 text-on-surface-variant leading-relaxed text-sm md:text-base font-medium">Most teams are fully onboarded within 7-14 business days, depending on the complexity of legacy data migration and existing infrastructure.</p>
                </details>
            </div>
            <div class="bg-surface-container-lowest p-6 md:p-8 rounded-2xl border border-outline-variant/10 premium-shadow group">
                <details class="group/details">
                    <summary class="flex items-center justify-between w-full text-left font-bold text-lg md:text-xl text-on-surface cursor-pointer list-none font-headline">
                        <span>Is our sensitive employee data secure?</span>
                        <span class="material-symbols-outlined group-open/details:rotate-180 transition-transform text-primary">expand_more</span>
                    </summary>
                    <p class="mt-6 text-on-surface-variant leading-relaxed text-sm md:text-base font-medium">We use enterprise-grade AES-256 encryption and are SOC2 Type II compliant to ensure absolute sanctuary for your data at every level.</p>
                </details>
            </div>
            <div class="bg-surface-container-lowest p-6 md:p-8 rounded-2xl border border-outline-variant/10 premium-shadow group">
                <details class="group/details">
                    <summary class="flex items-center justify-between w-full text-left font-bold text-lg md:text-xl text-on-surface cursor-pointer list-none font-headline">
                        <span>Can we integrate with our existing ERP?</span>
                        <span class="material-symbols-outlined group-open/details:rotate-180 transition-transform text-primary">expand_more</span>
                    </summary>
                    <p class="mt-6 text-on-surface-variant leading-relaxed text-sm md:text-base font-medium">Yes, we offer a robust API and pre-built connectors for major ERP and accounting suites like SAP, Oracle, and Tally.</p>
                </details>
            </div>
        </div>
    </div>
</section>

<!-- Final CTA Section -->
<section class="py-24 md:py-32 px-6 relative overflow-hidden">
    <div class="max-w-7xl mx-auto rounded-[3rem] md:rounded-[4rem] bg-inverse-surface p-10 md:p-24 relative overflow-hidden text-center premium-shadow">
        <div class="relative z-10 max-w-3xl mx-auto space-y-8 md:space-y-10">
            <h2 class="text-3xl md:text-6xl font-extrabold font-headline text-white tracking-tight leading-tight">Ready to curate your culture?</h2>
            <p class="text-inverse-on-surface text-base md:text-xl leading-relaxed font-medium">Join 2,500+ forward-thinking organizations building the future of work with architectural precision.</p>
            <div class="flex flex-wrap justify-center gap-4 md:gap-6 pt-4">
                <a href="/contact" class="w-full sm:w-auto px-10 py-5 bg-primary text-on-primary font-bold rounded-xl premium-shadow text-lg hover:scale-105 transition-transform primary-gradient">Book a Demo</a>
                <a href="#" class="w-full sm:w-auto px-10 py-5 bg-white/10 text-white font-bold rounded-xl border border-white/20 text-lg hover:bg-white/20 transition-colors">Talk to Sales</a>
            </div>
        </div>
        <!-- Abstract Texture BG -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_30%_50%,#0053cd_0%,transparent_50%)]"></div>
            <div class="absolute bottom-0 right-0 w-full h-full bg-[radial-gradient(circle_at_70%_50%,#8c3a8c_0%,transparent_50%)]"></div>
        </div>
    </div>
</section>
@endsection
