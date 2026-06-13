<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SolidrixHR - Modern HRMS')</title>
    <meta name="description" content="@yield('description', 'Manage attendance, payroll, leave, recruitment, and employee records from a single platform. Built for growing teams.')">
    
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
<body class="bg-white text-gray-900 selection:bg-blue-100 selection:text-blue-900">

    <!-- Vibrant Navigation -->
    <header class="sticky top-0 z-50 w-full bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo with Geeno Style -->
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

                <!-- CTA -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="/login" class="text-sm font-semibold text-gray-700 hover:text-blue-600 transition-colors">Log in</a>
                    <a href="/contact" class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg shadow-sm text-sm font-bold text-white bg-orange-500 hover:bg-orange-600 transition-colors">
                        Book a Demo
                    </a>
                    <a href="/contact" class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                        Start Trial
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Minimalist Footer -->
    <footer class="bg-gray-50 border-t border-gray-200 pt-16 pb-8 mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase mb-4">Product</h3>
                    <ul class="space-y-3">
                        <li><a href="/payroll-software" class="text-sm text-gray-600 hover:text-blue-600">Payroll</a></li>
                        <li><a href="/attendance-management-software" class="text-sm text-gray-600 hover:text-blue-600">Attendance</a></li>
                        <li><a href="/leave-management-system" class="text-sm text-gray-600 hover:text-blue-600">Leave Management</a></li>
                        <li><a href="/recruitment-software" class="text-sm text-gray-600 hover:text-blue-600">Recruitment</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase mb-4">Use Cases</h3>
                    <ul class="space-y-3">
                        <li><a href="/hrms-for-small-business" class="text-sm text-gray-600 hover:text-blue-600">For Small Business</a></li>
                        <li><a href="/hrms-for-startups" class="text-sm text-gray-600 hover:text-blue-600">For Startups</a></li>
                        <li><a href="/hrms-for-manufacturing" class="text-sm text-gray-600 hover:text-blue-600">For Manufacturing</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase mb-4">Company</h3>
                    <ul class="space-y-3">
                        <li><a href="/about" class="text-sm text-gray-600 hover:text-blue-600">About Us</a></li>
                        <li><a href="/contact" class="text-sm text-gray-600 hover:text-blue-600">Contact</a></li>
                        <li><a href="/pricing" class="text-sm text-gray-600 hover:text-blue-600">Pricing</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase mb-4">Legal</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-sm text-gray-600 hover:text-blue-600">Privacy Policy</a></li>
                        <li><a href="#" class="text-sm text-gray-600 hover:text-blue-600">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-200 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} Solidrix. All rights reserved.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <span class="text-sm text-gray-500">Practical HR software for modern teams.</span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
