<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Performance</h1>
                    <p class="opacity-70 mt-1.5 font-medium">Monitor KPIs, appraisals, and employee goals.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('performance.appraisals.index') }}" class="btn btn-primary btn-sm flex items-center gap-2 px-5">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        New Appraisal
                    </a>
                    <a href="{{ route('performance.goals.index') }}" class="btn btn-outline btn-sm flex items-center gap-2 px-5">
                        <span class="material-symbols-outlined text-[18px]">flag</span>
                        Manage Goals
                    </a>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                <div class="card bg-base-100 border border-base-200 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="card-body p-6">
                        <div class="flex items-center gap-4 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary text-[20px]">analytics</span>
                            </div>
                            <h3 class="font-bold text-xs uppercase tracking-widest opacity-70">Active KPIs</h3>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-4xl font-bold">{{ $kpiCount }}</span>
                        </div>
                    </div>
                </div>

                <div class="card bg-base-100 border border-base-200 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="card-body p-6">
                        <div class="flex items-center gap-4 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-secondary text-[20px]">rate_review</span>
                            </div>
                            <h3 class="font-bold text-xs uppercase tracking-widest opacity-70">Pending Appraisals</h3>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-4xl font-bold">{{ $pendingAppraisals }}</span>
                        </div>
                    </div>
                </div>

                <div class="card bg-base-100 border border-base-200 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="card-body p-6">
                        <div class="flex items-center gap-4 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-accent text-[20px]">target</span>
                            </div>
                            <h3 class="font-bold text-xs uppercase tracking-widest opacity-70">Active Goals</h3>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-4xl font-bold">{{ $activeGoals }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recent Appraisals -->
                <div class="lg:col-span-2">
                    <div class="card bg-base-100 border border-base-200 shadow-sm overflow-hidden min-h-[400px]">
                        <div class="p-6 border-b border-base-200 flex items-center justify-between">
                            <h2 class="text-lg font-bold">Recent Appraisals</h2>
                            <a href="{{ route('performance.appraisals.index') }}" class="btn btn-ghost btn-xs text-primary font-bold">View History</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="table table-md">
                                <thead class="bg-base-200/50">
                                    <tr>
                                        <th class="font-bold text-[11px] uppercase tracking-wider py-4 opacity-70">Employee</th>
                                        <th class="font-bold text-[11px] uppercase tracking-wider py-4 opacity-70">Period</th>
                                        <th class="font-bold text-[11px] uppercase tracking-wider py-4 text-center opacity-70">Score</th>
                                        <th class="font-bold text-[11px] uppercase tracking-wider py-4 text-center opacity-70">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentAppraisals as $appraisal)
                                        <tr class="hover:bg-base-200/50 transition-colors border-b border-base-200">
                                            <td class="py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="avatar placeholder">
                                                        <div class="bg-primary/10 text-primary w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs">
                                                            <span>{{ substr($appraisal->employee->first_name, 0, 1) }}{{ substr($appraisal->employee->last_name, 0, 1) }}</span>
                                                        </div>
                                                    </div>
                                                    <span class="text-sm font-semibold">{{ $appraisal->employee->full_name }}</span>
                                                </div>
                                            </td>
                                            <td class="text-sm font-medium py-4 opacity-80">{{ $appraisal->review_period }}</td>
                                            <td class="text-center py-4">
                                                @if($appraisal->score)
                                                    <span class="text-sm font-bold {{ $appraisal->score >= 80 ? 'text-success' : ($appraisal->score >= 50 ? 'text-warning' : 'text-error') }}">
                                                        {{ number_format($appraisal->score, 1) }}%
                                                    </span>
                                                @else
                                                    <span class="font-medium text-xs opacity-50">--</span>
                                                @endif
                                            </td>
                                            <td class="text-center py-4">
                                                <span class="badge badge-sm font-bold
                                                    @if($appraisal->status === 'completed') badge-success
                                                    @elseif($appraisal->status === 'pending') badge-warning
                                                    @else badge-ghost @endif">
                                                    {{ ucfirst($appraisal->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-12 text-center italic font-medium opacity-50">No recent appraisals found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Shortcuts/Quick Actions -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="card bg-base-100 border border-base-200 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-base-200">
                            <h2 class="text-lg font-bold">Quick Insights</h2>
                        </div>
                        <div class="card-body p-6 space-y-4">
                            <div class="flex items-start gap-4 p-4 rounded-xl bg-base-200 transition-all hover:bg-base-300 cursor-default group border border-transparent hover:border-base-300">
                                <div class="w-10 h-10 rounded-lg bg-info/10 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-info text-[20px]">lightbulb</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-xs uppercase tracking-widest mb-1 opacity-70">Observation</h4>
                                    <p class="text-sm font-medium leading-snug">Goal alignment across the R&D department is currently at 85%.</p>
                                </div>
                            </div>
                            <!-- Add more insights as needed -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
