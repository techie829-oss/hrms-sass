@php
use App\Core\Constants\PermissionConstants;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-on-surface tracking-tight">Recruitment Pipeline</h2>
                <p class="text-sm opacity-70 mt-1">Manage job postings and track candidates.</p>
            </div>
            <div class="flex gap-3">
                @can(PermissionConstants::MANAGE_RECRUITMENT)
                    <a href="{{ route('recruitment.job_postings.create') }}" class="btn btn-primary">
                        <span class="material-symbols-outlined text-base">work</span> New Job Posting
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Overview Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Active Jobs</p>
                        <h3 class="text-2xl font-black text-gray-900 mt-1">{{ $postings->where('status', 'open')->count() }}</h3>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">work</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Applicants</p>
                        <h3 class="text-2xl font-black text-gray-900 mt-1">0</h3>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">group</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Interviews Scheduled</p>
                        <h3 class="text-2xl font-black text-gray-900 mt-1">0</h3>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">event</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Hired This Month</p>
                        <h3 class="text-2xl font-black text-gray-900 mt-1">0</h3>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">check_circle</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job Postings List -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
            <div class="p-0">
                <div class="p-6 border-b border-base-200 flex justify-between items-center bg-base-200/50">
                    <h3 class="text-sm font-bold uppercase tracking-widest">Active Job Postings</h3>
                </div>
                {{-- Desktop Table View --}}
                <div class="hidden lg:block overflow-x-auto">
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
                                            @if($posting->status === 'open')
                                                <button onclick="let t=document.createElement('textarea');t.value='{{ url("/careers/" . $posting->share_key) }}';document.body.appendChild(t);t.select();document.execCommand('copy');document.body.removeChild(t); alert('Public link copied to clipboard!');" class="btn btn-ghost btn-sm btn-square rounded-xl text-info hover:bg-info/10 transition-colors tooltip tooltip-left" data-tip="Copy Public Link">
                                                    <span class="material-symbols-outlined text-base">link</span>
                                                </button>
                                            @endif
                                            @can('view', $posting)
                                                <a href="{{ route('recruitment.job_postings.show', $posting->id) }}" class="btn btn-ghost btn-sm btn-square rounded-xl text-primary hover:bg-primary/10 transition-colors tooltip tooltip-left" data-tip="View Details">
                                                    <span class="material-symbols-outlined text-base">visibility</span>
                                                </a>
                                            @endcan
                                            @can('update', $posting)
                                                <a href="{{ route('recruitment.job_postings.edit', $posting->id) }}" class="btn btn-ghost btn-sm btn-square rounded-xl text-secondary hover:bg-secondary/10 transition-colors tooltip tooltip-left" data-tip="Edit Posting">
                                                    <span class="material-symbols-outlined text-base">edit</span>
                                                </a>
                                            @endcan
                                            @can('delete', $posting)
                                                <form action="{{ route('recruitment.job_postings.destroy', $posting->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-ghost btn-sm btn-square rounded-xl text-error hover:bg-error/10 transition-colors tooltip tooltip-left" data-tip="Delete" onclick="return confirm('Are you sure you want to delete this job posting?')">
                                                        <span class="material-symbols-outlined text-base">delete</span>
                                                    </button>
                                                </form>
                                            @endcan
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

                {{-- Mobile Card Stack View --}}
                <div class="lg:hidden p-4 space-y-3 bg-slate-50/50">
                    @forelse($postings as $posting)
                        @php
                            $statusConfig = [
                                'open' => ['class' => 'badge-success', 'icon' => 'public'],
                                'draft' => ['class' => 'badge-ghost', 'icon' => 'edit_note'],
                                'closed' => ['class' => 'badge-neutral', 'icon' => 'lock'],
                            ];
                            $config = $statusConfig[$posting->status] ?? $statusConfig['draft'];
                        @endphp
                        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm space-y-3">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <div class="font-bold text-sm text-slate-800">{{ $posting->title }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5">{{ $posting->location ?? 'Remote' }}</div>
                                </div>
                                <span class="badge {{ $config['class'] }} gap-1 font-semibold text-[10px] uppercase shrink-0">
                                    {{ ucfirst($posting->status) }}
                                </span>
                            </div>

                            <div class="flex flex-wrap items-center gap-2 text-xs">
                                <span class="badge badge-outline badge-sm">{{ str_replace('_', ' ', $posting->employment_type) }}</span>
                                <span class="text-slate-400">|</span>
                                <span class="text-xs font-semibold text-primary">0 Candidates</span>
                            </div>

                            <div class="flex items-center justify-between border-t border-slate-100 pt-3">
                                <span class="text-[10px] text-slate-400 font-medium">
                                    Closes: {{ $posting->closing_date ? $posting->closing_date->format('M d, Y') : 'Open Ended' }}
                                </span>
                                <div class="flex items-center gap-1">
                                    @can('view', $posting)
                                        <a href="{{ route('recruitment.job_postings.show', $posting->id) }}" class="btn btn-ghost btn-xs text-primary font-bold">
                                            View
                                        </a>
                                    @endcan
                                    @can('update', $posting)
                                        <a href="{{ route('recruitment.job_postings.edit', $posting->id) }}" class="btn btn-ghost btn-xs text-secondary font-bold">
                                            Edit
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center bg-white border border-slate-200 rounded-xl">
                            <span class="material-symbols-outlined text-4xl text-slate-400 mb-2">work_off</span>
                            <p class="font-bold text-xs text-slate-500">No Job Postings Yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
            </div>
        </div>
    </div>
</x-app-layout>
