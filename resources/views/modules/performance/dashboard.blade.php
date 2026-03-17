<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-on-surface">Performance Analytics</h2>
            <div class="flex gap-2">
                <a href="{{ route('performance.goals.index') }}" class="btn btn-sm btn-ghost border-outline-variant/10 rounded-lg font-bold text-[10px] uppercase tracking-wider">
                    <span class="material-symbols-outlined text-sm">flag</span> Manage Goals
                </a>
                <a href="{{ route('performance.appraisals.index') }}" class="btn btn-sm btn-primary border-none rounded-lg font-bold text-[10px] uppercase tracking-wider shadow-sm">
                    <span class="material-symbols-outlined text-sm">add_circle</span> New Appraisal
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Compact Stats Row (Matches Dashboard) -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/10 shadow-sm transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-on-surface-variant">Active KPIs</span>
                    <span class="material-symbols-outlined text-primary text-base">analytics</span>
                </div>
                <div class="text-2xl font-bold text-on-surface">{{ $kpiCount }}</div>
                <div class="text-[9px] font-bold text-success mt-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[10px]">check_circle</span> Tracking Status
                </div>
            </div>

            <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/10 shadow-sm transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-on-surface-variant">Pending Appraisals</span>
                    <span class="material-symbols-outlined text-secondary text-base">rate_review</span>
                </div>
                <div class="text-2xl font-bold text-on-surface">{{ $pendingAppraisals }}</div>
                <div class="text-[9px] font-bold text-warning mt-1 italic opacity-70">Awaiting Feedback</div>
            </div>

            <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/10 shadow-sm transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-on-surface-variant">Active Goals</span>
                    <span class="material-symbols-outlined text-tertiary text-base">target</span>
                </div>
                <div class="text-2xl font-bold text-tertiary">{{ $activeGoals }}</div>
                <div class="text-[9px] font-bold text-on-surface-variant mt-1 italic opacity-70">Employee targets</div>
            </div>

            <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/10 shadow-sm transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-on-surface-variant">Avg. Score</span>
                    <span class="material-symbols-outlined text-primary text-base">monitoring</span>
                </div>
                <div class="text-2xl font-bold text-on-surface">84<span class="text-xs opacity-40">%</span></div>
                <div class="badge badge-success badge-outline text-[8px] font-bold uppercase h-4 py-0">Healthy</div>
            </div>

            <div class="hidden lg:block bg-primary p-5 rounded-xl shadow-sm relative overflow-hidden transition-all">
                <div class="relative z-10">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-white/80">Growth Index</span>
                    <div class="text-2xl font-bold text-white">+12.4%</div>
                    <div class="w-full bg-white/20 h-1.5 rounded-full mt-2 overflow-hidden">
                        <div class="bg-white h-full rounded-full" style="width: 75%"></div>
                    </div>
                </div>
                <span class="material-symbols-outlined absolute -right-3 -bottom-3 text-5xl opacity-10 text-white">trending_up</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left Side: Recent Appraisals -->
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-outline-variant/5 flex items-center justify-between">
                        <h3 class="font-bold text-xs uppercase tracking-wider text-on-surface">Recent Appraisals</h3>
                        <a href="{{ route('performance.appraisals.index') }}" class="text-[9px] font-bold text-primary hover:underline uppercase tracking-wider">View History</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-xs table-zebra w-full text-[11px]">
                            <thead>
                                <tr class="text-on-surface-variant/70 border-b border-outline-variant/5">
                                    <th class="py-3 px-5">Employee</th>
                                    <th>Review Period</th>
                                    <th class="text-center">Score</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-right pr-5">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="font-medium">
                                @forelse($recentAppraisals as $appraisal)
                                <tr class="hover:bg-primary/5 transition-colors border-b border-outline-variant/5">
                                    <td class="py-3 px-5">
                                        <div class="flex items-center gap-3">
                                            <div class="avatar placeholder">
                                                <div class="bg-primary/10 text-primary rounded-lg w-8 h-8 font-bold text-[9px]">
                                                    {{ substr($appraisal->employee->first_name, 0, 1) }}{{ substr($appraisal->employee->last_name, 0, 1) }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-bold text-on-surface">{{ $appraisal->employee->full_name }}</div>
                                                <div class="text-[9px] opacity-60">{{ $appraisal->employee->designation->name ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $appraisal->review_period }}</td>
                                    <td class="text-center">
                                        @if($appraisal->score)
                                            <span class="font-bold {{ $appraisal->score >= 80 ? 'text-success' : ($appraisal->score >= 50 ? 'text-warning' : 'text-error') }}">
                                                {{ number_format($appraisal->score, 1) }}%
                                            </span>
                                        @else
                                            <span class="opacity-30">--</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($appraisal->status === 'completed')
                                            <span class="badge badge-success badge-sm text-[8px] font-bold text-white px-2 py-0.5 h-auto">COMPLETED</span>
                                        @elseif($appraisal->status === 'pending')
                                            <span class="badge badge-warning badge-sm text-[8px] font-bold text-white px-2 py-0.5 h-auto">PENDING</span>
                                        @else
                                            <span class="badge badge-neutral badge-sm text-[8px] font-bold text-white px-2 py-0.5 h-auto uppercase">{{ $appraisal->status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right pr-5">
                                        <a href="{{ route('performance.appraisals.show', $appraisal->id) }}" class="btn btn-ghost btn-xs rounded-md">
                                            <span class="material-symbols-outlined text-sm">visibility</span>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-on-surface-variant opacity-70 italic">No recent appraisals found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-surface-container-low p-6 rounded-xl border border-outline-variant/10 shadow-sm">
                        <h4 class="text-[9px] font-bold uppercase tracking-wider mb-6 text-on-surface-variant">Rating Distribution</h4>
                        <div class="space-y-5">
                            <div>
                                <div class="flex justify-between text-[9px] font-bold mb-1.5 uppercase"><span>Outstanding (90-100)</span><span>15%</span></div>
                                <progress class="progress progress-success h-1.5" value="15" max="100"></progress>
                            </div>
                            <div>
                                <div class="flex justify-between text-[9px] font-bold mb-1.5 uppercase"><span>Exceeds Expectations (80-89)</span><span>42%</span></div>
                                <progress class="progress progress-primary h-1.5" value="42" max="100"></progress>
                            </div>
                            <div>
                                <div class="flex justify-between text-[9px] font-bold mb-1.5 uppercase"><span>Meets Expectations (70-79)</span><span>38%</span></div>
                                <progress class="progress progress-secondary h-1.5" value="38" max="100"></progress>
                            </div>
                        </div>
                    </div>
                    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm flex flex-col justify-center items-center text-center">
                        <div class="radial-progress text-primary mb-4" style="--value:86; --size:4.5rem; --thickness: 4px;" role="progressbar">
                            <span class="text-[10px] font-bold">86%</span>
                        </div>
                        <h4 class="text-[10px] font-bold uppercase tracking-wider text-on-surface">Alignment Scroe</h4>
                        <p class="text-[9px] text-on-surface-variant font-medium mt-1">Goal vs Completion status</p>
                    </div>
                </div>
            </div>

            <!-- Right Side: Quick Action and Insights -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Quick Insights (Matches Dashboard Style) -->
                <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm transition-all">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-[9px] font-bold uppercase tracking-wider text-on-surface-variant">Quick Insights</h4>
                        <span class="badge badge-primary badge-outline text-[8px] font-bold uppercase py-0">Live</span>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-surface-container-low transition-all hover:bg-surface-container-low/60 cursor-default group border border-outline-variant/5">
                            <div class="w-9 h-9 rounded-lg bg-info/10 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                <span class="material-symbols-outlined text-info text-base">lightbulb</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-[10px] uppercase tracking-wider mb-1 text-on-surface">Goal Alignment</h4>
                                <p class="text-[10px] font-semibold text-on-surface-variant leading-snug">R&D team has reached 85% goal alignment this quarter.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 p-4 rounded-xl bg-surface-container-low transition-all hover:bg-surface-container-low/60 cursor-default group border border-outline-variant/5">
                            <div class="w-9 h-9 rounded-lg bg-success/10 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                <span class="material-symbols-outlined text-success text-base">trending_up</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-[10px] uppercase tracking-wider mb-1 text-on-surface">Top Performer</h4>
                                <p class="text-[10px] font-semibold text-on-surface-variant leading-snug">Engineering department shows highest appraisal completion rate.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Widget -->
                <div class="bg-secondary p-6 rounded-xl shadow-sm relative overflow-hidden transition-all">
                    <div class="relative z-10">
                        <span class="text-[9px] font-bold uppercase tracking-wider text-white/80">Reviews</span>
                        <div class="text-xl font-bold text-white mt-1 italic uppercase tracking-tighter">Feedback Cycle</div>
                        <p class="text-[9px] text-white/70 font-medium mt-1 leading-relaxed">System is ready for 360-degree feedback sessions.</p>
                        <button class="btn btn-xs bg-white text-secondary border-none rounded-lg font-bold text-[8px] uppercase tracking-widest mt-4">Initiate Cycle</button>
                    </div>
                    <span class="material-symbols-outlined absolute -right-3 -bottom-3 text-6xl opacity-10 text-white">rate_review</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
