<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.modules.index') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-slate-700 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                </a>
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $slug === 'leave' ? 'Leave & Time-Off System' : $module['name'] }}</h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $module['free'] ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-indigo-50 text-indigo-700 border border-indigo-200' }} uppercase tracking-wider">
                            {{ $slug === 'leave' ? 'Integrated Ecosystem' : ($module['free'] ? 'Core' : 'Premium') }}
                        </span>
                    </div>
                    <p class="text-sm text-slate-500 mt-1">
                        @if($slug === 'leave')
                            Comprehensive management of Annual Leaves, Compensatory Offs, and Public Holidays.
                        @else
                            Internal mapping, security surface, and filesystem architecture.
                        @endif
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="hidden md:flex flex-col items-end mr-2">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">System Status</span>
                    <span class="flex items-center gap-1.5 text-xs font-bold {{ $dbModule ? 'text-emerald-600' : 'text-red-600' }}">
                        <span class="w-2 h-2 rounded-full {{ $dbModule ? 'bg-emerald-500' : 'bg-red-500 animate-pulse' }}"></span>
                        {{ $dbModule ? 'Fully Integrated' : 'Integration Pending' }}
                    </span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 pb-12">
        <!-- 1. Main Content: Components Explorer (Col 8) -->
        <div class="lg:col-span-8 space-y-8">
            <!-- Security Surface Section (The New Hierarchy Integration) -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                
                <div class="relative flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-md">
                            <span class="material-symbols-outlined text-2xl">admin_panel_settings</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 tracking-tight">Security Hierarchy</h3>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mt-1">Authorized Capabilities & Scopes</p>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-indigo-100">{{ count($modulePermissions) }}</div>
                </div>

                <div class="relative space-y-2">
                    <div class="flex items-center gap-2 text-indigo-600 mb-3">
                        <span class="material-symbols-outlined text-sm">folder_open</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest">{{ $slug }}::permissions</span>
                    </div>
                    
                    <div class="ml-2 border-l-2 border-slate-100 pl-4 space-y-3">
                        @forelse($modulePermissions as $perm)
                            <div class="relative group">
                                <!-- Connection Line -->
                                <div class="absolute -left-4 top-1/2 w-3 h-[2px] bg-slate-100 group-hover:bg-indigo-200 transition-colors"></div>
                                
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 hover:border-indigo-200 transition-colors">
                                    <div class="flex items-center gap-2 mb-1">
                                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-400"></div>
                                        <span class="font-mono font-bold text-xs text-slate-900 tracking-wide">{{ $perm->name }}</span>
                                    </div>
                                    <p class="text-xs text-slate-500 ml-4">{{ $perm->description ?: 'System capability mapping.' }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic">No permissions defined</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Components Hierarchy Tree -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-8">
                <div class="flex items-center gap-3 mb-8">
                    <span class="material-symbols-outlined text-indigo-600 text-xl">account_tree</span>
                    <h4 class="font-bold text-sm uppercase tracking-widest text-slate-900">System Component Tree</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Logical Branch -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 text-slate-700">
                            <span class="material-symbols-outlined text-lg">terminal</span>
                            <span class="text-[10px] font-bold uppercase tracking-widest">Controllers / Business Logic</span>
                        </div>
                        <div class="ml-2 border-l-2 border-slate-100 pl-4 space-y-2">
                            @foreach($controllers as $file)
                                <div class="relative flex items-center gap-2">
                                    <div class="absolute -left-4 top-1/2 w-3 h-[2px] bg-slate-100"></div>
                                    <span class="material-symbols-outlined text-[14px] text-slate-400">description</span>
                                    <span class="font-mono text-[11px] font-medium text-slate-600">{{ $file }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Storage Branch -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 text-slate-700">
                            <span class="material-symbols-outlined text-lg">database</span>
                            <span class="text-[10px] font-bold uppercase tracking-widest">Data Models / Storage</span>
                        </div>
                        <div class="ml-2 border-l-2 border-slate-100 pl-4 space-y-2">
                            @foreach($models as $file)
                                <div class="relative flex items-center gap-2">
                                    <div class="absolute -left-4 top-1/2 w-3 h-[2px] bg-slate-100"></div>
                                    <span class="material-symbols-outlined text-[14px] text-slate-400">storage</span>
                                    <span class="font-mono text-[11px] font-medium text-slate-600">{{ $file }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- UI Templates (Full Width) -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-indigo-600 text-xl">grid_view</span>
                        <h4 class="font-bold text-sm uppercase tracking-widest text-slate-900">UI Component Tree (Views)</h4>
                    </div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">{{ count($views) }} files</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($views as $file)
                        <div class="flex items-center gap-2 p-2 rounded-lg bg-slate-50 border border-slate-200">
                            <span class="material-symbols-outlined text-[14px] text-slate-400">article</span>
                            <span class="font-mono text-[10px] font-medium truncate text-slate-600" title="{{ $file }}">{{ $file }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 2. Sidebar: Stats & Tenants (Col 4) -->
        <div class="lg:col-span-4 space-y-8">
            <!-- Filesystem Identity -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                <h4 class="font-bold text-[10px] uppercase tracking-widest text-slate-500 mb-4">System Identity</h4>
                <div class="space-y-5">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-indigo-600">Relative Path</label>
                        <div class="p-3 rounded-lg bg-slate-50 border border-slate-200">
                            <code class="text-[10px] font-mono break-all font-medium text-slate-700">{{ str_replace(base_path(), '', $module['path']) }}</code>
                        </div>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t border-slate-100">
                        <span class="text-xs font-semibold text-slate-600">Migrations</span>
                        <span class="font-bold text-sm text-slate-900">{{ count($migrations) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-semibold text-slate-600">Policies</span>
                        <span class="font-bold text-sm text-slate-900">{{ count($policies) }}</span>
                    </div>
                </div>
            </div>

            <!-- Active Installs -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="font-bold text-[10px] uppercase tracking-widest text-slate-500">Tenant Installs</h4>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-slate-100 text-slate-700">{{ count($activeTenants) }}</span>
                </div>
                
                <div class="space-y-3">
                    @forelse($activeTenants as $tenant)
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-slate-50 border border-slate-200">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center font-bold text-[10px]">
                                {{ strtoupper(substr($tenant->name, 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h5 class="text-xs font-bold text-slate-900 uppercase truncate">{{ $tenant->name }}</h5>
                                <p class="text-[10px] font-mono text-slate-500 truncate">{{ $tenant->domain }}</p>
                            </div>
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <span class="material-symbols-outlined text-3xl text-slate-300">corporate_fare</span>
                            <p class="text-[10px] font-bold uppercase tracking-widest mt-2 text-slate-400">Not activated yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="bg-slate-900 border border-slate-800 rounded-xl shadow-lg p-6 text-white">
                <h4 class="font-bold text-[10px] uppercase tracking-widest text-slate-400 mb-4">Development Tools</h4>
                <div class="space-y-3">
                    <button class="w-full flex items-center justify-start px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-[10px] font-bold uppercase tracking-widest hover:bg-slate-700 transition-colors">
                        <span class="material-symbols-outlined mr-3 text-base text-slate-400">terminal</span> Open in terminal
                    </button>
                    <button class="w-full flex items-center justify-start px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-[10px] font-bold uppercase tracking-widest hover:bg-slate-700 transition-colors">
                        <span class="material-symbols-outlined mr-3 text-base text-slate-400">history</span> Integration Logs
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
