<div class="py-12 bg-white border-t border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center md:divide-x md:divide-gray-100">
            @foreach($items as $item)
            <div class="px-4 flex flex-col items-center">
                <div class="text-3xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-orange-500 mb-2">{{ $item['value'] }}</div>
                <div class="text-sm font-bold text-slate-600 uppercase tracking-wide">{{ $item['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>
