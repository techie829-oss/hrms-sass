<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('reports.index') }}" class="btn btn-ghost btn-xs btn-circle">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                </a>
                <h2 class="text-xl font-bold text-on-surface">Workforce Summary</h2>
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()" class="btn btn-sm btn-ghost border-outline-variant/20 rounded-lg font-bold text-[10px] uppercase tracking-wider gap-2">
                    <span class="material-symbols-outlined text-sm">print</span> Print Analysis
                </button>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- Stat Cards (Matches Dashboard Style) --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-surface-container-lowest border border-outline-variant/10 p-5 rounded-xl shadow-sm">
                <div class="text-[9px] font-bold uppercase tracking-widest text-on-surface-variant opacity-60 mb-2">Total Headcount</div>
                <div class="text-3xl font-black text-primary">{{ $totalEmployees }}</div>
                <div class="mt-2 flex items-center gap-1">
                    <div class="w-1 h-1 rounded-full bg-success"></div>
                    <span class="text-[9px] font-bold text-success uppercase truncate">Active Talent Pool</span>
                </div>
            </div>
            <div class="bg-surface-container-lowest border border-outline-variant/10 p-5 rounded-xl shadow-sm">
                <div class="text-[9px] font-bold uppercase tracking-widest text-on-surface-variant opacity-60 mb-2">Departments</div>
                <div class="text-3xl font-black text-secondary">{{ $departments->count() }}</div>
                <div class="mt-2 flex items-center gap-1">
                    <div class="w-1 h-1 rounded-full bg-secondary opacity-50"></div>
                    <span class="text-[9px] font-bold text-on-surface-variant uppercase opacity-60 truncate">Organizational Units</span>
                </div>
            </div>
            {{-- Add common stats placeholders if needed --}}
            <div class="bg-surface-container-lowest border border-outline-variant/10 p-5 rounded-xl shadow-sm hidden md:block">
                <div class="text-[9px] font-bold uppercase tracking-widest text-on-surface-variant opacity-60 mb-2">Avg Tenure</div>
                <div class="text-3xl font-black text-on-surface">2.4<span class="text-xs opacity-40 ml-1">Yrs</span></div>
                <div class="mt-2 flex items-center gap-1">
                    <span class="text-[9px] font-bold text-on-surface-variant uppercase opacity-40 truncate font-mono">ESTIMATED</span>
                </div>
            </div>
            <div class="bg-surface-container-lowest border border-outline-variant/10 p-5 rounded-xl shadow-sm hidden md:block">
                <div class="text-[9px] font-bold uppercase tracking-widest text-on-surface-variant opacity-60 mb-2">Retention</div>
                <div class="text-3xl font-black text-on-surface">94<span class="text-xs opacity-40 ml-1">%</span></div>
                <div class="mt-2 flex items-center gap-1">
                    <div class="w-1 h-1 rounded-full bg-primary"></div>
                    <span class="text-[9px] font-bold text-primary uppercase truncate font-mono">Q1 TARGET</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Department Breakdown Card --}}
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-outline-variant/5 bg-surface-container-low/20">
                    <h3 class="font-bold text-[10px] uppercase tracking-wider text-on-surface opacity-80 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">hub</span> Department Distribution
                    </h3>
                </div>
                <div class="p-5 space-y-5">
                    @foreach($departments as $dept)
                        @php
                            $percentage = $totalEmployees > 0 ? ($dept->employees_count / $totalEmployees) * 100 : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between items-center mb-1.5 px-0.5">
                                <span class="font-bold text-[11px] text-on-surface">{{ $dept->name }}</span>
                                <span class="text-[10px] font-black text-on-surface-variant opacity-70">{{ $dept->employees_count }} members <span class="opacity-30 inline-block ml-1">[{{ number_format($percentage, 0) }}%]</span></span>
                            </div>
                            <div class="w-full bg-surface-container-low rounded-full h-1.5 overflow-hidden">
                                <div class="bg-primary h-full rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Demographic Mix Cards --}}
            <div class="space-y-6">
                {{-- Gender Distribution --}}
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-outline-variant/5 bg-surface-container-low/20">
                        <h3 class="font-bold text-[10px] uppercase tracking-wider text-on-surface opacity-80 flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">diversity_3</span> Gender Diversity
                        </h3>
                    </div>
                    <div class="p-5 flex flex-wrap gap-3">
                        @foreach($genderDistribution as $item)
                            <div class="bg-surface-container-low/50 rounded-xl px-4 py-2.5 border border-outline-variant/5 flex flex-col items-center min-w-[100px]">
                                <span class="text-[8px] font-black uppercase tracking-widest text-on-surface-variant opacity-50 mb-1">{{ $item->gender ?? 'N/A' }}</span>
                                <span class="text-xl font-black text-on-surface">{{ $item->count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Employment Mix --}}
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-outline-variant/5 bg-surface-container-low/20">
                        <h3 class="font-bold text-[10px] uppercase tracking-wider text-on-surface opacity-80 flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">badge</span> Employment Mix
                        </h3>
                    </div>
                    <div class="p-5 flex flex-wrap gap-3">
                        @forelse($employmentTypeDistribution as $item)
                            <div class="bg-surface-container-low/50 rounded-xl px-4 py-2.5 border border-outline-variant/5 flex flex-col items-center min-w-[100px]">
                                <span class="text-[8px] font-black uppercase tracking-widest text-on-surface-variant opacity-50 mb-1">{{ str_replace('_', ' ', strtoupper($item->employment_type)) }}</span>
                                <span class="text-xl font-black text-on-surface">{{ $item->count }}</span>
                            </div>
                        @empty
                            <div class="w-full text-center py-4 opacity-30 italic text-[10px]">No classification data available.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Insight --}}
        <div class="flex items-center justify-between p-4 bg-primary/5 rounded-xl border border-primary/10">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-primary text-lg">info</span>
                <p class="text-[10px] font-bold text-primary uppercase tracking-wider italic">Snapshot accurate as of {{ now()->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
