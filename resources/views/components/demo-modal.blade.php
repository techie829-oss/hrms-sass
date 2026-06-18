<div x-data="{ open: false }" @open-demo-modal.window="open = true">
    <!-- Backdrop -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm" 
         style="display: none;"></div>

    <!-- Modal -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
         style="display: none;">
         
        <div @click.outside="open = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden relative">
            
            <!-- Close Button -->
            <button @click="open = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-full p-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <div class="p-8 sm:p-10">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-extrabold text-slate-800 mb-2">Start Your Free Demo</h2>
                    <p class="text-gray-500">Enter your details to get instant access to the SolidrixHR platform.</p>
                </div>

                <form action="{{ route('central.start-demo') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" placeholder="John Doe">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Work Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" placeholder="john@company.com">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Mobile Number <span class="text-red-500">*</span></label>
                            <input type="text" name="mobile" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" placeholder="+91 9876543210">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Country <span class="text-red-500">*</span></label>
                            <select name="country" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none bg-white">
                                <option value="" disabled selected>Select Country</option>
                                <option value="India">India</option>
                                <option value="United States">United States</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="Canada">Canada</option>
                                <option value="Australia">Australia</option>
                                <option value="United Arab Emirates">United Arab Emirates</option>
                                <option value="Singapore">Singapore</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Company Name</label>
                        <input type="text" name="company_name" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" placeholder="e.g. Acme Corp">
                    </div>

                    <button type="submit" class="w-full py-4 mt-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-lg rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:-translate-y-0.5 transition-all duration-200">
                        Access Demo Now
                    </button>
                    
                    <p class="text-xs text-center text-gray-400 mt-4">
                        By proceeding, you agree to our <a href="{{ route('central.terms') }}" class="text-blue-500 hover:underline">Terms of Service</a> and <a href="{{ route('central.privacy') }}" class="text-blue-500 hover:underline">Privacy Policy</a>.
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
