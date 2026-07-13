<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-on-surface tracking-tight">Clients</h2>
                <p class="text-xs font-medium mt-0.5 text-on-surface-variant">Manage external partners and organizations.</p>
            </div>
            <div class="flex gap-2">
                <button onclick="add_client_modal.showModal()" class="btn btn-primary btn-sm rounded-xl px-5 shadow-sm shadow-primary/20">
                    <span class="material-symbols-outlined text-base">person_add</span>
                    Add Client
                </button>
            </div>
        </div>
    </x-slot>

    <div class="table-crm">
        {{-- Desktop Table View --}}
        <div class="hidden lg:block overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr class="bg-base-200/50">
                        <th class="text-xs uppercase font-bold py-4">Client</th>
                        <th class="text-xs uppercase font-bold py-4">Contact</th>
                        <th class="text-xs uppercase font-bold py-4">Projects</th>
                        <th class="text-xs uppercase font-bold py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                        @php
                            $colors = ['bg-blue-600', 'bg-indigo-600', 'bg-violet-600', 'bg-purple-600', 'bg-teal-600', 'bg-emerald-600', 'bg-cyan-600', 'bg-sky-600'];
                            $colorClass = $colors[$client->id % count($colors)];
                        @endphp
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl {{ $colorClass }} text-white font-bold text-sm flex items-center justify-center shrink-0 shadow-sm">
                                        {{ strtoupper(substr($client->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold">{{ $client->name }}</div>
                                        <div class="text-xs opacity-50">{{ $client->company }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="flex flex-wrap gap-1 max-w-[200px]">
                                    @forelse($client->contacts as $contact)
                                        <div class="badge badge-ghost badge-sm font-medium">{{ $contact->name }}</div>
                                    @empty
                                        <span class="text-xs opacity-40 italic">No contacts</span>
                                    @endforelse
                                </div>
                                @if($client->contacts->count() > 0)
                                    <div class="text-[10px] opacity-40 mt-1 uppercase font-bold">{{ $client->contacts->count() }} Linked Contact(s)</div>
                                @endif
                            </td>
                            <td>
                                <div class="badge badge-outline gap-2 font-bold py-3">
                                    {{ $client->projects_count }} Projects
                                </div>
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end gap-2">
                                    <button class="btn btn-sm btn-square btn-ghost">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                    </button>
                                    <button class="btn btn-sm btn-square btn-ghost text-error">
                                        <span class="material-symbols-outlined text-base">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-base-content/40 italic">
                                No clients registered yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card Stack View --}}
        <div class="lg:hidden p-4 space-y-3 bg-slate-50/50">
            @forelse($clients as $client)
                @php
                    $colors = ['bg-blue-600', 'bg-indigo-600', 'bg-violet-600', 'bg-purple-600', 'bg-teal-600', 'bg-emerald-600', 'bg-cyan-600', 'bg-sky-600'];
                    $colorClass = $colors[$client->id % count($colors)];
                @endphp
                <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl {{ $colorClass }} text-white font-bold text-sm flex items-center justify-center shrink-0 shadow-sm">
                                {{ strtoupper(substr($client->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-bold text-sm text-slate-800">{{ $client->name }}</div>
                                <div class="text-xs text-slate-500">{{ $client->company }}</div>
                            </div>
                        </div>
                        <span class="badge badge-outline gap-1 font-bold text-[10px] shrink-0">
                            {{ $client->projects_count }} Projects
                        </span>
                    </div>

                    <div class="border-t border-slate-100 pt-2.5">
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Contacts</span>
                        <div class="flex flex-wrap gap-1">
                            @forelse($client->contacts as $contact)
                                <span class="badge badge-ghost badge-sm text-xs font-medium">{{ $contact->name }}</span>
                            @empty
                                <span class="text-xs text-slate-400 italic">No contacts</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 border-t border-slate-100 pt-2.5">
                        <button class="btn btn-xs btn-ghost text-slate-600 font-semibold flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">edit</span> Edit
                        </button>
                        <button class="btn btn-xs btn-ghost text-error font-semibold flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">delete</span> Delete
                        </button>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center bg-white border border-slate-200 rounded-xl">
                    <span class="material-symbols-outlined text-4xl text-slate-400 mb-2">domain</span>
                    <p class="font-bold text-xs text-slate-500">No clients registered yet.</p>
                </div>
            @endforelse
        </div>
    </div>

    </div>

    <!-- Add Client Modal -->
    <dialog id="add_client_modal" class="modal">
      <div class="modal-box p-0 overflow-hidden">
        <div class="p-6 bg-base-200 border-b border-base-300 flex justify-between items-center">
            <h3 class="font-bold text-lg text-primary">Add New Client</h3>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
        </div>
        <div class="p-6">
            <form action="{{ route('operations.clients.store') }}" method="POST">
                @csrf
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text font-bold">Full Name</span></label>
                    <input type="text" name="name" class="input input-bordered w-full" required placeholder="e.g. John Doe">
                </div>
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text font-bold">Company Name</span></label>
                    <input type="text" name="company" class="input input-bordered w-full" placeholder="e.g. Acme Corp">
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Email</span></label>
                        <input type="email" name="email" class="input input-bordered w-full" placeholder="client@example.com">
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Phone</span></label>
                        <input type="text" name="phone" class="input input-bordered w-full" placeholder="+1 234 ...">
                    </div>
                </div>
                <div class="form-control mb-6">
                    <label class="label"><span class="label-text font-bold">Address & Notes</span></label>
                    <x-editor name="address" placeholder="Office address and client notes..." />
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="btn btn-primary btn-block">Register Client</button>
                </div>
            </form>
        </div>
      </div>
    </dialog>
</x-app-layout>
