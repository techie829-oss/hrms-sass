<div class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">
                {{ $title ?? 'Why Choose Our Platform?' }}
            </h2>
            <p class="mt-4 text-lg text-slate-600">
                {{ $subtitle ?? 'Simplify your operations with everything you need.' }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($features as $feature)
            <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_30px_-4px_rgba(0,82,204,0.1)] transition-all duration-300">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-6">
                    <span class="text-2xl">{{ $feature['icon'] ?? '⚡' }}</span>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">{{ $feature['title'] }}</h3>
                <p class="text-slate-600 leading-relaxed">{{ $feature['description'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
