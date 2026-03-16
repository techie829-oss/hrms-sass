<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">Recruitment Pipeline</h2>
                <p class="text-sm opacity-70 mt-1">Manage job postings and track candidates.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('recruitment.job_postings.create') }}" class="btn btn-primary">
                    <span class="material-symbols-outlined text-base">work</span> New Job Posting
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Overview Stats -->
        <div class="stats shadow w-full">
            <div class="stat">
                <div class="stat-figure text-primary">
                    <span class="material-symbols-outlined text-3xl">work</span>
                </div>
                <div class="stat-title">Active Jobs</div>
                <div class="stat-value text-primary">{{ $postings->where('status', 'open')->count() }}</div>
            </div>
            
            <div class="stat">
                <div class="stat-figure text-secondary">
                    <span class="material-symbols-outlined text-3xl">group</span>
                </div>
                <div class="stat-title">Total Applicants</div>
                <div class="stat-value text-secondary">0</div>
            </div>
            
            <div class="stat">
                <div class="stat-figure text-warning">
                    <span class="material-symbols-outlined text-3xl">event</span>
                </div>
                <div class="stat-title">Interviews Scheduled</div>
                <div class="stat-value text-warning">0</div>
            </div>
            
            <div class="stat">
                <div class="stat-figure text-success">
                    <span class="material-symbols-outlined text-3xl">check_circle</span>
                </div>
                <div class="stat-title">Hired This Month</div>
                <div class="stat-value text-success">0</div>
            </div>
        </div>

        <!-- Job Postings List -->
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body p-0">
                <div class="p-6 border-b border-base-200 flex justify-between items-center bg-base-200/50">
                    <h3 class="text-sm font-bold uppercase tracking-widest">Active Job Postings</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>Department/Location</th>
                                <th>Type</th>
                                <th>Applicants</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($postings as $posting)
                                <tr class="hover group">
                                    <td>
                                        <div class="font-bold group-hover:text-primary transition-colors">{{ $posting->title }}</div>
                                        <div class="text-xs opacity-70 mt-1">Closes: {{ $posting->closing_date ? $posting->closing_date->format('M d, Y') : 'Open Ended' }}</div>
                                    </td>
                                    <td>
                                        <div class="font-semibold">{{ $posting->location ?? 'Remote' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge badge-outline badge-sm">
                                            {{ str_replace('_', ' ', $posting->employment_type) }}
                                        </span>
                                    </td>
                                    <td class="font-bold text-primary">
                                        0 Candidates
                                    </td>
                                    <td>
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
                                    </td>
                                    <td class="text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('recruitment.job_postings.show', $posting->id) }}" class="btn btn-ghost btn-sm btn-square rounded-xl text-primary hover:bg-primary/10 transition-colors tooltip tooltip-left" data-tip="View Details">
                                                <span class="material-symbols-outlined text-base">visibility</span>
                                            </a>
                                            <a href="{{ route('recruitment.job_postings.edit', $posting->id) }}" class="btn btn-ghost btn-sm btn-square rounded-xl text-secondary hover:bg-secondary/10 transition-colors tooltip tooltip-left" data-tip="Edit Posting">
                                                <span class="material-symbols-outlined text-base">edit</span>
                                            </a>
                                            <form action="{{ route('recruitment.job_postings.destroy', $posting->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-ghost btn-sm btn-square rounded-xl text-error hover:bg-error/10 transition-colors tooltip tooltip-left" data-tip="Delete" onclick="return confirm('Are you sure you want to delete this job posting?')">
                                                    <span class="material-symbols-outlined text-base">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <span class="material-symbols-outlined text-5xl opacity-30 mb-4">work_off</span>
                                            <h3 class="text-lg font-bold">No Job Postings Yet</h3>
                                            <p class="text-sm opacity-70 mt-1 mb-6">Create a job posting to start recruiting talent.</p>
                                            <a href="{{ route('recruitment.job_postings.create') }}" class="btn btn-primary">
                                                Create First Posting
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
