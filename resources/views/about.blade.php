@extends('layouts.landing')

@section('title', 'About Us | HRMS Solutions')

@section('content')
<!-- Section 1: Hero -->
<section class="relative pt-24 pb-32 md:pt-32 md:pb-48 overflow-hidden bg-hero-gradient">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-16 md:gap-20 items-center">
        <div class="lg:col-span-7 z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 mb-8 rounded-full bg-secondary/10 border border-secondary/20 premium-shadow">
                <span class="flex h-2 w-2 rounded-full bg-secondary"></span>
                <span class="text-[11px] font-bold tracking-widest uppercase text-secondary font-label">Established 2024</span>
            </div>
            <h1 class="font-headline text-5xl md:text-7xl lg:text-8xl font-extrabold tracking-tight text-on-surface mb-8 leading-[1.05]">
                Architecture for <br><span class="text-gradient">Human Capital.</span>
            </h1>
            <p class="text-on-surface-variant text-lg md:text-xl leading-relaxed max-w-xl mb-12 font-medium">
                Beyond the spreadsheet. We design environments where administrative clarity meets employee growth, creating a sanctuary for modern workforces.
            </p>
            <div class="flex flex-wrap gap-6">
                <a href="/modules" class="primary-gradient text-on-primary px-10 py-5 rounded-2xl font-bold text-lg premium-shadow hover:scale-[1.02] transition-transform text-center">
                    Explore the Suite
                </a>
                <a href="#story" class="bg-surface-container-lowest border border-outline-variant/20 px-10 py-5 rounded-2xl font-bold text-lg text-primary hover:bg-surface-container-low transition-all premium-shadow flex items-center justify-center gap-2">
                    Our Story <span class="material-symbols-outlined">expand_more</span>
                </a>
            </div>
        </div>
        <div class="lg:col-span-5 relative mt-12 lg:mt-0">
            <div class="w-full aspect-[4/5] bg-surface-container-low rounded-[3.5rem] overflow-hidden premium-shadow border-8 border-white transform lg:rotate-3 hover:rotate-0 transition-transform duration-700">
                <img class="w-full h-full object-cover opacity-90 mix-blend-multiply grayscale hover:grayscale-0 transition-all duration-1000" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDdY-mnJ5zxoKS3PStu0sVvW021jpzhY5ZMXaje1HHWgusp1wzGRYpq4syD_W6ajh7FTe9_zp9kHYNNFgLojnn0gW_iwWAx8QM-8CN_0Mx9yT0CQWR9NJJ28UIZumEtwMYgeIUTyDjjBv4aTKGV7Qmia8HCTz7UR8J8ce61PoZBLM3J_jmz6qAXnhcdw7AURry9vmv_o4XjuxdsBs-ipkjFLCxqMnPodNNoQBG944IT6ZXFl_r1ZQH-TWb7ma3rQQVmcxjXGyxUJVwh" alt="Architectural office">
                <div class="absolute inset-0 bg-gradient-to-tr from-primary/20 to-transparent"></div>
            </div>
        </div>
    </div>
</section>

<!-- Section 2: Our Mission -->
<section class="py-24 md:py-40 bg-surface">
    <div class="max-w-7xl mx-auto px-6">
        <div class="mb-16 space-y-4">
            <h2 class="font-headline text-4xl lg:text-5xl font-extrabold text-on-surface tracking-tight">Our Mission</h2>
            <div class="h-2 w-32 primary-gradient rounded-full"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
            <div class="md:col-span-8 bg-surface-container-low p-10 md:p-16 rounded-[3rem] flex flex-col justify-between min-h-[450px] premium-shadow border border-outline-variant/10 group">
                <div class="space-y-8">
                    <h3 class="font-headline text-4xl md:text-5xl font-bold leading-tight text-on-surface">Defining the Future of <br><span class="text-tertiary">Workforce Clarity.</span></h3>
                    <p class="text-on-surface-variant text-lg md:text-xl leading-relaxed mt-8 max-w-xl font-medium">
                        We exist to streamline the complexities of HR for Small and Medium Enterprises. By removing the bureaucratic clutter, we allow leaders to focus on what truly matters: their people.
                    </p>
                </div>
                <div class="mt-12 flex items-center gap-4 text-primary font-black uppercase tracking-widest text-sm hover:gap-6 transition-all cursor-pointer">
                    <span>Learn about our methodology</span>
                    <span class="material-symbols-outlined">arrow_forward</span>
                </div>
            </div>
            <div class="md:col-span-4 bg-tertiary p-10 md:p-12 rounded-[3rem] text-on-tertiary premium-shadow primary-gradient flex flex-col justify-between group">
                <span class="material-symbols-outlined text-7xl mb-8 group-hover:scale-110 transition-transform block" style="font-variation-settings: 'FILL' 1;">auto_awesome</span>
                <div>
                    <h4 class="font-headline text-3xl font-bold mb-6">Growth Focused</h4>
                    <p class="opacity-90 text-lg leading-relaxed font-medium">We don't just track data; we map potential. Our tools are built to identify promotion paths before they become obstacles.</p>
                </div>
            </div>
            <div class="md:col-span-4 bg-surface-container-highest p-10 rounded-[2.5rem] premium-shadow border border-outline-variant/10 group hover:bg-surface-container-high transition-colors">
                <h4 class="font-headline text-2xl font-bold mb-4 text-on-surface">Precision</h4>
                <p class="text-on-surface-variant leading-relaxed font-medium">Automated compliance and payroll systems that operate with surgical accuracy and zero-error tolerance.</p>
            </div>
            <div class="md:col-span-4 bg-secondary p-10 rounded-[2.5rem] text-on-secondary premium-shadow">
                <h4 class="font-headline text-2xl font-bold mb-4">Stability</h4>
                <p class="opacity-80 leading-relaxed font-medium">Built on architectural principles of balance and structural integrity for your enterprise-grade company data.</p>
            </div>
            <div class="md:col-span-4 bg-surface-container-lowest p-10 rounded-[2.5rem] border border-outline-variant/15 premium-shadow">
                <h4 class="font-headline text-2xl font-bold mb-4 text-on-surface">Clarity</h4>
                <p class="text-on-surface-variant leading-relaxed font-medium">Interfaces designed to reduce cognitive load and enhance administrative focus through intentional design.</p>
            </div>
        </div>
    </div>
</section>

<!-- Section 3: The Story & Philosophy -->
<section id="story" class="py-24 md:py-40 bg-surface-container-low border-y border-outline-variant/15">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col lg:flex-row gap-20 lg:gap-32 items-start">
            <div class="lg:w-1/2 lg:sticky lg:top-32 space-y-10">
                <h2 class="font-headline text-5xl md:text-6xl font-extrabold text-on-surface leading-tight tracking-tight">The Story Behind <br><span class="text-gradient">{{ config('app.name', 'HRMS Solutions') }}</span></h2>
                <div class="space-y-8 text-on-surface-variant text-lg md:text-xl leading-relaxed font-medium">
                    <p>
                        Our journey began in a crowded co-working space, born from a frustration shared by three founders who realized that "human resources" had become more about "resources" and less about "humans."
                    </p>
                    <p>
                        We saw brilliant startups struggling under the weight of disjointed spreadsheets, fragile payroll hacks, and opaque growth metrics. There was a desperate need for a system that didn't just store data, but organized it into a narrative of growth.
                    </p>
                </div>
            </div>
            <div class="lg:w-1/2 space-y-12 md:space-y-16 mt-12 lg:mt-0">
                <!-- Philosophy Card -->
                <div class="bg-surface-container-lowest p-10 md:p-16 rounded-[3.5rem] premium-shadow border-l-[12px] border-primary">
                    <h3 class="font-headline text-3xl font-bold mb-10 text-on-surface">The Architectural Lens</h3>
                    <p class="text-on-surface-variant text-lg md:text-xl leading-relaxed mb-12 font-medium">
                        We believe software should be as intentional as great architecture. Our design-led approach rejects the "feature factory" mentality.
                    </p>
                    <ul class="space-y-10">
                        <li class="flex items-start gap-6 group">
                            <span class="material-symbols-outlined text-primary p-3 bg-primary/10 rounded-2xl group-hover:scale-110 transition-transform">check_circle</span>
                            <div>
                                <span class="font-bold text-on-surface block text-xl font-headline mb-2">Editorial Hierarchy</span>
                                <span class="text-on-surface-variant text-lg leading-relaxed font-medium">Data is presented based on strategic priority, ensuring focus where it matters most.</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-6 group">
                            <span class="material-symbols-outlined text-primary p-3 bg-primary/10 rounded-2xl group-hover:scale-110 transition-transform">check_circle</span>
                            <div>
                                <span class="font-bold text-on-surface block text-xl font-headline mb-2">Human-Centric Scale</span>
                                <span class="text-on-surface-variant text-lg leading-relaxed font-medium">Tools that adapt to your unique organizational workflow, not the other way around.</span>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- Simple Stacked Image (Breathable) -->
                <div class="rounded-[3.5rem] overflow-hidden premium-shadow h-[400px] md:h-[500px] border-8 border-white group relative">
                    <img class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-1000" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDMy_BmXElsyL2jKrhYpx0EXwv9Czsml17KJa674da6JDPKADCmdZjYemAF9NjBLR9DIgNAm7Sms-RUFG_ParcU4ZYxqFsT6hfMAZTwvXpAKZZyB30pRNtaIRdIdnlvjhQCkES_QLCEKzuGlYAC6nlHieqcXXaJ2GdJrAzU7myj-GrGLOaHA4rOqAQFVkmbzUULt0zgBYt8s_r5tV43K7ZMc771D63Q2sjXNx8BBqZNMpJqgx8cqMu2BgjYlEIEugV1Jl77mwvt1f4d" alt="Collaboration">
                    <div class="absolute inset-0 bg-primary/10 opacity-0 group-hover:opacity-100 transition-opacity duration-1000"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section 4: Leadership -->
<section class="py-24 md:py-40 bg-surface">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-20 md:mb-32 space-y-6">
            <h2 class="font-headline text-5xl lg:text-6xl font-extrabold text-on-surface tracking-tight leading-tight">The Visionaries</h2>
            <p class="text-on-surface-variant text-lg md:text-xl max-w-2xl mx-auto leading-relaxed font-medium">Founded by experts in industrial design and enterprise engineering committed to structural software integrity.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-16 lg:gap-24 items-start">
            <!-- Founder 1 -->
            <div class="group">
                <div class="relative mb-10 rounded-[3rem] overflow-hidden aspect-[4/5] bg-surface-container premium-shadow border-8 border-white">
                    <img class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 grayscale group-hover:grayscale-0" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD5ZygiY-V_6HRjnmgjw28Z2O3790_opvFDUmL7CRmnlnHdl19PF8pWTEq0kYxSbEvA9QjqUtRcGxn0QVjcSGHOqX2z6CSA9YNaZaNJaQwHC18CA2V0S1rhnBrDsHOHLLl2mDcs4De_uN9b37pW4_LTV2vdH4_W_4dE_vb0ykzwDYIkRt144xvWk1CSmvCc590le9423u4wuhQTH-fprlsBzUBFkYEtYvd5a15LWrP-xAW0h8GzMS2gp6iLjmZM8sr8sdci49s0viie" alt="Julian Sterling">
                </div>
                <div class="space-y-4">
                    <h4 class="font-headline text-3xl font-bold text-on-surface">Julian Sterling</h4>
                    <p class="text-primary font-black uppercase tracking-[0.2em] text-xs font-label">Chief Executive Officer</p>
                    <p class="text-on-surface-variant text-lg leading-relaxed font-medium">Focused on structural integrity and design-led system architecture.</p>
                </div>
            </div>
            <!-- Founder 2 -->
            <div class="group md:mt-24">
                <div class="relative mb-10 rounded-[3rem] overflow-hidden aspect-[4/5] bg-surface-container premium-shadow border-8 border-white">
                    <img class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 grayscale group-hover:grayscale-0" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAX0qeYs8RbhsbvBJkW1Xm5s-xP5LOkcpmhYvG5JM3Wfy5M3rWlOaoYczt-MyvBOUddtP_YQd7hv1YlfS08ZvgpiUT5Fn93C_sqcpyI3EQoJyWRvD5X4ZndXFjepCBKBDsMc5HuarwMSZHKX2ekO3pHb7PmjVp3DRS-FBZ-WUTT--z9B76eYO8iOh5I-H2nNnhhv54OxRbw1Mv2vRAJtMAT3rbeYEUVyohxuUw06ash4dnrhCHdz0bRJt-Cl__fp0-3BBnwzh_CY1mL" alt="Elena Vance">
                </div>
                <div class="space-y-4">
                    <h4 class="font-headline text-3xl font-bold text-on-surface">Elena Vance</h4>
                    <p class="text-primary font-black uppercase tracking-[0.2em] text-xs font-label">Chief Operations Officer</p>
                    <p class="text-on-surface-variant text-lg leading-relaxed font-medium">HR strategy veteran with 15 years in SME workforce scaling expertise.</p>
                </div>
            </div>
            <!-- Founder 3 -->
            <div class="group">
                <div class="relative mb-10 rounded-[3rem] overflow-hidden aspect-[4/5] bg-surface-container premium-shadow border-8 border-white">
                    <img class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 grayscale group-hover:grayscale-0" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDsQmnx2QceU3wkXQbWQHWxAYaC7tCQYrB727BCD98hwqAy-hebroarQAiEWpTCZuguH9fMJmReU5Ma0MPDR2spfHkwfJau0lNKuAkiYUgmsRJFx4dP0uorEMq04CZVy5RSx00K-bVF25a19rbzeQNf2SSV_CJSvz10CduOgf-8DKDlGyarP2vh2zXSu8_tMANUA43oAyU1rEwv5zU32X8JW7YggtQfVsGnRGW3tWogUnAyBA2m2wxo-la2T1mOInznBmEyrBj2jh4-" alt="Marcus Chen">
                </div>
                <div class="space-y-4">
                    <h4 class="font-headline text-3xl font-bold text-on-surface">Marcus Chen</h4>
                    <p class="text-primary font-black uppercase tracking-[0.2em] text-xs font-label">Chief Technology Officer</p>
                    <p class="text-on-surface-variant text-lg leading-relaxed font-medium">Specializing in high-security cloud infrastructure and data sovereignty.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section 5: Final CTA -->
<section class="py-32 md:py-48 px-6">
    <div class="max-w-7xl mx-auto rounded-[4rem] bg-inverse-surface p-16 md:p-32 text-center premium-shadow relative overflow-hidden group">
        <div class="relative z-10 space-y-12">
            <h2 class="font-headline text-5xl md:text-7xl font-extrabold text-white tracking-tight leading-[1.1]">Join our journey</h2>
            <p class="text-2xl text-inverse-on-surface opacity-90 max-w-3xl mx-auto leading-relaxed font-medium">Experience the sanctuary of organized human capital. Let's build your workforce architecture together.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-8 pt-8">
                <a href="/contact" class="primary-gradient text-on-primary px-12 py-6 rounded-2xl font-black uppercase tracking-widest text-sm premium-shadow hover:scale-105 transition-transform text-center">
                    Book a Demo
                </a>
                <a href="/contact" class="bg-white/10 text-white border border-white/20 px-12 py-6 rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-white/20 transition-all text-center">
                    Contact Sales
                </a>
            </div>
        </div>
        <!-- Texture BG -->
        <div class="absolute inset-0 opacity-20 pointer-events-none group-hover:scale-110 transition-transform duration-[5000ms]">
            <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_30%_50%,#0053cd_0%,transparent_50%)]"></div>
            <div class="absolute bottom-0 right-0 w-full h-full bg-[radial-gradient(circle_at_70%_50%,#8c3a8c_0%,transparent_50%)]"></div>
        </div>
    </div>
</section>
@endsection
