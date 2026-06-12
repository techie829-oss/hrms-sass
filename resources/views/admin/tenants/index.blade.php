<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">
                    Tenant Management
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Oversee all organizations and workspaces in the system.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.tenants.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-sm text-sm sm:w-auto w-full">
                    <span class="material-symbols-outlined text-[20px]">add_business</span>
                    <span>Create Tenant</span>
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Header Tabs -->
    <div class="flex gap-2 mb-6 overflow-x-auto pb-2 sm:pb-0 scrollbar-hide">
        <a href="{{ route('admin.tenants.index') }}" class="px-4 py-2 font-semibold text-sm rounded-xl transition-all whitespace-nowrap {{ request('filter') !== 'archived' ? 'bg-indigo-50 text-indigo-700 border border-indigo-100 shadow-sm' : 'text-slate-600 hover:bg-slate-50 border border-transparent hover:text-slate-900' }}">
            Active Tenants
        </a>
        <a href="{{ route('admin.tenants.index', ['filter' => 'archived']) }}" class="px-4 py-2 font-semibold text-sm rounded-xl transition-all whitespace-nowrap {{ request('filter') === 'archived' ? 'bg-slate-800 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 border border-transparent hover:text-slate-900' }}">
            Archived Tenants
        </a>
    </div>

    <!-- Main Card Container -->
    <div class="card-crm">
        <div class="overflow-x-auto">
            <table class="table-crm">
                <thead>
                    <tr>
                        <th class="text-left">Company</th>
                        <th class="text-left hidden sm:table-cell">Subdomain</th>
                        <th class="text-left hidden md:table-cell">Mode</th>
                        <th class="text-left">Plan</th>
                        <th class="text-left">Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenants as $tenant)
                        <tr class="hover:bg-surface-container-low transition-colors group">
                            <td class="whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold text-sm border border-primary/20 shadow-sm">
                                        {{ strtoupper(substr($tenant->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-on-surface group-hover:text-primary transition-colors">{{ $tenant->name }}</div>
                                        <div class="text-xs font-medium text-on-surface-variant mt-0.5">{{ $tenant->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap hidden sm:table-cell">
                                <div class="inline-flex items-center px-3 py-1 rounded-md bg-surface-container-low text-on-surface font-mono text-xs font-bold border border-outline-variant/50">
                                    {{ $tenant->slug }}.{{ parse_url(config('app.url'), PHP_URL_HOST) ?? config('app.url') }}
                                </div>
                            </td>
                            <td class="whitespace-nowrap hidden md:table-cell">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] uppercase tracking-wider font-bold border {{ $tenant->mode === 'dedicated' ? 'bg-secondary/10 text-secondary border-secondary/20' : 'bg-surface-container text-on-surface-variant border-outline-variant/50' }}">
                                    {{ ucfirst($tenant->mode) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] uppercase tracking-wider font-bold bg-info/10 text-info border border-info/20">
                                    {{ ucfirst($tenant->plan_id) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] uppercase tracking-wider font-bold border {{ $tenant->status === 'active' ? 'bg-success/10 text-success border-success/20' : 'bg-error/10 text-error border-error/20' }}">
                                    {{ ucfirst($tenant->status) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2 transition-opacity">
                                    @if(request('filter') === 'archived')
                                        <form action="{{ route('admin.tenants.restore', $tenant->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-1.5 rounded-lg text-success hover:bg-success/10 transition-colors" title="Restore Tenant">
                                                <span class="material-symbols-outlined text-[20px]">settings_backup_restore</span>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.tenants.force-delete', $tenant->id) }}" method="POST" class="inline" onsubmit="return confirm('CRITICAL WARNING: This action is irreversible!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-error hover:bg-error/10 transition-colors" title="Permanently Delete Tenant">
                                                <span class="material-symbols-outlined text-[20px]">delete_forever</span>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="p-1.5 rounded-lg text-on-surface-variant hover:text-primary hover:bg-primary/10 transition-colors" title="Manage Tenant">
                                            <span class="material-symbols-outlined text-[20px]">settings</span>
                                        </a>
                                        <form action="{{ route('admin.tenants.toggle-status', $tenant->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="p-1.5 rounded-lg text-on-surface-variant hover:text-warning hover:bg-warning/10 transition-colors" title="Toggle Status">
                                                <span class="material-symbols-outlined text-[20px]">block</span>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.tenants.destroy', $tenant->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to archive this tenant?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors" title="Archive Tenant">
                                                <span class="material-symbols-outlined text-[20px]">archive</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center border-b-0">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-surface-container border border-outline-variant mb-4">
                                    <span class="material-symbols-outlined text-3xl text-on-surface-variant/50">domain_disabled</span>
                                </div>
                                <h3 class="text-sm font-bold text-on-surface">No tenants found</h3>
                                <p class="mt-1 text-sm text-on-surface-variant">Get started by creating a new tenant.</p>
                                <div class="mt-6">
                                    <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary rounded-xl">
                                        <span class="material-symbols-outlined text-[18px] mr-2">add</span>
                                        Create First Tenant
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tenants->hasPages())
            <div class="px-6 py-4 border-t border-outline-variant/50 bg-surface-container-lowest">
                {{ $tenants->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
