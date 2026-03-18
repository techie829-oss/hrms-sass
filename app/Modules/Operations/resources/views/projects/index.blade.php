<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">Projects</h2>
                <p class="text-xs font-medium mt-1 opacity-70">Manage your tenant projects and milestones.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('operations.projects.create') }}" class="btn btn-primary btn-sm">
                    <span class="material-symbols-outlined text-base">add</span>
                    New Project
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($projects as $project)
                <div class="card bg-base-100 shadow-xl border border-base-200">
                    <div class="card-body">
                        <div class="flex justify-between items-start">
                            <h2 class="card-title text-lg">{{ $project->name }}</h2>
                            <div class="badge {{ 
                                $project->status === 'completed' ? 'badge-success' : 
                                ($project->status === 'in_progress' ? 'badge-info' : 'badge-ghost') 
                            }} badge-sm uppercase font-bold px-2 py-3 rounded-md">
                                {{ str_replace('_', ' ', $project->status) }}
                            </div>
                        </div>
                        
                        <p class="text-sm text-base-content/70 line-clamp-2 mt-2">
                            {{ $project->description ?: 'No description provided.' }}
                        </p>

                        <div class="mt-4 space-y-2">
                            <div class="flex items-center gap-2 text-sm">
                                <span class="material-symbols-outlined text-base">person</span>
                                <span>{{ $project->client->name ?? 'Internal Project' }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                <span class="material-symbols-outlined text-base">calendar_today</span>
                                <span>{{ $project->deadline ? $project->deadline->format('M d, Y') : 'No Deadline' }}</span>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="flex justify-between text-xs mb-1">
                                <span>Tasks Progress</span>
                                <span>{{ $project->tasks->where('status', 'done')->count() }}/{{ $project->tasks->count() }}</span>
                            </div>
                            <progress class="progress progress-primary w-full" value="{{ $project->tasks->count() > 0 ? ($project->tasks->where('status', 'done')->count() / $project->tasks->count()) * 100 : 0 }}" max="100"></progress>
                        </div>

                        <div class="card-actions justify-end mt-4">
                            <a href="{{ route('operations.projects.show', $project) }}" class="btn btn-sm btn-ghost">View Details</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-base-200/50 rounded-xl border-2 border-dashed border-base-300">
                    <span class="material-symbols-outlined text-6xl text-base-content/20 mb-4">account_tree</span>
                    <p class="text-lg font-medium">No projects found</p>
                    <p class="text-base-content/60 mb-6">Start by creating your first project.</p>
                    <a href="{{ route('operations.projects.create') }}" class="btn btn-primary">Create Project</a>
                </div>
            @endforelse
        </div>

        </div>

        @if($projects->hasPages())
            <div class="mt-6">
                {{ $projects->links() }}
            </div>
        @endif
</x-app-layout>
