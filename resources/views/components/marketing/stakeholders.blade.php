<div class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">
                <span class="text-blue-600">Designed for</span> Every Stakeholder
            </h2>
            <p class="mt-4 text-lg text-slate-600">
                A unified platform that brings your entire organization together.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($personas as $persona)
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-50 to-orange-50 rounded-2xl flex items-center justify-center mb-6 border border-blue-100">
                    <span class="text-2xl">{{ $persona['icon'] }}</span>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-4">{{ $persona['title'] }}</h3>
                <ul class="space-y-3">
                    @foreach($persona['points'] as $point)
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-orange-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-slate-600 leading-relaxed">{{ $point }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </div>
</div>
