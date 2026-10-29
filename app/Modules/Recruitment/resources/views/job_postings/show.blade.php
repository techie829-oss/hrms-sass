<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 w-full">
            {{-- Title & Back Button --}}
            <div class="flex items-start sm:items-center gap-3 sm:gap-4 min-w-0">
                <a href="{{ route('recruitment.job_postings.index') }}" 
                   class="w-10 h-10 rounded-xl border border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50 flex items-center justify-center text-slate-600 hover:text-slate-900 transition-all shrink-0 shadow-2xs group" 
                   title="Back to Job Postings">
                    <span class="material-symbols-outlined text-[20px] group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
                </a>
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight truncate max-w-xl">
                            {{ $posting->title }}
                        </h1>
                        @php
                            $statusConfig = match($posting->status) {
                                'open' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200/80', 'dot' => 'bg-emerald-500', 'icon' => 'public'],
                                'closed' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'border' => 'border-slate-200', 'dot' => 'bg-slate-400', 'icon' => 'lock'],
                                default => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200/80', 'dot' => 'bg-amber-500', 'icon' => 'edit_note'],
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} border {{ $statusConfig['border'] }} shadow-2xs shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>
                            <span class="capitalize">{{ $posting->status }}</span>
                        </span>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 mt-1">
                        <span class="inline-flex items-center gap-1">
                            <span class="material-symbols-outlined text-[15px] text-slate-400">location_on</span>
                            {{ $posting->location ?? 'Remote' }}
                        </span>
                        <span class="text-slate-300">&bull;</span>
                        <span class="inline-flex items-center gap-1">
                            <span class="material-symbols-outlined text-[15px] text-slate-400">schedule</span>
                            {{ ucwords(str_replace('_', ' ', $posting->employment_type)) }}
                        </span>
                        @if($posting->closing_date)
                            <span class="text-slate-300">&bull;</span>
                            <span class="inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-[15px] text-slate-400">event_busy</span>
                                Closes: {{ $posting->closing_date->format('M d, Y') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-wrap items-center gap-2.5 shrink-0 self-start md:self-center">
                @if($posting->status === 'open')
                    <button type="button" 
                        id="copyLinkBtn"
                        onclick="copyShareLink(this, '{{ url('/careers/' . $posting->share_key) }}')"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200/80 shadow-2xs transition-all active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">link</span>
                        <span class="btn-label">Copy Public Link</span>
                    </button>
                @endif
                @can('update', $posting)
                    <a href="{{ route('recruitment.job_postings.edit', $posting->id) }}" 
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition-all active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                        <span>Edit Posting</span>
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- Top Metrics Bar --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-2xs hover:shadow-xs transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Candidates</p>
                        <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $posting->applications->count() }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">groups</span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-2xs hover:shadow-xs transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">In Interview</p>
                        <h3 class="text-2xl font-black text-purple-700 mt-1">
                            {{ $posting->applications->where('status', 'interview')->count() }}
                        </h3>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">record_voice_over</span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-2xs hover:shadow-xs transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Offered / Hired</p>
                        <h3 class="text-2xl font-black text-emerald-700 mt-1">
                            {{ $posting->applications->whereIn('status', ['offered', 'hired'])->count() }}
                        </h3>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">verified</span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-2xs hover:shadow-xs transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Posting Timeline</p>
                        <p class="text-sm font-bold text-slate-800 mt-1.5">
                            @if($posting->closing_date)
                                {{ $posting->closing_date->isPast() ? 'Closed ' . $posting->closing_date->diffForHumans() : 'Closes ' . $posting->closing_date->diffForHumans() }}
                            @else
                                Open Ended
                            @endif
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">hourglass_bottom</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Two-Column Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Left Column: Applicants Pipeline --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                    <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3 bg-slate-50/50">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[19px]">group</span>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Candidates Pipeline</h3>
                                <p class="text-xs text-slate-500">Applicants who applied for this opening</p>
                            </div>
                            <span class="ml-1.5 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700">
                                {{ $posting->applications->count() }}
                            </span>
                        </div>

                        <a href="{{ route('recruitment.applications.index', ['posting_id' => $posting->id]) }}" 
                           class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                            <span>View All in Applications</span>
                            <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
                        </a>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse($posting->applications as $app)
                            @php
                                $stageStyle = match($app->status) {
                                    'new' => ['bg' => 'bg-sky-50', 'text' => 'text-sky-700', 'border' => 'border-sky-200/80', 'dot' => 'bg-sky-500'],
                                    'reviewing' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'border' => 'border-indigo-200/80', 'dot' => 'bg-indigo-500'],
                                    'shortlisted' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200/80', 'dot' => 'bg-amber-500'],
                                    'interview' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200/80', 'dot' => 'bg-purple-500'],
                                    'offered' => ['bg' => 'bg-cyan-50', 'text' => 'text-cyan-700', 'border' => 'border-cyan-200/80', 'dot' => 'bg-cyan-500'],
                                    'hired' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200/80', 'dot' => 'bg-emerald-500'],
                                    'rejected' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-200/80', 'dot' => 'bg-rose-500'],
                                    default => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'border' => 'border-slate-200', 'dot' => 'bg-slate-400'],
                                };

                                $initials = strtoupper(substr($app->first_name, 0, 1) . substr($app->last_name, 0, 1));
                            @endphp
                            <a href="{{ route('recruitment.applications.show', $app->id) }}" 
                               class="group flex items-center justify-between p-4 sm:px-6 hover:bg-slate-50/80 transition-all duration-150">
                                
                                {{-- Candidate Info --}}
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-50 to-indigo-100/60 border border-indigo-200/80 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0 shadow-2xs group-hover:scale-105 transition-transform">
                                        {{ $initials }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors truncate">
                                            {{ $app->full_name }}
                                        </p>
                                        <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500 mt-0.5">
                                            <span class="truncate">{{ $app->email }}</span>
                                            @if($app->phone)
                                                <span class="text-slate-300">&bull;</span>
                                                <span>{{ $app->phone }}</span>
                                            @endif
                                            @if($app->applied_at)
                                                <span class="text-slate-300">&bull;</span>
                                                <span class="text-[11px] text-slate-400">Applied {{ $app->applied_at->diffForHumans() }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Stage Badge & Action --}}
                                <div class="flex items-center gap-3 shrink-0 ml-3">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $stageStyle['bg'] }} {{ $stageStyle['text'] }} border {{ $stageStyle['border'] }} shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $stageStyle['dot'] }}"></span>
                                        {{ $app->status }}
                                    </span>
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 group-hover:text-indigo-600 group-hover:bg-indigo-50 transition-all">
                                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="p-12 text-center">
                                <div class="w-16 h-16 rounded-2xl bg-indigo-50/80 text-indigo-500 flex items-center justify-center mx-auto mb-4 border border-indigo-100">
                                    <span class="material-symbols-outlined text-3xl">person_search</span>
                                </div>
                                <h4 class="text-base font-bold text-slate-900">No Candidates Applied Yet</h4>
                                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                                    Share the public careers link to invite candidates to apply for this job posting.
                                </p>
                                @if($posting->status === 'open')
                                    <button type="button" 
                                        onclick="copyShareLink(this, '{{ url('/careers/' . $posting->share_key) }}')"
                                        class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-all shadow-sm">
                                        <span class="material-symbols-outlined text-[16px]">link</span>
                                        <span class="btn-label">Copy Public Careers Link</span>
                                    </button>
                                @endif
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Right Column: Posting Details & Description --}}
            <div class="space-y-6">
                
                {{-- Key Details Card --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px] text-indigo-600">info</span>
                        Posting Details
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[18px]">payments</span>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Salary Range</p>
                                <p class="text-sm font-semibold text-slate-800 mt-0.5">
                                    {{ $posting->salary_range ?? 'Not specified' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[18px]">work</span>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Employment Type</p>
                                <p class="text-sm font-semibold text-slate-800 mt-0.5">
                                    {{ ucwords(str_replace('_', ' ', $posting->employment_type)) }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[18px]">location_on</span>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Work Location</p>
                                <p class="text-sm font-semibold text-slate-800 mt-0.5">
                                    {{ $posting->location ?? 'Remote' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[18px]">event_busy</span>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Closing Date</p>
                                <p class="text-sm font-semibold text-slate-800 mt-0.5">
                                    {{ $posting->closing_date ? $posting->closing_date->format('F d, Y') : 'Open Ended' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Date Created</p>
                                <p class="text-sm font-semibold text-slate-800 mt-0.5">
                                    {{ $posting->created_at->format('F d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Public Share Card --}}
                    @if($posting->status === 'open')
                        <div class="mt-6 pt-5 border-t border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Public Careers Page Link</p>
                            <div class="flex items-center gap-1.5 p-2 rounded-xl bg-slate-50 border border-slate-200">
                                <input type="text" readonly value="{{ url('/careers/' . $posting->share_key) }}" 
                                       class="bg-transparent border-none text-xs text-slate-600 font-mono flex-1 focus:ring-0 p-0 select-all" />
                                <button type="button" 
                                    onclick="copyShareLink(this, '{{ url('/careers/' . $posting->share_key) }}')"
                                    class="px-2.5 py-1 text-xs font-semibold text-indigo-700 bg-white hover:bg-indigo-50 border border-slate-200 rounded-lg shadow-2xs transition-colors shrink-0">
                                    Copy
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Job Description Card --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs space-y-5">
                    <div>
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest border-b border-slate-100 pb-3 mb-3 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-indigo-600">description</span>
                            Job Description
                        </h3>
                        <div class="text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                            {{ $posting->description }}
                        </div>
                    </div>
                    
                    @if($posting->requirements)
                        <div class="pt-4 border-t border-slate-100">
                            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest border-b border-slate-100 pb-3 mb-3 flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-indigo-600">checklist</span>
                                Requirements
                            </h3>
                            <div class="text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                                {{ $posting->requirements }}
                            </div>
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
                const label = btn.querySelector('.btn-label') || btn;
                const originalText = label.innerText;
                label.innerText = 'Copied!';
                btn.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
                
                setTimeout(() => {
                    label.innerText = originalText;
                    btn.classList.remove('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
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
