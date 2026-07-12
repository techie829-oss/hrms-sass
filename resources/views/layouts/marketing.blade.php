<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Solidrix HRMS - Best Cloud HR, Payroll & Attendance Software')</title>
    <meta name="description" content="@yield('description', 'Simplify your HR operations with Solidrix HRMS. A complete cloud-based solution for payroll, attendance tracking, leave management, and employee self-service.')">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .bg-vibrant-hero {
            background: radial-gradient(circle at top left, rgba(255, 165, 0, 0.15), transparent 40%),
                        radial-gradient(circle at top right, rgba(0, 82, 204, 0.1), transparent 40%);
            background-color: #ffffff;
        }
    </style>
</head>
<body class="bg-white text-gray-900 selection:bg-blue-100 selection:text-blue-900 flex flex-col min-h-screen">

    <!-- Vibrant Navigation -->
    <header class="sticky top-0 z-50 w-full bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm" x-data="{ mobileOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center relative shadow-sm">
                            <span class="text-white font-bold text-xl leading-none">S</span>
                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-orange-500 rounded-full border-2 border-white"></div>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-slate-800">Solidrix<span class="text-blue-600">HR</span></span>
                    </a>
                </div>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex space-x-8">
                    <a href="/features" class="text-sm font-semibold text-gray-700 hover:text-blue-600 transition-colors">Features</a>
                    <a href="/pricing" class="text-sm font-semibold text-gray-700 hover:text-blue-600 transition-colors">Pricing</a>
                    <a href="/about" class="text-sm font-semibold text-gray-700 hover:text-blue-600 transition-colors">About</a>
                    <a href="/contact" class="text-sm font-semibold text-gray-700 hover:text-blue-600 transition-colors">Contact</a>
                </nav>

                <!-- Desktop CTA -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="/login" class="text-sm font-semibold text-gray-700 hover:text-blue-600 transition-colors">Log in</a>
                    
                    @if(\Illuminate\Support\Facades\Cookie::has('demo_access_granted'))
                        <a href="https://demo.hr.solidrix.com/" target="_blank" class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg shadow-sm text-sm font-bold text-white bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 transition-all shadow-orange-500/30">
                            Start Demo
                        </a>
                    @else
                        <button type="button" x-data @click="$dispatch('open-demo-modal')" class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg shadow-sm text-sm font-bold text-white bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 transition-all shadow-orange-500/30 animate-pulse hover:animate-none">
                            Start Demo
                        </button>
                    @endif

                    <a href="/contact" class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                        Start Trial
                    </a>
                </div>

                <!-- Mobile Hamburger Button -->
                <button
                    type="button"
                    class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-gray-100 transition-colors"
                    @click="mobileOpen = !mobileOpen"
                    aria-label="Toggle menu"
                >
                    <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div
            x-show="mobileOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="md:hidden border-t border-gray-100 bg-white"
            style="display:none"
        >
            <div class="px-4 py-4 space-y-1">
                <a href="/features" class="block px-4 py-3 rounded-xl text-sm font-semibold text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">Features</a>
                <a href="/pricing" class="block px-4 py-3 rounded-xl text-sm font-semibold text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">Pricing</a>
                <a href="/about" class="block px-4 py-3 rounded-xl text-sm font-semibold text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">About</a>
                <a href="/contact" class="block px-4 py-3 rounded-xl text-sm font-semibold text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">Contact</a>
                <a href="/faqs" class="block px-4 py-3 rounded-xl text-sm font-semibold text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">FAQs</a>
                <div class="pt-3 border-t border-gray-100 flex flex-col gap-3">
                    <a href="/login" class="block text-center px-4 py-3 rounded-xl text-sm font-semibold text-gray-700 border border-gray-200 hover:border-blue-300 hover:text-blue-600 transition-colors">Log in</a>
                    @if(\Illuminate\Support\Facades\Cookie::has('demo_access_granted'))
                        <a href="https://demo.hr.solidrix.com/" target="_blank" class="block text-center px-4 py-3 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-orange-500 to-red-500">
                            Start Demo
                        </a>
                    @else
                        <button type="button" x-data @click="$dispatch('open-demo-modal'); mobileOpen = false" class="block w-full text-center px-4 py-3 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-orange-500 to-red-500">
                            Start Demo
                        </button>
                    @endif
                    <a href="/contact" class="block text-center px-4 py-3 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                        Start Trial
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col">
        @yield('content')
    </main>

    <!-- Expanded Footer -->
    <footer class="bg-gray-50 border-t border-gray-200 pt-16 pb-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row justify-between gap-8 lg:gap-16 xl:gap-24 mb-12">
                
                <!-- Column 1: Brand & Contact Info -->
                <div class="lg:w-1/4 xl:w-[28%] shrink-0">
                    <a href="/" class="flex items-center gap-2 mb-6">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center relative shadow-sm">
                            <span class="text-white font-bold text-xl leading-none">S</span>
                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-orange-500 rounded-full border-2 border-white"></div>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-slate-800">Solidrix<span class="text-blue-600">HR</span></span>
                    </a>
                    <p class="text-sm text-gray-600 mb-6 leading-relaxed">
                        SolidrixHR provides an affordable, easy-to-use HRMS tailored for modern teams. Manage attendance, payroll, and compliance without the complexity.
                    </p>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="text-sm text-gray-600">Lakhimpur, Uttar Pradesh – 262701</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <a href="mailto:support@sklops.com" class="text-sm text-gray-600 hover:text-blue-600 transition-colors">support@sklops.com</a>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <a href="tel:+919811655457" class="text-sm text-gray-600 hover:text-blue-600 transition-colors">+91 98116 55457</a>
                        </div>
                        <div class="flex items-center gap-3 pt-1">
                            <a href="https://wa.me/919811655457" target="_blank" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-green-50 text-green-700 hover:bg-green-100 transition-colors text-sm font-semibold border border-green-200">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                Chat on WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-wrap lg:flex-nowrap gap-10 xl:gap-16 lg:justify-end flex-grow pt-2">
                    <!-- Column 2: Product -->
                    <div class="w-48">
                        <h3 class="text-sm font-bold text-gray-900 tracking-wider uppercase mb-5">Product</h3>
                        <ul class="space-y-4">
                            <li><a href="/payroll-software" class="text-sm text-gray-600 hover:text-blue-600 transition-colors">Payroll</a></li>
                            <li><a href="/attendance-management-software" class="text-sm text-gray-600 hover:text-blue-600 transition-colors">Attendance</a></li>
                            <li><a href="/leave-management-system" class="text-sm text-gray-600 hover:text-blue-600 transition-colors">Leave Management</a></li>
                            <li><a href="/recruitment-software" class="text-sm text-gray-600 hover:text-blue-600 transition-colors">Recruitment</a></li>
                            <li><a href="/performance-management-software" class="text-sm text-gray-600 hover:text-blue-600 transition-colors whitespace-nowrap">Performance Management</a></li>
                            <li><a href="/employee-self-service" class="text-sm text-gray-600 hover:text-blue-600 transition-colors whitespace-nowrap">Employee Self Service</a></li>
                        </ul>
                    </div>
                    
                    <!-- Column 3: Use Cases -->
                    <div class="w-44">
                        <h3 class="text-sm font-bold text-gray-900 tracking-wider uppercase mb-5">Use Cases</h3>
                        <ul class="space-y-4">
                            <li><a href="/hrms-for-small-business" class="text-sm text-gray-600 hover:text-blue-600 transition-colors whitespace-nowrap">For Small Business</a></li>
                            <li><a href="/hrms-for-startups" class="text-sm text-gray-600 hover:text-blue-600 transition-colors">For Startups</a></li>
                            <li><a href="/hrms-for-manufacturing" class="text-sm text-gray-600 hover:text-blue-600 transition-colors whitespace-nowrap">For Manufacturing</a></li>
                            <li><a href="/hrms-for-it-companies" class="text-sm text-gray-600 hover:text-blue-600 transition-colors whitespace-nowrap">For IT Companies</a></li>
                            <li><a href="/hrms-for-retail-stores" class="text-sm text-gray-600 hover:text-blue-600 transition-colors whitespace-nowrap">For Retail Stores</a></li>
                        </ul>
                    </div>
                    
                    <!-- Column 4: Company -->
                    <div class="w-24">
                        <h3 class="text-sm font-bold text-gray-900 tracking-wider uppercase mb-5">Company</h3>
                        <ul class="space-y-4">
                            <li><a href="/about" class="text-sm text-gray-600 hover:text-blue-600 transition-colors">About Us</a></li>
                            <li><a href="/contact" class="text-sm text-gray-600 hover:text-blue-600 transition-colors">Contact</a></li>
                            <li><a href="/pricing" class="text-sm text-gray-600 hover:text-blue-600 transition-colors">Pricing</a></li>
                            <li><a href="/faqs" class="text-sm text-gray-600 hover:text-blue-600 transition-colors">FAQs</a></li>
                        </ul>
                    </div>
                    
                    <!-- Column 5: Legal -->
                    <div class="w-32">
                        <h3 class="text-sm font-bold text-gray-900 tracking-wider uppercase mb-5">Legal</h3>
                        <ul class="space-y-4">
                            <li><a href="{{ route('central.privacy') }}" class="text-sm text-gray-600 hover:text-blue-600 transition-colors">Privacy Policy</a></li>
                            <li><a href="{{ route('central.terms') }}" class="text-sm text-gray-600 hover:text-blue-600 transition-colors">Terms of Service</a></li>
                            <li><a href="/cookie-policy" class="text-sm text-gray-600 hover:text-blue-600 transition-colors">Cookie Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-200 pt-8 pb-4">
                <h3 class="text-xs font-bold text-gray-400 tracking-wider uppercase mb-3">Available In</h3>
                <div class="flex flex-wrap gap-x-4 gap-y-2">
                    <span class="text-xs text-gray-500">Delhi</span>
                    <span class="text-xs text-gray-500">Mumbai</span>
                    <span class="text-xs text-gray-500">Bangalore</span>
                    <span class="text-xs text-gray-500">Hyderabad</span>
                    <span class="text-xs text-gray-500">Chennai</span>
                    <span class="text-xs text-gray-500">Pune</span>
                    <span class="text-xs text-gray-500">Noida</span>
                    <span class="text-xs text-gray-500">Gurugram</span>
                    <span class="text-xs text-gray-500">Lucknow</span>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-sm text-gray-500 mb-4 md:mb-0">
                    &copy; {{ date('Y') }} Solidrix. All rights reserved.
                </p>
                
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-500">Solidrix - Modern HRMS</span>
                </div>
            </div>
        </div>
    </footer>

    <x-demo-modal />
</body>
</html>
