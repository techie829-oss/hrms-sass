<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface">System Modules</h2>
                <p class="text-sm text-on-surface-variant font-medium mt-1">Configure and sync available capabilities in the ecosystem.</p>
            </div>
            <form action="{{ route('admin.modules.sync') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-secondary rounded-xl font-bold text-xs uppercase tracking-widest px-6 shadow-lg border-none">
                    <span class="material-symbols-outlined text-lg">sync</span> Sync Filesystem
                </button>
            </form>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($availableModules as $slug => $module)
            <div class="bg-surface-container-lowest p-8 rounded-[2.5rem] border border-outline-variant/15 premium-shadow group hover:bg-surface-bright transition-all">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center font-black text-xs premium-shadow border border-primary/5 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">widgets</span>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="badge {{ $module['free'] ? 'badge-success' : 'badge-primary' }} text-white font-black text-[8px] uppercase tracking-widest px-2 py-1 mb-1">
                            {{ $module['free'] ? 'FREE' : 'PAID' }}
                        </span>
                        @if(isset($dbModules[$slug]))
                            <span class="text-[8px] font-black text-on-surface-variant uppercase tracking-widest opacity-50">Active in System</span>
                        @else
                            <span class="text-[8px] font-black text-error uppercase tracking-widest animate-pulse">Needs Sync</span>
                        @endif
                    </div>
                </div>
                <h3 class="text-xl font-black font-headline text-on-surface mb-2 uppercase">{{ $module['name'] }}</h3>
                <p class="text-sm text-on-surface-variant font-medium leading-relaxed mb-6">
                    Modular capability stored at <code class="bg-surface-container-low px-2 py-0.5 rounded font-mono text-[10px]">{{ str_replace(base_path(), '', $module['path']) }}</code>
                </p>
                <div class="pt-6 border-t border-outline-variant/10 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-sm">settings_input_component</span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-on-surface">Auto-Discoverable</span>
                    </div>
                    <button class="btn btn-ghost btn-xs rounded-lg font-black uppercase text-[9px] tracking-widest">Details</button>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
