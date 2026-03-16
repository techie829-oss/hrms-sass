<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">Candidates Pipeline</h2>
                <p class="text-xs font-medium mt-1 opacity-70">Track and manage all job applicants.</p>
            </div>
        </div>
    </x-slot>

    {{-- Filters --}}
    <form method="GET" action="{{ route('recruitment.applications.index') }}" class="flex flex-wrap gap-3 mb-6">
        <select name="posting_id" class="select select-bordered select-sm" onchange="this.form.submit()">
            <option value="">All Job Postings</option>
            @foreach($postings as $p)
                <option value="{{ $p->id }}" @selected(request('posting_id') == $p->id)>{{ $p->title }}</option>
            @endforeach
        </select>
        <select name="status" class="select select-bordered select-sm" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            @foreach(['new','reviewing','shortlisted','interview','offered','hired','rejected'] as $s)
                <option value="{{ $s }}" @selected(request('status') == $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        @if(request()->hasAny(['posting_id','status']))
            <a href="{{ route('recruitment.applications.index') }}" class="btn btn-ghost btn-sm">Clear</a>
        @endif
    </form>

    @php
        $stages = [
            'new'         => ['label' => 'New',          'badge' => 'badge-ghost'],
            'reviewing'   => ['label' => 'Reviewing',     'badge' => 'badge-info'],
            'shortlisted' => ['label' => 'Shortlisted',   'badge' => 'badge-warning'],
            'interview'   => ['label' => 'Interview',     'badge' => 'badge-secondary'],
            'offered'     => ['label' => 'Offered',       'badge' => 'badge-accent'],
            'hired'       => ['label' => 'Hired',         'badge' => 'badge-success'],
            'rejected'    => ['label' => 'Rejected',      'badge' => 'badge-error'],
        ];
    @endphp

    <div class="card bg-base-100 shadow-sm border border-base-200 overflow-hidden">
        <div class="p-5 border-b border-base-200 bg-base-200/30 flex justify-between items-center">
            <h3 class="font-bold text-sm">Applications</h3>
            <span class="text-[10px] font-bold opacity-50 uppercase tracking-wider">{{ $applications->total() }} Total</span>
        </div>

        <table class="table w-full">
            <thead>
                <tr class="bg-base-200/50 text-[10px] font-bold uppercase tracking-wider">
                    <th class="px-6 py-4">Candidate</th>
                    <th>Applied For</th>
                    <th>Applied</th>
                    <th>Status</th>
                    <th class="text-right px-6">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                    <tr class="border-b border-base-200 hover:bg-base-200/30 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="avatar placeholder">
                                    <div class="bg-secondary/10 text-secondary rounded-xl w-9 h-9 font-bold text-xs">
                                        {{ strtoupper(substr($app->first_name,0,1).substr($app->last_name,0,1)) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold text-sm">{{ $app->full_name }}</div>
                                    <div class="text-[11px] opacity-60">{{ $app->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-sm">{{ $app->jobPosting->title ?? '—' }}</td>
                        <td class="text-xs opacity-70">{{ $app->applied_at ? $app->applied_at->diffForHumans() : $app->created_at->diffForHumans() }}</td>
                        <td>
                            <span class="badge {{ $stages[$app->status]['badge'] ?? 'badge-ghost' }} badge-sm font-bold uppercase text-[10px]">
                                {{ $stages[$app->status]['label'] ?? $app->status }}
                            </span>
                        </td>
                        <td class="text-right px-6">
                            <a href="{{ route('recruitment.applications.show', $app->id) }}" class="btn btn-ghost btn-xs btn-square text-primary hover:bg-primary/10" title="View">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <div class="flex flex-col items-center gap-3 opacity-40">
                                <span class="material-symbols-outlined text-5xl">person_search</span>
                                <p class="font-bold text-sm">No applications yet.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($applications->hasPages())
            <div class="p-6 border-t border-base-200">{{ $applications->links() }}</div>
        @endif
    </div>
</x-app-layout>
