<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-on-surface tracking-tight">Leads</h2>
                <p class="text-xs font-medium mt-0.5 text-on-surface-variant">Manage your business opportunities and conversions.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('operations.leads.create') }}" class="btn btn-primary btn-sm rounded-xl px-5 shadow-sm shadow-primary/20">
                    <span class="material-symbols-outlined text-base">add</span>
                    New Lead
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($leads as $lead)
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm hover:border-indigo-300 transition-all">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">{{ $lead->name }}</h2>
                            <p class="text-xs font-medium opacity-60">{{ $lead->company_name ?? 'Individual' }}</p>
                        </div>
                        <div class="badge {{ 
                            $lead->status === 'converted' ? 'badge-success' : 
                            ($lead->status === 'lost' ? 'badge-error' : 
                            ($lead->status === 'qualified' ? 'badge-primary' : 'badge-ghost')) 
                        }} badge-sm uppercase font-bold px-2 py-3 rounded-md">
                            {{ str_replace('_', ' ', $lead->status) }}
                        </div>
                    </div>
                    
                    <div class="mt-4 space-y-2">
                        @if($lead->email)
                        <div class="flex items-center gap-2 text-sm">
                            <span class="material-symbols-outlined text-base opacity-70">mail</span>
                            <span class="truncate">{{ $lead->email }}</span>
                        </div>
                        @endif
                        @if($lead->phone)
                        <div class="flex items-center gap-2 text-sm">
                            <span class="material-symbols-outlined text-base opacity-70">call</span>
                            <span>{{ $lead->phone }}</span>
                        </div>
                        @endif
                        <div class="flex items-center gap-2 text-sm">
                            <span class="material-symbols-outlined text-base opacity-70">person_search</span>
                            <span>Assignee: {{ $lead->assignee->name ?? 'Unassigned' }}</span>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 pt-4 border-t border-slate-200">
                        <div class="flex gap-1">
                            <a href="{{ route('operations.leads.edit', $lead) }}" class="btn btn-sm btn-ghost btn-square">
                                <span class="material-symbols-outlined text-base">edit</span>
                            </a>
                            <a href="{{ route('operations.leads.show', $lead) }}" class="btn btn-sm btn-ghost">View</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center bg-base-200/50 rounded-xl border-2 border-dashed border-base-300">
                <span class="material-symbols-outlined text-6xl text-base-content/20 mb-4">person_add</span>
                <p class="text-lg font-medium">No leads found</p>
                <p class="text-base-content/60 mb-6">Start by capturing your first lead.</p>
                <a href="{{ route('operations.leads.create') }}" class="btn btn-primary">Create Lead</a>
            </div>
        @endforelse
    </div>

    @if($leads->hasPages())
        <div class="mt-6">
            {{ $leads->links() }}
        </div>
    @endif
</x-app-layout>
