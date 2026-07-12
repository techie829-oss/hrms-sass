@extends('layouts.marketing')

@section('title', 'Contact Us | Solidrix HRMS Sales & Support')
@section('description', 'Get in touch with the Solidrix HRMS team. Contact our sales or support for assistance, to request a demo, or to discuss custom enterprise requirements.')
@section('content')

{{-- Hero Section --}}
<section class="pt-28 pb-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6">Get in Touch</h1>
        <p class="text-xl text-gray-500 max-w-2xl">
            Have questions about Solidrix HRMS? Need support? Want to schedule a demo?
            We're here to help you succeed.
        </p>
    </div>
</section>

{{-- Main Contact Section --}}
<section class="pb-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">

            {{-- Contact Form --}}
            <div class="lg:col-span-3 bg-white border border-gray-100 rounded-3xl p-10 shadow-sm">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Send us a Message</h2>
                <p class="text-gray-500 text-sm mb-8">We typically respond within 1 business day.</p>

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="/contact" method="POST" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="first_name" class="block text-sm font-semibold text-gray-700 mb-1.5">First Name <span class="text-red-500">*</span></label>
                            <input type="text" id="first_name" name="first_name" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors text-sm"
                                placeholder="Enter first name">
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-semibold text-gray-700 mb-1.5">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" id="last_name" name="last_name" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors text-sm"
                                placeholder="Enter last name">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors text-sm"
                                placeholder="Enter your email address">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1.5">Phone Number</label>
                            <div class="flex rounded-xl overflow-hidden border border-gray-200 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent transition-all">
                                <span class="inline-flex items-center px-3 bg-gray-50 text-gray-500 border-r border-gray-200 text-sm font-medium">+91</span>
                                <input type="tel" id="phone" name="phone"
                                    class="flex-1 block w-full px-4 py-3 border-0 focus:ring-0 text-sm"
                                    placeholder="Enter your phone number">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="company_name" class="block text-sm font-semibold text-gray-700 mb-1.5">Company / Organization Name <span class="text-red-500">*</span></label>
                        <input type="text" id="company_name" name="company_name" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors text-sm"
                            placeholder="Enter your company name">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="employee_count" class="block text-sm font-semibold text-gray-700 mb-1.5">Number of Employees</label>
                            <select id="employee_count" name="employee_count"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors text-sm bg-white">
                                <option value="">Select team size</option>
                                <option value="1-10">1 – 10 employees</option>
                                <option value="11-50">11 – 50 employees</option>
                                <option value="51-200">51 – 200 employees</option>
                                <option value="201-500">201 – 500 employees</option>
                                <option value="500+">500+ employees</option>
                            </select>
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-semibold text-gray-700 mb-1.5">Subject <span class="text-red-500">*</span></label>
                            <select id="subject" name="subject" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors text-sm bg-white">
                                <option value="">Select a subject</option>
                                <option value="demo">Schedule a Demo</option>
                                <option value="pricing">Pricing Information</option>
                                <option value="support">Technical Support</option>
                                <option value="partnership">Partnership Opportunities</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-semibold text-gray-700 mb-1.5">Message <span class="text-red-500">*</span></label>
                        <textarea id="message" name="message" rows="5" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors text-sm"
                            placeholder="Tell us about your HR challenges or what you'd like to know…"></textarea>
                    </div>

                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="newsletter"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-500">Subscribe to our newsletter for HR tips and product updates</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-4 rounded-xl text-base font-bold text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm">
                        Send Message
                    </button>
                </form>
            </div>

            {{-- Right Column: Contact Info + Hours --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Contact Information Card --}}
                <div class="bg-blue-50 rounded-3xl p-8 border border-blue-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Contact Information</h2>
                    <div class="space-y-5">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-sm">Phone</p>
                                <p class="text-gray-600 text-sm">+91 98116 55457</p>
                                <a href="https://wa.me/919811655457?text=Hello,%20I%20have%20an%20inquiry%20about%20Solidrix%20HRMS." target="_blank"
                                    class="text-blue-600 hover:text-blue-800 text-xs font-medium flex items-center gap-1 mt-1 group">
                                    <span>Chat on WhatsApp</span>
                                    <span class="group-hover:translate-x-1 transition-transform inline-block">→</span>
                                </a>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-sm">Email</p>
                                <p class="text-gray-600 text-sm">support@sklops.com</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-sm">Address</p>
                                <p class="text-gray-600 text-sm">Lakhimpur, Uttar Pradesh – 262701</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-blue-200">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-sm">Live Demo</p>
                                <a href="https://wa.me/919811655457?text=Hi,%20I%20want%20to%20book%20a%20demo%20of%20Solidrix%20HRMS." target="_blank" class="text-amber-600 hover:text-amber-800 text-xs font-medium flex items-center gap-1 mt-1 group">
                                    <span>Book a Demo on WhatsApp</span>
                                    <span class="group-hover:translate-x-1 transition-transform inline-block">→</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Business Hours Card --}}
                <div class="bg-white border border-amber-100 rounded-3xl p-8 shadow-sm">
                    <h2 class="text-xl font-bold text-amber-500 mb-5">Business Hours</h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Monday – Friday</span>
                            <span class="font-semibold text-gray-800">9:00 AM – 6:00 PM IST</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Saturday</span>
                            <span class="font-semibold text-gray-800">10:00 AM – 4:00 PM IST</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Sunday</span>
                            <span class="font-semibold text-red-400">Closed</span>
                        </div>
                    </div>
                    <div class="mt-5 p-4 bg-amber-50 rounded-xl">
                        <p class="text-xs text-amber-700">
                            <strong>Note:</strong> For urgent technical support, please call our helpline directly.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Quick Actions --}}
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-gray-900">Quick Actions</h2>
            <p class="mt-3 text-gray-500">Not ready to talk? Explore on your own.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="/modules"
                class="group bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Explore Modules</h3>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Learn about all 7 integrated HR modules — from payroll to recruitment.
                </p>
            </a>

            <a href="/pricing"
                class="group bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">View Pricing</h3>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Transparent pricing for startups to enterprises. No hidden fees.
                </p>
            </a>

            <a href="/about"
                class="group bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">About Solidrix</h3>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Learn our story, our mission, and the team behind the product.
                </p>
            </a>
        </div>
    </div>
</section>

@endsection
