<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-on-surface">Reports & Analytics</h2>
            <div class="flex gap-2">
                <span class="text-[9px] font-bold text-on-surface-variant uppercase tracking-widest italic opacity-50">Enterprise Insights</span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        {{-- Dashboard Summary Header --}}
        <div class="mb-2">
            <p class="text-sm font-medium text-on-surface-variant opacity-70">Strategic insights across workforce, attendance, and financial cycles.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Workforce Report Card --}}
            <div class="card bg-surface-container-lowest border border-outline-variant/10 shadow-sm hover:border-primary/30 transition-all group rounded-xl overflow-hidden">
                <div class="h-1 w-full bg-primary opacity-20 group-hover:opacity-100 transition-opacity"></div>
                <div class="card-body p-6 text-left">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center shadow-sm">
                            <span class="material-symbols-outlined text-2xl">groups</span>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-on-surface leading-tight">Workforce</h2>
                            <p class="text-[9px] font-bold text-primary uppercase tracking-widest mt-0.5">Demographics & Distribution</p>
                        </div>
                    </div>
                    <p class="text-xs font-medium text-on-surface-variant leading-relaxed opacity-70 mb-8">Analyze headcount growth, department density, and employee diversity metrics.</p>
                    <div class="card-actions justify-end border-t border-outline-variant/5 pt-4">
                        <a href="{{ route('reports.workforce') }}" class="btn btn-primary btn-sm rounded-lg font-bold text-[10px] uppercase tracking-wider px-6 shadow-sm shadow-primary/10">
                            Launch Report →
                        </a>
                    </div>
                </div>
            </div>

            {{-- Attendance Report Card --}}
            <div class="card bg-surface-container-lowest border border-outline-variant/10 shadow-sm hover:border-secondary/30 transition-all group rounded-xl overflow-hidden">
                <div class="h-1 w-full bg-secondary opacity-20 group-hover:opacity-100 transition-opacity"></div>
                <div class="card-body p-6 text-left">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-12 h-12 bg-secondary/10 text-secondary rounded-xl flex items-center justify-center shadow-sm">
                            <span class="material-symbols-outlined text-2xl">event_available</span>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-on-surface leading-tight">Attendance</h2>
                            <p class="text-[9px] font-bold text-secondary uppercase tracking-widest mt-0.5">Punctuality & Trends</p>
                        </div>
                    </div>
                    <p class="text-xs font-medium text-on-surface-variant leading-relaxed opacity-70 mb-8">Track daily trends, late occurrences, and absence patterns organization-wide.</p>
                    <div class="card-actions justify-end border-t border-outline-variant/5 pt-4">
                        <a href="{{ route('reports.attendance') }}" class="btn btn-secondary btn-sm rounded-lg font-bold text-[10px] uppercase tracking-wider px-6 shadow-sm shadow-secondary/10 text-white">
                            Launch Report →
                        </a>
                    </div>
                </div>
            </div>

            {{-- Payroll Report Card --}}
            <div class="card bg-surface-container-lowest border border-outline-variant/10 shadow-sm hover:border-accent/30 transition-all group rounded-xl overflow-hidden">
                <div class="h-1 w-full bg-accent opacity-20 group-hover:opacity-100 transition-opacity"></div>
                <div class="card-body p-6 text-left">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-12 h-12 bg-accent/10 text-accent rounded-xl flex items-center justify-center shadow-sm">
                            <span class="material-symbols-outlined text-2xl">payments</span>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-on-surface leading-tight">Payroll</h2>
                            <p class="text-[9px] font-bold text-accent uppercase tracking-widest mt-0.5">Cost & Compliance</p>
                        </div>
                    </div>
                    <p class="text-xs font-medium text-on-surface-variant leading-relaxed opacity-70 mb-8">Monitor cycle payouts, statutory deductions, and total labor cost summary.</p>
                    <div class="card-actions justify-end border-t border-outline-variant/5 pt-4">
                        <a href="{{ route('reports.payroll') }}" class="btn btn-accent btn-sm rounded-lg font-bold text-[10px] uppercase tracking-wider px-6 shadow-sm shadow-accent/10 text-white">
                            Launch Report →
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Future Insights Placeholders --}}
        <div class="pt-6">
            <div class="bg-surface-container-low/30 border-2 border-dashed border-outline-variant/20 rounded-2xl p-10 text-center">
                <div class="flex flex-col items-center gap-3 opacity-30">
                    <span class="material-symbols-outlined text-5xl">monitoring</span>
                    <p class="text-xs font-bold uppercase tracking-widest">More analytical insights coming soon</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
