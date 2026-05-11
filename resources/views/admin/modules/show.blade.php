<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('admin.modules.index') }}" class="w-12 h-12 rounded-2xl bg-surface-container-low flex items-center justify-center border border-outline-variant/20 hover:bg-surface-container-high transition-all group">
                    <span class="material-symbols-outlined text-on-surface-variant group-hover:-translate-x-1 transition-transform">arrow_back</span>
                </a>
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-4xl font-black font-headline tracking-tight text-on-surface uppercase">{{ $slug === 'leave' ? 'Leave & Time-Off System' : $module['name'] }}</h2>
                        <span class="px-3 py-1 rounded-full {{ $module['free'] ? 'bg-success/10 text-success' : 'bg-primary/10 text-primary' }} font-black text-[10px] uppercase tracking-widest border {{ $module['free'] ? 'border-success/20' : 'border-primary/20' }}">
                            {{ $slug === 'leave' ? 'Integrated Ecosystem' : ($module['free'] ? 'Core' : 'Premium') }}
                        </span>
                    </div>
                    <p class="text-sm text-on-surface-variant font-medium mt-1 opacity-70">
                        @if($slug === 'leave')
                            Comprehensive management of Annual Leaves, Compensatory Offs, and Public Holidays.
                        @else
                            Internal mapping, security surface, and filesystem architecture.
                        @endif
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="hidden md:flex flex-col items-end mr-4">
                    <span class="text-[10px] font-black uppercase tracking-widest opacity-40">System Status</span>
                    <span class="flex items-center gap-2 text-xs font-bold {{ $dbModule ? 'text-success' : 'text-error' }}">
                        <span class="w-2 h-2 rounded-full {{ $dbModule ? 'bg-success' : 'bg-error animate-pulse' }}"></span>
                        {{ $dbModule ? 'Fully Integrated' : 'Integration Pending' }}
                    </span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 pb-20">
        <!-- 1. Main Content: Components Explorer (Col 8) -->
        <div class="lg:col-span-8 space-y-10">
            <!-- Security Surface Section (The New Hierarchy Integration) -->
            <div class="bg-surface-container-lowest p-10 rounded-[3rem] border border-outline-variant/15 shadow-xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                
                <div class="relative flex items-center justify-between mb-10">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 rounded-2xl bg-primary text-white flex items-center justify-center shadow-2xl shadow-primary/30">
                            <span class="material-symbols-outlined text-3xl">admin_panel_settings</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black font-headline text-on-surface uppercase tracking-tight">Security Hierarchy</h3>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-on-surface-variant/40 mt-1">Authorized Capabilities & Scopes</p>
                        </div>
                    </div>
                    <div class="text-4xl font-black text-primary/20">{{ count($modulePermissions) }}</div>
                </div>

                <div class="relative space-y-2">
                    <div class="flex items-center gap-3 text-primary mb-4">
                        <span class="material-symbols-outlined text-sm">folder_open</span>
                        <span class="text-[10px] font-black uppercase tracking-widest">{{ $slug }}::permissions</span>
                    </div>
                    
                    <div class="ml-4 border-l-2 border-outline-variant/10 pl-6 space-y-4">
                        @forelse($modulePermissions as $perm)
                            <div class="relative group/perm">
                                <!-- Connection Line -->
                                <div class="absolute -left-6 top-1/2 w-4 h-[2px] bg-outline-variant/10 group-hover/perm:bg-primary/30 transition-colors"></div>
                                
                                <div class="bg-surface-container-low p-4 rounded-2xl border border-outline-variant/10 hover:border-primary/20 transition-all">
                                    <div class="flex items-center gap-3 mb-1">
                                        <div class="w-2 h-2 rounded-full bg-primary/40"></div>
                                        <span class="font-mono font-black text-xs text-on-surface uppercase tracking-widest">{{ $perm->name }}</span>
                                    </div>
                                    <p class="text-[9px] font-medium text-on-surface-variant opacity-60 ml-5">{{ $perm->description ?: 'System capability mapping.' }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-[10px] font-black uppercase tracking-widest opacity-30 italic">No permissions defined</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Components Hierarchy Tree -->
            <div class="bg-surface-container-lowest p-10 rounded-[3rem] border border-outline-variant/15 shadow-xl">
                <div class="flex items-center gap-4 mb-10">
                    <span class="material-symbols-outlined text-tertiary text-2xl">account_tree</span>
                    <h4 class="font-black text-sm uppercase tracking-widest text-on-surface">System Component Tree</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <!-- Logical Branch -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 text-secondary">
                            <span class="material-symbols-outlined text-lg">terminal</span>
                            <span class="text-[10px] font-black uppercase tracking-widest">Controllers / Business Logic</span>
                        </div>
                        <div class="ml-4 border-l-2 border-outline-variant/10 pl-6 space-y-3">
                            @foreach($controllers as $file)
                                <div class="relative flex items-center gap-3">
                                    <div class="absolute -left-6 top-1/2 w-4 h-[2px] bg-outline-variant/10"></div>
                                    <span class="material-symbols-outlined text-xs opacity-30">description</span>
                                    <span class="font-mono text-[10px] font-bold text-on-surface-variant">{{ $file }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Storage Branch -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 text-tertiary">
                            <span class="material-symbols-outlined text-lg">database</span>
                            <span class="text-[10px] font-black uppercase tracking-widest">Data Models / Storage</span>
                        </div>
                        <div class="ml-4 border-l-2 border-outline-variant/10 pl-6 space-y-3">
                            @foreach($models as $file)
                                <div class="relative flex items-center gap-3">
                                    <div class="absolute -left-6 top-1/2 w-4 h-[2px] bg-outline-variant/10"></div>
                                    <span class="material-symbols-outlined text-xs opacity-30">storage</span>
                                    <span class="font-mono text-[10px] font-bold text-on-surface-variant">{{ $file }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- UI Templates (Full Width) -->
            <div class="bg-surface-container-lowest p-8 rounded-[3rem] border border-outline-variant/15 shadow-lg">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-outlined text-info text-2xl">grid_view</span>
                        <h4 class="font-black text-sm uppercase tracking-widest text-on-surface">UI Component Tree (Views)</h4>
                    </div>
                    <span class="text-[10px] font-black text-info uppercase tracking-widest opacity-60">{{ count($views) }} files</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($views as $file)
                        <div class="flex items-center gap-2 p-3 rounded-xl bg-surface-container-low border border-outline-variant/5">
                            <span class="material-symbols-outlined text-xs text-info/40">article</span>
                            <span class="font-mono text-[9px] font-bold truncate text-on-surface-variant" title="{{ $file }}">{{ $file }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 2. Sidebar: Stats & Tenants (Col 4) -->
        <div class="lg:col-span-4 space-y-10">
            <!-- Filesystem Identity -->
            <div class="bg-surface-container-lowest p-8 rounded-[3rem] border border-outline-variant/15 shadow-xl">
                <h4 class="font-black text-[10px] uppercase tracking-widest text-on-surface-variant mb-6 opacity-40">System Identity</h4>
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-primary">Relative Path</label>
                        <div class="p-4 rounded-2xl bg-surface-container-low border border-outline-variant/10">
                            <code class="text-[10px] font-mono break-all font-bold text-on-surface">{{ str_replace(base_path(), '', $module['path']) }}</code>
                        </div>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-outline-variant/10">
                        <span class="text-[10px] font-black uppercase tracking-widest opacity-50">Migrations</span>
                        <span class="font-black text-xs">{{ count($migrations) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-black uppercase tracking-widest opacity-50">Policies</span>
                        <span class="font-black text-xs">{{ count($policies) }}</span>
                    </div>
                </div>
            </div>

            <!-- Active Installs -->
            <div class="bg-surface-container-lowest p-8 rounded-[3rem] border border-outline-variant/15 shadow-xl">
                <div class="flex items-center justify-between mb-8">
                    <h4 class="font-black text-[10px] uppercase tracking-widest text-on-surface-variant opacity-40">Tenant Installs</h4>
                    <span class="badge badge-secondary badge-sm font-black">{{ count($activeTenants) }}</span>
                </div>
                
                <div class="space-y-4">
                    @forelse($activeTenants as $tenant)
                        <div class="flex items-center gap-4 p-4 rounded-2xl bg-surface-container-low border border-outline-variant/5">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-black text-[10px]">
                                {{ strtoupper(substr($tenant->name, 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h5 class="text-xs font-black text-on-surface uppercase truncate">{{ $tenant->name }}</h5>
                                <p class="text-[10px] font-mono text-on-surface-variant opacity-50 truncate">{{ $tenant->domain }}</p>
                            </div>
                            <div class="w-2 h-2 rounded-full bg-success"></div>
                        </div>
                    @empty
                        <div class="text-center py-10 opacity-30">
                            <span class="material-symbols-outlined text-4xl">corporate_fare</span>
                            <p class="text-[10px] font-black uppercase tracking-widest mt-2">Not activated yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="bg-gradient-to-br from-on-surface to-surface-container-highest p-8 rounded-[3rem] shadow-xl text-white">
                <h4 class="font-black text-[10px] uppercase tracking-widest opacity-50 mb-6">Development Tools</h4>
                <div class="space-y-3">
                    <button class="w-full btn btn-ghost text-white border border-white/10 rounded-2xl font-black text-[10px] uppercase tracking-widest h-12 justify-start px-6 hover:bg-white/10">
                        <span class="material-symbols-outlined mr-3 text-lg">terminal</span> Open in terminal
                    </button>
                    <button class="w-full btn btn-ghost text-white border border-white/10 rounded-2xl font-black text-[10px] uppercase tracking-widest h-12 justify-start px-6 hover:bg-white/10">
                        <span class="material-symbols-outlined mr-3 text-lg">history</span> Integration Logs
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
