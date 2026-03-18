<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('recruitment.job_postings.index') }}" class="btn btn-ghost btn-sm btn-square">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-bold">{{ $posting->title }}</h2>
                    @php
                        $statusConfig = [
                            'open' => ['class' => 'badge-success', 'icon' => 'public'],
                            'draft' => ['class' => 'badge-ghost', 'icon' => 'edit_note'],
                            'closed' => ['class' => 'badge-neutral', 'icon' => 'lock'],
                        ];
                        $config = $statusConfig[$posting->status] ?? $statusConfig['draft'];
                    @endphp
                    <span class="badge {{ $config['class'] }} gap-1 font-semibold text-xs py-3">
                        <span class="material-symbols-outlined text-[14px]">{{ $config['icon'] }}</span>
                        {{ ucfirst($posting->status) }}
                    </span>
                </div>
                <p class="text-sm opacity-70 mt-1">{{ $posting->location ?? 'Remote' }} &bull; {{ str_replace('_', ' ', $posting->employment_type) }}</p>
            </div>
            
            <div class="ml-auto flex items-center gap-3">
                @if($posting->status === 'open')
                    <button onclick="let t=document.createElement('textarea');t.value='{{ url("/careers/" . $posting->share_key) }}';document.body.appendChild(t);t.select();document.execCommand('copy');document.body.removeChild(t); this.innerText = 'Copied!'; setTimeout(() => this.innerText = 'Copy Public Link', 2000);" class="btn btn-outline btn-primary">
                        <span class="material-symbols-outlined text-base">link</span> Copy Public Link
                    </button>
                @endif
                <a href="{{ route('recruitment.job_postings.edit', $posting->id) }}" class="btn btn-secondary">
                    <span class="material-symbols-outlined text-base">edit</span> Edit Posting
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Applicants -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body p-0">
                    <div class="p-6 border-b border-base-200 flex justify-between items-center bg-base-200/50">
                        <h3 class="text-sm font-bold flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">groups</span>
                            Candidates Pipeline
                            <span class="badge badge-primary badge-sm ml-1">{{ $posting->applications->count() }}</span>
                        </h3>
                        <a href="{{ route('recruitment.applications.index', ['posting_id' => $posting->id]) }}" class="btn btn-ghost btn-xs">View All</a>
                    </div>

                    @forelse($posting->applications->take(8) as $app)
                        @php
                            $stageBadge = [
                                'new'=>'badge-ghost','reviewing'=>'badge-info','shortlisted'=>'badge-warning',
                                'interview'=>'badge-secondary','offered'=>'badge-accent','hired'=>'badge-success','rejected'=>'badge-error',
                            ][$app->status] ?? 'badge-ghost';
                        @endphp
                        <div class="flex items-center justify-between px-6 py-3 border-b border-base-200 last:border-b-0 hover:bg-base-200/30 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="avatar placeholder">
                                    <div class="bg-secondary/10 text-secondary rounded-lg w-8 h-8 font-bold text-[10px]">
                                        {{ strtoupper(substr($app->first_name,0,1).substr($app->last_name,0,1)) }}
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-bold">{{ $app->full_name }}</p>
                                    <p class="text-[11px] opacity-60">{{ $app->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="badge {{ $stageBadge }} badge-sm font-bold uppercase text-[10px]">{{ $app->status }}</span>
                                <a href="{{ route('recruitment.applications.show', $app->id) }}" class="btn btn-ghost btn-xs btn-square text-primary">
                                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <span class="material-symbols-outlined text-6xl opacity-30 mb-4">person_add</span>
                            <h4 class="text-lg font-bold">No Candidates Yet</h4>
                            <p class="text-sm opacity-70 mt-2 max-w-md mx-auto">Once published, candidates who apply will appear here grouped by their stage in the hiring process.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Posting Details -->
        <div class="space-y-6">
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="text-xs font-bold text-primary uppercase tracking-widest border-b border-base-200 pb-3 mb-4">Posting Details</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] font-bold opacity-70 uppercase tracking-wider mb-1">Salary Range</p>
                            <p class="text-sm font-medium">{{ $posting->salary_range ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold opacity-70 uppercase tracking-wider mb-1">Closing Date</p>
                            <p class="text-sm font-medium">{{ $posting->closing_date ? $posting->closing_date->format('F d, Y') : 'Open Ended' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold opacity-70 uppercase tracking-wider mb-1">Date Created</p>
                            <p class="text-sm font-medium">{{ $posting->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="text-xs font-bold text-primary uppercase tracking-widest border-b border-base-200 pb-3 mb-4">Job Description</h3>
                    <div class="prose prose-sm max-w-none">
                        {!! nl2br(e($posting->description)) !!}
                    </div>
                    
                    @if($posting->requirements)
                        <h3 class="text-xs font-bold text-primary uppercase tracking-widest border-b border-base-200 pb-3 mb-4 mt-6">Requirements</h3>
                        <div class="prose prose-sm max-w-none">
                            {!! nl2br(e($posting->requirements)) !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
