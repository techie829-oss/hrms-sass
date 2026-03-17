<x-careers-layout>
    <div class="min-h-screen bg-surface-container-lowest">
        <!-- Hero Section -->
        <div class="bg-primary/5 border-b border-outline-variant/15 pt-20 pb-16">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <h1 class="text-4xl md:text-5xl font-bold tracking-tight mb-4 text-on-surface">Careers at {{ $tenant->name ?? 'Our Company' }}</h1>
                <p class="text-lg opacity-80 max-w-2xl mx-auto font-medium text-on-surface-variant">
                    Join our team and help us build the future. Explore our open positions below.
                </p>
            </div>
        </div>

        <!-- Job Listings -->
        <div class="max-w-7xl mx-auto px-6 py-16">
            @if(session('success'))
                <div class="alert alert-success mb-10 border border-success/20 bg-success/10 text-success-content rounded-xl">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="mb-8 flex items-center justify-between">
                <h2 class="text-2xl font-bold">Open Positions</h2>
                <span class="badge badge-primary badge-outline font-bold">{{ $postings->count() }} Jobs</span>
            </div>

            <div class="space-y-4">
                @forelse($postings as $posting)
                    <a href="{{ route('tenant.careers.show', ['job_posting' => $posting->share_key]) }}" class="block group">
                        <div class="card bg-base-100 border border-outline-variant/30 shadow-sm hover:shadow-md hover:border-primary/50 transition-all duration-300">
                            <div class="card-body p-6 flex flex-col md:flex-row md:items-center justify-between gap-6">
                                <div>
                                    <h3 class="text-xl font-bold group-hover:text-primary transition-colors">{{ $posting->title }}</h3>
                                    <div class="flex flex-wrap items-center gap-3 mt-3 text-sm font-medium opacity-80">
                                        <div class="flex items-center gap-1.5">
                                            <span class="material-symbols-outlined text-[16px]">business_center</span>
                                            {{ ucwords(str_replace('_', ' ', $posting->employment_type)) }}
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="material-symbols-outlined text-[16px]">location_on</span>
                                            {{ $posting->location ?? 'Remote / Flexible' }}
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="material-symbols-outlined text-[16px]">work</span>
                                            {{ $posting->department->name ?? 'General' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <span class="btn btn-outline btn-sm group-hover:btn-primary">View Details &rarr;</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-20 border-2 border-dashed border-outline-variant/30 rounded-2xl">
                        <span class="material-symbols-outlined text-6xl opacity-20 mb-4">work_off</span>
                        <h3 class="text-xl font-bold mb-2">No Openings Available</h3>
                        <p class="opacity-70 font-medium">We currently don't have any open positions. Please check back later.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-careers-layout>
