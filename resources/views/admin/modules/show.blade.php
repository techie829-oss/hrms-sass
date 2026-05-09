<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.modules.index') }}" class="btn btn-ghost btn-circle btn-sm">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
            </a>
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface uppercase">{{ $module['name'] }} Details</h2>
                <p class="text-sm text-on-surface-variant font-medium mt-1">Detailed status, filesystem contents, and client activation logs.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-8 space-y-8">
        <!-- Module Information Card -->
        <div class="card bg-surface-container-lowest shadow-xl border border-outline-variant/15 rounded-[2.5rem] overflow-hidden">
            <div class="card-body p-8 md:p-10 space-y-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-outline-variant/10">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 rounded-3xl bg-primary/10 text-primary flex items-center justify-center font-black shadow-xl border border-primary/5">
                            <span class="material-symbols-outlined text-4xl">{{ $module['icon'] ?? 'widgets' }}</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black font-headline text-on-surface uppercase">{{ $module['name'] }}</h3>
                            <p class="text-xs font-semibold opacity-70 mt-1 uppercase tracking-wider">Module Identifier: {{ $slug }}</p>
                        </div>
                    </div>
                    <div>
                        <span class="badge {{ $module['free'] ? 'badge-success' : 'badge-primary' }} text-white font-black text-xs uppercase tracking-widest px-4 py-2 rounded-xl shadow-md">
                            {{ $module['free'] ? 'FREE MODULE' : 'PAID MODULE' }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <span class="text-[10px] font-black uppercase tracking-widest opacity-50">Filesystem Location</span>
                        <div class="bg-surface-container-low p-4 rounded-2xl border border-outline-variant/5">
                            <code class="font-mono text-xs break-all text-primary font-bold">{{ str_replace(base_path(), '', $module['path']) }}</code>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <span class="text-[10px] font-black uppercase tracking-widest opacity-50">Database Status</span>
                        <div class="bg-surface-container-low p-4 rounded-2xl border border-outline-variant/5 flex items-center gap-2">
                            @if($dbModule)
                                <span class="w-2.5 h-2.5 rounded-full bg-success"></span>
                                <span class="text-xs font-black uppercase tracking-wider text-success">Synced & Ready in System</span>
                            @else
                                <span class="w-2.5 h-2.5 rounded-full bg-error animate-pulse"></span>
                                <span class="text-xs font-black uppercase tracking-wider text-error">Sync Needed (No DB Record)</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Module Components & File Architecture Card -->
        <div class="card bg-surface-container-lowest shadow-xl border border-outline-variant/15 rounded-[2.5rem] overflow-hidden">
            <div class="card-body p-8 md:p-10 space-y-6">
                <div class="flex items-center gap-4 border-b border-outline-variant/10 pb-4">
                    <span class="material-symbols-outlined text-primary text-3xl">folder_open</span>
                    <div>
                        <h3 class="font-black font-headline text-lg uppercase tracking-wider text-on-surface">Module Architecture & Files</h3>
                        <p class="text-[10px] opacity-70 font-medium">A dynamic file explorer of the Controllers, Models, Migrations, Policies, and Views registered inside this module.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Controllers Card -->
                    <div class="bg-surface-container-low p-6 rounded-3xl border border-outline-variant/10 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary">terminal</span>
                                <h4 class="font-black text-xs uppercase tracking-wider text-on-surface">Controllers</h4>
                            </div>
                            <span class="badge badge-primary font-black text-[10px] px-2 py-1">{{ count($controllers) }}</span>
                        </div>
                        <div class="max-h-[140px] overflow-y-auto space-y-2 pr-1 font-mono text-[10px]">
                            @forelse($controllers as $file)
                                <div class="flex items-center gap-2 bg-surface-container-lowest p-2 rounded-xl border border-outline-variant/5">
                                    <span class="material-symbols-outlined text-xs text-primary/70">description</span>
                                    <span class="font-bold opacity-80">{{ $file }}</span>
                                </div>
                            @empty
                                <p class="text-[10px] opacity-50 py-4 text-center font-sans font-bold">No Controllers found.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Models Card -->
                    <div class="bg-surface-container-low p-6 rounded-3xl border border-outline-variant/10 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-secondary">database</span>
                                <h4 class="font-black text-xs uppercase tracking-wider text-on-surface">Models</h4>
                            </div>
                            <span class="badge badge-secondary font-black text-[10px] px-2 py-1">{{ count($models) }}</span>
                        </div>
                        <div class="max-h-[140px] overflow-y-auto space-y-2 pr-1 font-mono text-[10px]">
                            @forelse($models as $file)
                                <div class="flex items-center gap-2 bg-surface-container-lowest p-2 rounded-xl border border-outline-variant/5">
                                    <span class="material-symbols-outlined text-xs text-secondary/70">description</span>
                                    <span class="font-bold opacity-80">{{ $file }}</span>
                                </div>
                            @empty
                                <p class="text-[10px] opacity-50 py-4 text-center font-sans font-bold">No Models found.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Migrations Card -->
                    <div class="bg-surface-container-low p-6 rounded-3xl border border-outline-variant/10 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-tertiary">schema</span>
                                <h4 class="font-black text-xs uppercase tracking-wider text-on-surface">Migrations</h4>
                            </div>
                            <span class="badge badge-accent font-black text-[10px] px-2 py-1">{{ count($migrations) }}</span>
                        </div>
                        <div class="max-h-[140px] overflow-y-auto space-y-2 pr-1 font-mono text-[10px]">
                            @forelse($migrations as $file)
                                <div class="flex items-center gap-2 bg-surface-container-lowest p-2 rounded-xl border border-outline-variant/5">
                                    <span class="material-symbols-outlined text-xs text-tertiary/70">description</span>
                                    <span class="font-bold opacity-80">{{ $file }}</span>
                                </div>
                            @empty
                                <p class="text-[10px] opacity-50 py-4 text-center font-sans font-bold">No Migrations found.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Policies Card -->
                    <div class="bg-surface-container-low p-6 rounded-3xl border border-outline-variant/10 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-info">gavel</span>
                                <h4 class="font-black text-xs uppercase tracking-wider text-on-surface">Access Policies</h4>
                            </div>
                            <span class="badge badge-info font-black text-[10px] px-2 py-1">{{ count($policies) }}</span>
                        </div>
                        <div class="max-h-[140px] overflow-y-auto space-y-2 pr-1 font-mono text-[10px]">
                            @forelse($policies as $file)
                                <div class="flex items-center gap-2 bg-surface-container-lowest p-2 rounded-xl border border-outline-variant/5">
                                    <span class="material-symbols-outlined text-xs text-info/70">description</span>
                                    <span class="font-bold opacity-80">{{ $file }}</span>
                                </div>
                            @empty
                                <p class="text-[10px] opacity-50 py-4 text-center font-sans font-bold">No Policies found.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Views Card (Full Width Span) -->
                    <div class="bg-surface-container-low p-6 rounded-3xl border border-outline-variant/10 space-y-4 md:col-span-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-success">grid_view</span>
                                <h4 class="font-black text-xs uppercase tracking-wider text-on-surface">Blade UI Templates (Views)</h4>
                            </div>
                            <span class="badge badge-success text-white font-black text-[10px] px-2 py-1">{{ count($views) }} Templates</span>
                        </div>
                        <div class="max-h-[180px] overflow-y-auto grid grid-cols-1 md:grid-cols-2 gap-2 pr-1 font-mono text-[10px]">
                            @forelse($views as $file)
                                <div class="flex items-center gap-2 bg-surface-container-lowest p-2 rounded-xl border border-outline-variant/5">
                                    <span class="material-symbols-outlined text-xs text-success/70">article</span>
                                    <span class="font-bold opacity-80">{{ $file }}</span>
                                </div>
                            @empty
                                <div class="col-span-2 py-4 text-center font-sans font-bold opacity-50">No UI Templates found.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Tenants List -->
        <div class="card bg-surface-container-lowest shadow-xl border border-outline-variant/15 rounded-[2.5rem] overflow-hidden">
            <div class="card-body p-8 md:p-10 space-y-6">
                <div class="flex items-center gap-4 border-b border-outline-variant/10 pb-4">
                    <span class="material-symbols-outlined text-secondary text-3xl">corporate_fare</span>
                    <div>
                        <h3 class="font-black font-headline text-lg uppercase tracking-wider text-on-surface">Active Tenant Installs</h3>
                        <p class="text-[10px] opacity-70 font-medium">Clients that currently have this modular capability enabled.</p>
                    </div>
                </div>

                @if($activeTenants->isNotEmpty())
                    <div class="overflow-x-auto rounded-3xl border border-outline-variant/10">
                        <table class="table w-full text-xs font-semibold">
                            <thead>
                                <tr class="bg-surface-container-low text-on-surface font-black uppercase tracking-wider text-[10px] border-b border-outline-variant/10">
                                    <th class="py-4 px-6">Tenant Name</th>
                                    <th class="py-4 px-6">Domain / Host</th>
                                    <th class="py-4 px-6">Installation Date</th>
                                    <th class="py-4 px-6 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activeTenants as $tenant)
                                    <tr class="hover:bg-surface-container-low/50 transition-colors border-b border-outline-variant/10">
                                        <td class="py-4 px-6 font-bold text-on-surface">
                                            <div class="flex items-center gap-3">
                                                <div class="avatar placeholder">
                                                    <div class="bg-primary/10 text-primary rounded-xl w-8 h-8 font-bold text-[10px] border border-primary/10">
                                                        {{ strtoupper(substr($tenant->name, 0, 2)) }}
                                                    </div>
                                                </div>
                                                {{ $tenant->name }}
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 font-mono text-on-surface-variant font-medium">{{ $tenant->domain }}</td>
                                        <td class="py-4 px-6 text-on-surface-variant font-medium">
                                            {{ $tenant->installed_at ? \Carbon\Carbon::parse($tenant->installed_at)->format('F d, Y — H:i') : 'System Default' }}
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <span class="badge badge-success text-white font-black text-[9px] uppercase tracking-wider px-2.5 py-1">ENABLED</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-16 opacity-45 gap-4">
                        <span class="material-symbols-outlined text-6xl">corporate_fare</span>
                        <div class="text-center">
                            <h4 class="font-black text-sm uppercase tracking-wider">No Tenant Installs Found</h4>
                            <p class="text-[10px] font-medium mt-1">No registered company has activated this module yet.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
