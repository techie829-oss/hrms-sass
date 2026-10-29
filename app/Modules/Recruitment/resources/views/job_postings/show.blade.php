<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('recruitment.job_postings.index') }}" class="btn btn-ghost btn-sm btn-square shrink-0" title="Back to Job Postings">
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                </a>
                <div class="min-w-0">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h2 class="text-xl font-bold text-slate-900 truncate">{{ $posting->title }}</h2>
                        @php
                            $statusConfig = [
                                'open' => ['class' => 'badge-success', 'icon' => 'public'],
                                'draft' => ['class' => 'badge-ghost', 'icon' => 'edit_note'],
                                'closed' => ['class' => 'badge-neutral', 'icon' => 'lock'],
                            ];
                            $config = $statusConfig[$posting->status] ?? $statusConfig['draft'];
                        @endphp
                        <span class="badge {{ $config['class'] }} badge-sm gap-1 font-semibold text-xs py-2.5">
                            <span class="material-symbols-outlined text-[13px]">{{ $config['icon'] }}</span>
                            {{ ucfirst($posting->status) }}
                        </span>
                    </div>
                    <p class="text-xs font-medium text-slate-500 mt-1">
                        {{ $posting->location ?? 'Remote' }} &bull; {{ ucwords(str_replace('_', ' ', $posting->employment_type)) }}
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2 shrink-0">
                @if($posting->status === 'open')
                    <button type="button" 
                        id="copyLinkBtn"
                        onclick="copyShareLink(this, '{{ url('/careers/' . $posting->share_key) }}')" 
                        class="btn btn-outline btn-primary btn-sm gap-1.5">
                        <span class="material-symbols-outlined text-base">link</span>
                        <span class="btn-text">Copy Public Link</span>
                    </button>
                @endif
                @can('update', $posting)
                    <a href="{{ route('recruitment.job_postings.edit', $posting->id) }}" class="btn btn-secondary btn-sm gap-1.5">
                        <span class="material-symbols-outlined text-base">edit</span> Edit Posting
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Applicants Pipeline -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-sm font-bold flex items-center gap-2 text-slate-800">
                        <span class="material-symbols-outlined text-primary text-base">groups</span>
                        Candidates Pipeline
                        <span class="badge badge-primary badge-sm ml-1">{{ $posting->applications->count() }}</span>
                    </h3>
                    <a href="{{ route('recruitment.applications.index', ['posting_id' => $posting->id]) }}" class="btn btn-ghost btn-xs text-primary font-semibold">
                        View All
                    </a>
                </div>

                <div>
                    @forelse($posting->applications as $app)
                        @php
                            $stageBadge = [
                                'new'         => 'badge-ghost',
                                'reviewing'   => 'badge-info',
                                'shortlisted' => 'badge-warning',
                                'interview'   => 'badge-secondary',
                                'offered'     => 'badge-accent',
                                'hired'       => 'badge-success',
                                'rejected'    => 'badge-error',
                            ][$app->status] ?? 'badge-ghost';
                        @endphp
                        <div class="flex items-center justify-between px-6 py-3.5 border-b border-slate-100 last:border-b-0 hover:bg-slate-50/60 transition-colors">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="avatar placeholder shrink-0">
                                    <div class="bg-primary/10 text-primary rounded-lg w-9 h-9 font-bold text-xs flex items-center justify-center">
                                        {{ strtoupper(substr($app->first_name, 0, 1) . substr($app->last_name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-slate-900 truncate">{{ $app->full_name }}</p>
                                    <div class="flex items-center gap-2 text-xs text-slate-500 mt-0.5">
                                        <span class="truncate">{{ $app->email }}</span>
                                        @if($app->phone)
                                            <span class="text-slate-300">&bull;</span>
                                            <span>{{ $app->phone }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 shrink-0 ml-3">
                                <span class="badge {{ $stageBadge }} badge-sm font-bold uppercase text-[10px] py-2 px-2.5">
                                    {{ $app->status }}
                                </span>
                                <a href="{{ route('recruitment.applications.show', $app->id) }}" class="btn btn-ghost btn-xs btn-square text-primary" title="View Candidate">
                                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <span class="material-symbols-outlined text-5xl opacity-30 mb-3 text-slate-400">person_add</span>
                            <h4 class="text-base font-bold text-slate-800">No Candidates Yet</h4>
                            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                                Once published, candidates who apply will appear here grouped by their stage in the hiring process.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Posting Details & Description -->
        <div class="space-y-6">
            
            <!-- Posting Details Card -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="p-6">
                    <h3 class="text-xs font-bold text-primary uppercase tracking-widest border-b border-slate-100 pb-3 mb-4">
                        Posting Details
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Salary Range</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $posting->salary_range ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Employment Type</p>
                            <p class="text-sm font-semibold text-slate-800">{{ ucwords(str_replace('_', ' ', $posting->employment_type)) }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Location</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $posting->location ?? 'Remote' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Closing Date</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $posting->closing_date ? $posting->closing_date->format('F d, Y') : 'Open Ended' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Date Created</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $posting->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>

                    @if($posting->status === 'open')
                        <div class="mt-6 pt-5 border-t border-slate-100">
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Public Careers Page Link</p>
                            <div class="flex items-center gap-1.5 p-1.5 rounded-lg bg-slate-50 border border-slate-200">
                                <input type="text" readonly value="{{ url('/careers/' . $posting->share_key) }}" 
                                       class="bg-transparent border-none text-xs text-slate-600 font-mono flex-1 focus:ring-0 p-1 select-all" />
                                <button type="button" 
                                    onclick="copyShareLink(this, '{{ url('/careers/' . $posting->share_key) }}')"
                                    class="btn btn-ghost btn-xs text-primary font-semibold shrink-0">
                                    Copy
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Job Description Card -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="p-6">
                    <h3 class="text-xs font-bold text-primary uppercase tracking-widest border-b border-slate-100 pb-3 mb-4">
                        Job Description
                    </h3>
                    <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                        {{ $posting->description }}
                    </div>
                    
                    @if($posting->requirements)
                        <h3 class="text-xs font-bold text-primary uppercase tracking-widest border-b border-slate-100 pb-3 mb-4 mt-6">
                            Requirements
                        </h3>
                        <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                            {{ $posting->requirements }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- Interactive Copy Script --}}
    <script>
        function copyShareLink(btn, url) {
            navigator.clipboard.writeText(url).then(() => {
                const textElem = btn.querySelector('.btn-text') || btn;
                const originalText = textElem.innerText;
                textElem.innerText = 'Copied!';
                setTimeout(() => {
                    textElem.innerText = originalText;
                }, 2000);
            }).catch(() => {
                const textarea = document.createElement('textarea');
                textarea.value = url;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                alert('Public link copied to clipboard!');
            });
        }
    </script>
</x-app-layout>
