@extends('layouts.landing')

@section('title', 'Contact Us | HRMS Solutions')

@section('content')
<!-- Hero Section -->
<section class="relative bg-hero-gradient py-24 px-6 overflow-hidden">
    <div class="max-w-7xl mx-auto relative z-10">
        <div class="max-w-2xl space-y-6">
            <span class="inline-block px-4 py-1.5 rounded-full bg-primary/10 text-primary font-label text-xs font-bold tracking-widest uppercase premium-shadow">Contact Us</span>
            <h1 class="font-headline text-5xl md:text-7xl font-extrabold text-on-surface tracking-tight leading-[1.1]">Get in <span class="text-gradient">Touch.</span></h1>
            <p class="text-on-surface-variant text-xl leading-relaxed font-medium">
                Connect with our experts to discuss how {{ config('app.name', 'HRMS Solutions') }} can transform your organization's architectural approach to human capital.
            </p>
        </div>
    </div>
    <!-- Abstract background shape -->
    <div class="absolute top-0 right-0 w-1/2 h-full bg-surface-container-low/50 -rotate-12 translate-x-1/4 translate-y-12 blur-3xl -z-0 hidden lg:block"></div>
</section>

<!-- Main Section: Form & Cards -->
<section class="py-24 px-6 bg-surface">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
            <!-- Contact Form -->
            <div class="lg:col-span-7 bg-surface-container-lowest p-10 md:p-12 rounded-[2.5rem] premium-shadow border border-outline-variant/10">
                <form class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-xs font-bold text-on-surface uppercase tracking-widest font-label">Full Name</label>
                            <input type="text" class="w-full bg-surface-container-low border-transparent focus:border-primary focus:ring-0 rounded-xl p-4 text-on-surface placeholder-outline/50 transition-all font-medium" placeholder="John Doe">
                        </div>
                        <div class="space-y-3">
                            <label class="text-xs font-bold text-on-surface uppercase tracking-widest font-label">Work Email</label>
                            <input type="email" class="w-full bg-surface-container-low border-transparent focus:border-primary focus:ring-0 rounded-xl p-4 text-on-surface placeholder-outline/50 transition-all font-medium" placeholder="john@company.com">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-xs font-bold text-on-surface uppercase tracking-widest font-label">Company Name</label>
                            <input type="text" class="w-full bg-surface-container-low border-transparent focus:border-primary focus:ring-0 rounded-xl p-4 text-on-surface placeholder-outline/50 transition-all font-medium" placeholder="Enter company name">
                        </div>
                        <div class="space-y-3">
                            <label class="text-xs font-bold text-on-surface uppercase tracking-widest font-label">Subject</label>
                            <select class="w-full bg-surface-container-low border-transparent focus:border-primary focus:ring-0 rounded-xl p-4 text-on-surface transition-all font-medium">
                                <option>Sales Inquiry</option>
                                <option>Product Support</option>
                                <option>Partnership</option>
                                <option>Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="text-xs font-bold text-on-surface uppercase tracking-widest font-label">Message</label>
                        <textarea class="w-full bg-surface-container-low border-transparent focus:border-primary focus:ring-0 rounded-xl p-4 text-on-surface placeholder-outline/50 transition-all font-medium" placeholder="Tell us more about your needs..." rows="5"></textarea>
                    </div>
                    <button type="submit" class="w-full primary-gradient py-5 rounded-2xl text-on-primary font-black tracking-widest uppercase text-sm hover:scale-[1.02] transition-transform premium-shadow">Send Message</button>
                </form>
            </div>

            <!-- Contact Info Cards -->
            <div class="lg:col-span-5 flex flex-col gap-8">
                <!-- Sales -->
                <div class="bg-surface-container-low p-8 rounded-[2rem] border border-outline-variant/15 hover:bg-surface-bright transition-all premium-shadow group">
                    <div class="flex items-start gap-6">
                        <div class="p-4 bg-primary/10 rounded-2xl text-primary group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl">payments</span>
                        </div>
                        <div class="flex-1 space-y-4">
                            <h3 class="font-headline font-bold text-2xl text-on-surface">Sales</h3>
                            <p class="text-on-surface-variant leading-relaxed">Inquire about our premium enterprise plans and custom architectural solutions.</p>
                            <div class="space-y-2">
                                <p class="flex items-center gap-3 text-primary font-bold">
                                    <span class="material-symbols-outlined text-xl">mail</span> sales@hrmscloud.com
                                </p>
                                <p class="flex items-center gap-3 text-primary font-bold">
                                    <span class="material-symbols-outlined text-xl">call</span> +1 (888) 555-0123
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Support -->
                <div class="bg-surface-container-low p-8 rounded-[2rem] border border-outline-variant/15 hover:bg-surface-bright transition-all premium-shadow group">
                    <div class="flex items-start gap-6">
                        <div class="p-4 bg-tertiary/10 rounded-2xl text-tertiary group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl">contact_support</span>
                        </div>
                        <div class="flex-1 space-y-4">
                            <h3 class="font-headline font-bold text-2xl text-on-surface">Support</h3>
                            <p class="text-on-surface-variant leading-relaxed">Available for all active platform users for seamless troubleshooting.</p>
                            <div class="space-y-2">
                                <p class="flex items-center gap-3 text-tertiary font-bold">
                                    <span class="material-symbols-outlined text-xl">mail</span> support@hrmscloud.com
                                </p>
                                <p class="flex items-center gap-3 text-tertiary font-bold">
                                    <span class="material-symbols-outlined text-xl">live_help</span> Help Center 24/7
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Office Location Section -->
<section class="py-32 bg-surface-container-low border-y border-outline-variant/15 overflow-hidden">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
            <div class="space-y-10">
                <h2 class="font-headline text-4xl lg:text-5xl font-extrabold text-on-surface tracking-tight">Global Headquarters</h2>
                <div class="space-y-8">
                    <div class="flex gap-6">
                        <span class="material-symbols-outlined text-primary p-3 bg-primary/10 rounded-2xl h-fit">location_on</span>
                        <div>
                            <p class="font-bold text-2xl text-on-surface font-headline mb-2">Cloud Plaza</p>
                            <p class="text-on-surface-variant text-lg leading-relaxed">
                                Level 42, 500 Modernism Way<br>
                                Suite 100, Financial District<br>
                                San Francisco, CA 94105, USA
                            </p>
                        </div>
                    </div>
                    <div class="pt-10 border-t border-outline-variant/30 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                        <div>
                            <p class="text-xs font-bold text-on-surface-variant uppercase tracking-[0.2em] mb-2 font-label">Local Time</p>
                            <p class="text-3xl font-headline font-extrabold text-on-surface">10:42 AM PST</p>
                        </div>
                        <a href="#" class="inline-flex items-center gap-3 px-6 py-3 bg-surface-container-lowest text-primary font-bold rounded-xl premium-shadow border border-outline-variant/15 hover:bg-white transition-all group">
                            Get Directions <span class="material-symbols-outlined text-xl group-hover:translate-x-1 transition-transform">open_in_new</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="h-[500px] relative rounded-[3rem] overflow-hidden premium-shadow border-4 border-white">
                <img class="w-full h-full object-cover grayscale opacity-90 hover:grayscale-0 transition-all duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAiafkjW1mJ4KjupJIZNTGy0oVbaPfpSHPMsckYoZOzULU7dbGfzW6FV7_OPLRonx6aQPjg8W7EnHxTXhGYQyg8zNKprm7G4fTRlGnRTuuvQ6tngSL6rWfekpdatMXAsfI6Fy4uui9z043u1p-GPdipaVDl_SCX9z4qliiRNzJiNYvtewejK8BtYMeQj3k4X9dPYVjJ2x04qXZNhbBJ1SQaUWYAr7g0EIY9ZgN0VPPln3ejhuj9ag5BJ4k5Mr7cFqIgxIGRJ7aNSFBg" alt="Office View">
                <div class="absolute inset-0 bg-primary/10 mix-blend-multiply"></div>
            </div>
        </div>
    </div>
</section>

<!-- Operating Hours -->
<section class="py-32 px-6 bg-surface">
    <div class="max-w-7xl mx-auto">
        <div class="text-center max-w-2xl mx-auto mb-20 space-y-4">
            <h2 class="font-headline text-4xl font-extrabold text-on-surface tracking-tight">Availability & Support</h2>
            <p class="text-on-surface-variant text-lg">Our global teams operate across multiple timezones to ensure consistent reliability.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-surface-container-low p-10 rounded-[2.5rem] border border-outline-variant/10 premium-shadow flex flex-col items-center text-center group hover:bg-surface-bright transition-all">
                <span class="material-symbols-outlined text-primary text-4xl mb-6 group-hover:scale-110 transition-transform">schedule</span>
                <h4 class="font-headline font-bold text-2xl text-on-surface mb-3">Office Hours</h4>
                <p class="text-on-surface-variant mb-4">Monday – Friday</p>
                <p class="text-2xl font-bold text-on-surface font-headline">9:00 AM – 6:00 PM</p>
                <span class="mt-6 text-xs text-on-surface-variant font-bold tracking-[0.2em] uppercase font-label">PST Timezone</span>
            </div>
            <div class="bg-surface-container-low p-10 rounded-[2.5rem] border border-outline-variant/10 premium-shadow flex flex-col items-center text-center group hover:bg-surface-bright transition-all">
                <span class="material-symbols-outlined text-tertiary text-4xl mb-6 group-hover:scale-110 transition-transform">weekend</span>
                <h4 class="font-headline font-bold text-2xl text-on-surface mb-3">Weekend Support</h4>
                <p class="text-on-surface-variant mb-4">Saturday – Sunday</p>
                <p class="text-2xl font-bold text-on-surface font-headline">10:00 AM – 4:00 PM</p>
                <span class="mt-6 text-xs text-on-surface-variant font-bold tracking-[0.2em] uppercase font-label">Limited Inquiries</span>
            </div>
            <div class="primary-gradient p-10 rounded-[2.5rem] premium-shadow flex flex-col items-center text-center text-on-primary group hover:scale-[1.02] transition-all">
                <span class="material-symbols-outlined text-4xl mb-6">priority_high</span>
                <h4 class="font-headline font-bold text-2xl mb-3 text-white">Emergency Desk</h4>
                <p class="text-white/80 mb-4">For critical system outages</p>
                <p class="text-3xl font-black font-headline">24 / 7 / 365</p>
                <span class="mt-6 text-xs font-black tracking-[0.2em] uppercase font-label">Global Priority</span>
            </div>
        </div>
    </div>
</section>
@endsection
