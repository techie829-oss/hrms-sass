<div class="py-24 bg-slate-50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-20">
            <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">
                Everything You Need to <span class="text-blue-600">Run Your Operations</span>
            </h2>
            <p class="mt-4 text-lg text-slate-600">
                Detailed capabilities designed to replace spreadsheets entirely.
            </p>
        </div>

        <div class="space-y-24">
            @foreach($sections as $index => $section)
            <div class="flex flex-col {{ $index % 2 == 1 ? 'md:flex-row-reverse' : 'md:flex-row' }} items-center gap-12">
                
                <!-- Content -->
                <div class="flex-1 space-y-6">
                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-bold tracking-wide">
                        {{ $section['badge'] ?? 'Feature Spotlight' }}
                    </div>
                    <h3 class="text-3xl font-extrabold text-slate-900">{{ $section['title'] }}</h3>
                    <p class="text-lg text-slate-600 leading-relaxed">{{ $section['description'] }}</p>
                    
                    @if(isset($section['points']))
                    <ul class="space-y-4 pt-4">
                        @foreach($section['points'] as $point)
                        <li class="flex items-start">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-orange-100 flex items-center justify-center mt-1">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="ml-4 text-slate-700 font-medium">{{ $point }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>

                <!-- Image/Screenshot Placeholder -->
                <div class="flex-1 w-full">
                    <div class="relative rounded-2xl bg-gradient-to-br from-blue-50 to-orange-50 border border-gray-200 p-2 shadow-2xl overflow-hidden aspect-[4/3] flex items-center justify-center group">
                        <!-- Mock Browser Chrome -->
                        <div class="absolute top-0 left-0 w-full h-10 bg-white border-b border-gray-200 flex items-center px-4 space-x-2">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        </div>
                        <div class="mt-8 text-center px-6">
                            <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                <span class="text-2xl">📸</span>
                            </div>
                            <span class="text-slate-400 font-medium block">Screenshot Placeholder</span>
                            <span class="text-slate-500 font-bold mt-2 block">{{ $section['title'] }} Dashboard</span>
                        </div>
                    </div>
                </div>

            </div>
            @endforeach
        </div>
    </div>
</div>
