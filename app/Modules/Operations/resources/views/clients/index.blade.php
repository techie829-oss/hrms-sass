<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">Clients</h2>
                <p class="text-xs font-medium mt-1 opacity-70">Manage external partners and organizations.</p>
            </div>
            <div class="flex gap-2">
                <button onclick="add_client_modal.showModal()" class="btn btn-primary btn-sm">
                    <span class="material-symbols-outlined text-base">person_add</span>
                    Add Client
                </button>
            </div>
        </div>
    </x-slot>

    <div class="card bg-base-100 shadow-sm border border-base-200 overflow-hidden">
            <div class="overflow-x-auto">
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
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar placeholder">
                                            <div class="bg-neutral text-neutral-content rounded-lg w-10">
                                                <span class="text-sm font-bold">{{ substr($client->name, 0, 1) }}</span>
                                            </div>
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
                <div class="card-actions justify-end">
                    <button type="submit" class="btn btn-primary btn-block">Register Client</button>
                </div>
            </form>
        </div>
      </div>
    </dialog>
</x-app-layout>
