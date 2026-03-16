<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('recruitment.applications.index') }}" class="btn btn-ghost btn-sm btn-square">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div class="flex-1">
                <div class="flex items-center gap-3 flex-wrap">
                    <h2 class="text-xl font-bold">{{ $application->full_name }}</h2>
                    @php
                        $stages = [
                            'new'         => ['label' => 'New',         'badge' => 'badge-ghost'],
                            'reviewing'   => ['label' => 'Reviewing',    'badge' => 'badge-info'],
                            'shortlisted' => ['label' => 'Shortlisted',  'badge' => 'badge-warning'],
                            'interview'   => ['label' => 'Interview',    'badge' => 'badge-secondary'],
                            'offered'     => ['label' => 'Offered',      'badge' => 'badge-accent'],
                            'hired'       => ['label' => 'Hired',        'badge' => 'badge-success'],
                            'rejected'    => ['label' => 'Rejected',     'badge' => 'badge-error'],
                        ];
                        $stage = $stages[$application->status] ?? ['label' => ucfirst($application->status), 'badge' => 'badge-ghost'];
                    @endphp
                    <span class="badge {{ $stage['badge'] }} font-bold uppercase text-[10px] py-3">{{ $stage['label'] }}</span>
                </div>
                <p class="text-sm opacity-70 mt-0.5">Applying for: <strong>{{ $application->jobPosting->title ?? '—' }}</strong></p>
            </div>

            {{-- Stage Move Dropdown --}}
            <div class="dropdown dropdown-end ml-auto">
                <div tabindex="0" role="button" class="btn btn-primary btn-sm gap-2">
                    <span class="material-symbols-outlined text-base">swap_horiz</span> Move Stage
                </div>
                <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box shadow-xl w-52 mt-2 p-1 border border-base-200 z-50">
                    @foreach($stages as $key => $cfg)
                        @if($key !== $application->status)
                        <li>
                            <form action="{{ route('recruitment.applications.status', $application->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="{{ $key }}">
                                <button type="submit" class="w-full text-left text-sm flex items-center gap-2">
                                    <span class="badge {{ $cfg['badge'] }} badge-xs"></span>
                                    {{ $cfg['label'] }}
                                </button>
                            </form>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="alert alert-success mb-6 text-sm font-semibold">
            <span class="material-symbols-outlined">check_circle</span> {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Interviews & Schedule --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Interview Timeline --}}
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body p-0">
                    <div class="p-5 border-b border-base-200 bg-base-200/30 flex justify-between items-center">
                        <h3 class="font-bold text-sm flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-base">event</span> Interviews
                        </h3>
                        <button onclick="schedule_modal.showModal()" class="btn btn-primary btn-xs gap-1">
                            <span class="material-symbols-outlined text-sm">add</span> Schedule
                        </button>
                    </div>

                    @forelse($application->interviews as $interview)
                        <div class="p-5 border-b border-base-200 last:border-b-0 flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                                {{ $interview->status === 'completed' ? 'bg-success/10 text-success' :
                                   ($interview->status === 'cancelled' ? 'bg-error/10 text-error' : 'bg-primary/10 text-primary') }}">
                                <span class="material-symbols-outlined text-base">
                                    {{ $interview->type === 'phone' ? 'call' : ($interview->type === 'video' ? 'videocam' : ($interview->type === 'technical' ? 'code' : 'groups')) }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2 flex-wrap">
                                    <div>
                                        <p class="font-bold text-sm capitalize">{{ str_replace('_', ' ', $interview->type) }} Interview</p>
                                        <p class="text-xs opacity-60">{{ $interview->interview_date->format('M d, Y • H:i') }}
                                            @if($interview->location) · {{ $interview->location }} @endif
                                            @if($interview->interviewer) · with {{ $interview->interviewer->first_name }} @endif
                                        </p>
                                    </div>
                                    <span class="badge badge-sm font-bold uppercase text-[9px]
                                        {{ $interview->status === 'completed' ? 'badge-success' :
                                           ($interview->status === 'cancelled' ? 'badge-error' :
                                           ($interview->status === 'no_show' ? 'badge-warning' : 'badge-ghost')) }}">
                                        {{ $interview->status }}
                                    </span>
                                </div>
                                @if($interview->feedback)
                                    <p class="text-xs mt-2 bg-base-200 rounded-lg p-3 leading-relaxed">{{ $interview->feedback }}</p>
                                @endif
                                @if($interview->status === 'scheduled')
                                    <form action="{{ route('recruitment.interviews.update', $interview->id) }}" method="POST" class="mt-3 flex flex-wrap gap-2 items-end">
                                        @csrf
                                        <select name="status" class="select select-bordered select-xs">
                                            <option value="completed">Mark Completed</option>
                                            <option value="cancelled">Mark Cancelled</option>
                                            <option value="no_show">No Show</option>
                                        </select>
                                        <textarea name="feedback" class="textarea textarea-bordered textarea-xs w-full" rows="2" placeholder="Feedback (optional)"></textarea>
                                        <button type="submit" class="btn btn-xs btn-outline">Update</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center">
                            <span class="material-symbols-outlined text-4xl opacity-30 mb-3">calendar_month</span>
                            <p class="text-sm opacity-50 font-semibold">No interviews scheduled yet.</p>
                            <button onclick="schedule_modal.showModal()" class="btn btn-ghost btn-sm mt-3">Schedule First Interview</button>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Cover Letter --}}
            @if($application->cover_letter)
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="text-xs font-bold text-primary uppercase tracking-widest mb-3">Cover Letter</h3>
                    <p class="text-sm leading-relaxed whitespace-pre-line">{{ $application->cover_letter }}</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Right Sidebar: Candidate Info --}}
        <div class="space-y-6">
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="avatar placeholder">
                            <div class="bg-secondary/10 text-secondary rounded-xl w-14 h-14 font-bold text-lg">
                                {{ strtoupper(substr($application->first_name,0,1).substr($application->last_name,0,1)) }}
                            </div>
                        </div>
                        <div>
                            <div class="font-bold text-base">{{ $application->full_name }}</div>
                            <div class="text-xs opacity-60">Candidate</div>
                        </div>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-[10px] font-bold opacity-60 uppercase tracking-wider mb-0.5">Email</p>
                            <a href="mailto:{{ $application->email }}" class="text-primary hover:underline text-sm">{{ $application->email }}</a>
                        </div>
                        @if($application->phone)
                        <div>
                            <p class="text-[10px] font-bold opacity-60 uppercase tracking-wider mb-0.5">Phone</p>
                            <p>{{ $application->phone }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-[10px] font-bold opacity-60 uppercase tracking-wider mb-0.5">Applied</p>
                            <p>{{ ($application->applied_at ?? $application->created_at)->format('M d, Y') }}</p>
                        </div>
                        @if($application->resume_path)
                        <div>
                            <p class="text-[10px] font-bold opacity-60 uppercase tracking-wider mb-0.5">Resume</p>
                            <a href="{{ Storage::url($application->resume_path) }}" target="_blank" class="btn btn-outline btn-xs btn-primary w-full mt-1">
                                <span class="material-symbols-outlined text-sm">download</span> Download Resume
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="text-xs font-bold text-primary uppercase tracking-widest border-b border-base-200 pb-3 mb-4">Job Posting</h3>
                    <p class="font-bold text-sm">{{ $application->jobPosting->title }}</p>
                    <p class="text-xs opacity-60 mt-1">{{ $application->jobPosting->location ?? 'Remote' }} · {{ str_replace('_', ' ', $application->jobPosting->employment_type) }}</p>
                    <a href="{{ route('recruitment.job_postings.show', $application->jobPosting->id) }}" class="btn btn-ghost btn-xs w-full mt-3">View Posting</a>
                </div>
            </div>

            {{-- Danger Zone --}}
            @if($application->status !== 'rejected' && $application->status !== 'hired')
            <div class="card bg-base-100 shadow-sm border border-error/20">
                <div class="card-body">
                    <h3 class="text-xs font-bold text-error uppercase tracking-widest mb-3">Actions</h3>
                    <form action="{{ route('recruitment.applications.status', $application->id) }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn btn-error btn-outline btn-sm w-full" onclick="return confirm('Reject this candidate?')">
                            <span class="material-symbols-outlined text-base">person_off</span> Reject
                        </button>
                    </form>
                    <form action="{{ route('recruitment.applications.status', $application->id) }}" method="POST" class="flex gap-2 mt-2">
                        @csrf
                        <input type="hidden" name="status" value="hired">
                        <button type="submit" class="btn btn-success btn-sm w-full" onclick="return confirm('Mark this candidate as Hired?')">
                            <span class="material-symbols-outlined text-base">how_to_reg</span> Mark as Hired
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Schedule Interview Modal --}}
    <dialog id="schedule_modal" class="modal">
        <div class="modal-box max-w-lg">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
            </form>
            <h3 class="font-bold text-xl mb-1">Schedule Interview</h3>
            <p class="text-xs mb-6 font-medium opacity-70">Book a new interview for {{ $application->full_name }}.</p>

            <form action="{{ route('recruitment.applications.interview.schedule', $application->id) }}" method="POST" class="space-y-5">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label font-bold text-sm">Interview Type</label>
                        <select name="type" class="select select-bordered w-full" required>
                            <option value="phone">📞 Phone Screen</option>
                            <option value="video">📹 Video Call</option>
                            <option value="technical">💻 Technical</option>
                            <option value="in_person">🏢 In Person</option>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label font-bold text-sm">Date & Time</label>
                        <input type="datetime-local" name="interview_date" class="input input-bordered w-full" required min="{{ now()->addHour()->format('Y-m-d\TH:i') }}" />
                    </div>
                </div>
                <div class="form-control">
                    <label class="label font-bold text-sm">Location / Link</label>
                    <input type="text" name="location" class="input input-bordered w-full" placeholder="e.g. Google Meet link or Office Room" />
                </div>
                <div class="form-control">
                    <label class="label font-bold text-sm">Interviewer</label>
                    <select name="interviewer_id" class="select select-bordered w-full">
                        <option value="">— Not Assigned —</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-action">
                    <button type="submit" class="btn btn-primary w-full">Schedule Interview</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>
</x-app-layout>
