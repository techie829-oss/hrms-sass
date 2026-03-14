<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface">Tenant Management</h2>
                <p class="text-sm text-on-surface-variant font-medium mt-1">Oversee all organizational sanctuaries in the system.</p>
            </div>
            <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary primary-gradient border-none rounded-xl font-bold text-xs uppercase tracking-widest px-6 shadow-lg">
                <span class="material-symbols-outlined text-lg">add_business</span> Create Tenant
            </a>
        </div>
    </x-slot>

    <div class="bg-surface-container-lowest rounded-[2.5rem] border border-outline-variant/15 premium-shadow overflow-hidden">
        <x-table :headers="['Company', 'Subdomain', 'Mode', 'Plan', 'Status', 'Actions']" :striped="false">
            @forelse($tenants as $tenant)
                <tr class="hover:bg-primary/5 transition-colors border-b border-outline-variant/5">
                    <td class="py-5 px-6">
                        <div class="flex items-center gap-4">
                            <div class="avatar placeholder">
                                <div class="bg-primary/10 text-primary rounded-2xl w-12 h-12 font-black text-sm premium-shadow">
                                    {{ strtoupper(substr($tenant->name, 0, 2)) }}
                                </div>
                            </div>
                            <div>
                                <div class="font-black text-on-surface text-base">{{ $tenant->name }}</div>
                                <div class="text-xs text-on-surface-variant font-medium opacity-70">{{ $tenant->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="font-mono text-xs font-bold text-primary">{{ $tenant->slug }}.{{ parse_url(config('app.url'), PHP_URL_HOST) ?? config('app.url') }}</div>
                    </td>
                    <td>
                        <span class="badge {{ $tenant->mode === 'dedicated' ? 'badge-secondary' : 'badge-ghost' }} font-black text-[9px] uppercase tracking-widest px-3 py-2">
                            {{ $tenant->mode }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-outline text-primary border-primary/30 font-black text-[9px] uppercase tracking-widest px-3 py-2">
                            {{ $tenant->plan_id }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $tenant->status === 'active' ? 'badge-success' : 'badge-error' }} text-white font-black text-[9px] uppercase tracking-widest px-3 py-2">
                            {{ $tenant->status }}
                        </span>
                    </td>
                    <td class="text-right px-6">
                        <div class="flex justify-end gap-2">
                            <form action="{{ route('admin.tenants.toggle-status', $tenant->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-ghost btn-sm btn-square rounded-xl hover:bg-warning/10 hover:text-warning" title="Toggle Status">
                                    <span class="material-symbols-outlined text-xl">block</span>
                                </button>
                            </form>
                            <form action="{{ route('admin.tenants.destroy', $tenant->id) }}" method="POST" class="inline" onsubmit="return confirm('WARNING: This will permanently delete the tenant and all their data!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-ghost btn-sm btn-square rounded-xl hover:bg-error/10 hover:text-error" title="Delete Tenant">
                                    <span class="material-symbols-outlined text-xl">delete_forever</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-20 text-center">
                        <div class="flex flex-col items-center gap-4 opacity-40">
                            <span class="material-symbols-outlined text-6xl">domain_disabled</span>
                            <p class="font-headline font-bold text-lg">No tenants provisioned yet.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if($tenants->hasPages())
            <div class="p-6 border-t border-outline-variant/10">
                {{ $tenants->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
