<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('operations.leads.index') }}" class="btn btn-ghost btn-sm btn-square">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-xl font-bold">{{ $lead->name }}</h2>
                    <p class="text-xs font-medium opacity-70">{{ $lead->company_name ?? 'Individual' }} • {{ ucfirst($lead->status) }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('operations.leads.edit', $lead) }}" class="btn btn-primary btn-sm">
                    <span class="material-symbols-outlined text-base">edit</span>
                    Edit Lead
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Lead Insights -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="font-bold text-lg mb-4">Lead Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs uppercase font-bold opacity-50">Email</p>
                            <p class="font-medium">{{ $lead->email ?: 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase font-bold opacity-50">Phone</p>
                            <p class="font-medium">{{ $lead->phone ?: 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase font-bold opacity-50">Source</p>
                            <p class="font-medium">{{ $lead->source ?: 'Direct' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase font-bold opacity-50">Assigned To</p>
                            <p class="font-medium text-primary">{{ $lead->assignee->name ?? 'Unassigned' }}</p>
                        </div>
                    </div>

                    <div class="mt-8 pt-8 border-t border-base-200">
                        <p class="text-xs uppercase font-bold opacity-50 mb-2">Description / Notes</p>
                        <div class="prose prose-sm max-w-none bg-base-200/50 p-4 rounded-xl">
                            {!! nl2br(e($lead->description)) ?: '<i>No notes provided.</i>' !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Linked Projects (if any converted) -->
            @if($lead->projects->count() > 0)
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="font-bold text-lg mb-4">Converted Projects</h3>
                    <div class="space-y-4">
                        @foreach($lead->projects as $project)
                            <div class="flex items-center justify-between p-4 bg-base-200/30 rounded-lg border border-base-200">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">account_tree</span>
                                    <div>
                                        <p class="font-bold">{{ $project->name }}</p>
                                        <p class="text-[10px] opacity-60">Status: {{ ucfirst($project->status) }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('operations.projects.show', $project) }}" class="btn btn-sm btn-ghost">View</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar / Actions -->
        <div class="lg:col-span-1 space-y-6">
            <div class="card bg-primary text-primary-content shadow-lg">
                <div class="card-body">
                    <h3 class="font-bold">Next Steps</h3>
                    <p class="text-sm opacity-80">Track conversion progress and follow up with the customer.</p>
                    <div class="mt-4 space-y-2">
                        @if($lead->status !== 'converted')
                        <button class="btn btn-white btn-sm btn-block text-primary">Convert to Project</button>
                        @endif
                        <button class="btn btn-ghost btn-sm btn-block border-primary-content/20">Send Email</button>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="font-bold text-sm mb-4">Timeline</h3>
                    <ul class="steps steps-vertical text-xs">
                        <li class="step step-primary">Lead Created ({{ $lead->created_at->format('M d') }})</li>
                        <li class="step {{ in_array($lead->status, ['contacted', 'qualified', 'converted']) ? 'step-primary' : '' }}">Initial Contact</li>
                        <li class="step {{ in_array($lead->status, ['qualified', 'converted']) ? 'step-primary' : '' }}">Qualified</li>
                        <li class="step {{ $lead->status === 'converted' ? 'step-primary' : '' }}">Converted</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
