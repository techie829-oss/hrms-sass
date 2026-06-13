<div class="py-24 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-extrabold text-slate-900">
                Frequently Asked <span class="text-blue-600">Questions</span>
            </h2>
            <p class="mt-4 text-lg text-slate-600">
                Everything you need to know about this module.
            </p>
        </div>

        <div class="space-y-4" x-data="{ activeAccordion: 0 }">
            @foreach($faqs as $index => $faq)
            <div class="border border-gray-200 rounded-xl overflow-hidden bg-white">
                <button 
                    @click="activeAccordion = activeAccordion === {{ $index }} ? null : {{ $index }}"
                    class="w-full flex justify-between items-center px-6 py-5 text-left focus:outline-none focus:bg-slate-50 transition-colors"
                >
                    <span class="font-bold text-slate-900 text-lg">{{ $faq['q'] }}</span>
                    <span class="ml-6 flex items-center justify-center w-8 h-8 rounded-full border border-gray-200 text-blue-600 transition-transform duration-200"
                          :class="{ 'rotate-180 bg-blue-50 border-blue-200': activeAccordion === {{ $index }} }">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </span>
                </button>
                <div 
                    x-show="activeAccordion === {{ $index }}" 
                    x-collapse
                    class="px-6 pb-6 pt-2 text-slate-600 leading-relaxed"
                    style="display: none;"
                >
                    {{ $faq['a'] }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
