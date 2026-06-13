<div class="py-24 bg-slate-900 relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
        <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-blue-600/10 blur-[120px]"></div>
        <div class="absolute bottom-[0%] -right-[10%] w-[40%] h-[40%] rounded-full bg-orange-500/10 blur-[100px]"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-20">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                Go Live in <span class="text-orange-400">4 Simple Steps</span>
            </h2>
            <p class="mt-4 text-lg text-slate-400">
                Transition smoothly without business disruption.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
            <!-- Connecting Line -->
            <div class="hidden md:block absolute top-8 left-12 right-12 h-0.5 bg-gradient-to-r from-blue-600/50 via-slate-700 to-orange-500/50 z-0"></div>
            
            @foreach($steps as $index => $step)
            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-800 border border-slate-700 flex items-center justify-center text-xl font-bold text-white shadow-xl mb-6">
                    0{{ $index + 1 }}
                </div>
                <h3 class="text-xl font-bold text-white mb-3">{{ $step['title'] }}</h3>
                <p class="text-slate-400 leading-relaxed">{{ $step['description'] }}</p>
            </div>
            @endforeach
        </div>

        <div class="mt-20 text-center">
            <a href="/contact" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-xl transition-all shadow-[0_0_20px_rgba(255,165,0,0.3)] hover:shadow-[0_0_30px_rgba(255,165,0,0.5)]">
                Start Onboarding
            </a>
        </div>
    </div>
</div>
