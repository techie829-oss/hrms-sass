<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">Contacts</h2>
                <p class="text-xs font-medium mt-1 opacity-70">Manage people associated with your client accounts.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('operations.contacts.create') }}" class="btn btn-primary btn-sm">
                    <span class="material-symbols-outlined text-base">add</span>
                    New Contact
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($contacts as $contact)
            <div class="card bg-base-100 shadow-xl border border-base-200 hover:border-primary/30 transition-all">
                <div class="card-body">
                    <div class="flex items-center gap-4">
                        <div class="avatar placeholder">
                            <div class="bg-primary-focus text-primary-content rounded-full w-12">
                                <span class="text-lg font-bold uppercase">{{ substr($contact->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div>
                            <h2 class="card-title text-base">{{ $contact->name }}</h2>
                            <p class="text-xs font-medium opacity-60">{{ $contact->job_title ?? 'Contact Person' }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 space-y-2">
                        @if($contact->email)
                        <div class="flex items-center gap-2 text-sm">
                            <span class="material-symbols-outlined text-base opacity-70">mail</span>
                            <span class="truncate">{{ $contact->email }}</span>
                        </div>
                        @endif
                        @if($contact->phone)
                        <div class="flex items-center gap-2 text-sm">
                            <span class="material-symbols-outlined text-base opacity-70">call</span>
                            <span>{{ $contact->phone }}</span>
                        </div>
                        @endif
                        <div class="flex items-center gap-2 text-sm">
                            <span class="material-symbols-outlined text-base opacity-70">business</span>
                            <div class="flex flex-wrap gap-1">
                                @forelse($contact->clients as $client)
                                    <div class="badge badge-outline badge-sm">{{ $client->name }}</div>
                                @empty
                                    <span class="italic opacity-50 text-xs">No linked accounts</span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="card-actions justify-end mt-6 pt-4 border-t border-base-200">
                        <div class="flex gap-1">
                            <a href="{{ route('operations.contacts.edit', $contact) }}" class="btn btn-sm btn-ghost btn-square">
                                <span class="material-symbols-outlined text-base">edit</span>
                            </a>
                            <form action="{{ route('operations.contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Delete this contact?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-ghost btn-square text-error">
                                    <span class="material-symbols-outlined text-base">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center bg-base-200/50 rounded-xl border-2 border-dashed border-base-300">
                <span class="material-symbols-outlined text-6xl text-base-content/20 mb-4">contacts</span>
                <p class="text-lg font-medium">No contacts found</p>
                <p class="text-base-content/60 mb-6">Start by adding your client's contact persons.</p>
                <a href="{{ route('operations.contacts.create') }}" class="btn btn-primary">Create Contact</a>
            </div>
        @endforelse
    </div>

    @if($contacts->hasPages())
        <div class="mt-6">
            {{ $contacts->links() }}
        </div>
    @endif
</x-app-layout>
