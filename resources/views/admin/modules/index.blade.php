<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-4xl font-black font-headline tracking-tight text-on-surface leading-tight">System Ecosystem</h2>
                <p class="text-sm text-on-surface-variant font-medium mt-1 opacity-70">Architecture, modules, and unified capabilities of your SaaS platform.</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.modules.visualizer') }}" class="btn btn-ghost bg-surface-container-low rounded-2xl font-black text-[10px] uppercase tracking-wider px-6 border border-outline-variant/20 hover:bg-surface-container-high transition-all h-12 shadow-sm">
                    <span class="material-symbols-outlined text-lg mr-2">account_tree</span> Module Map
                </a>
                <form action="{{ route('admin.modules.sync') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary bg-gradient-to-br from-primary to-tertiary border-none rounded-2xl font-black text-[10px] uppercase tracking-wider px-8 h-12 shadow-xl shadow-primary/20">
                        <span class="material-symbols-outlined text-lg mr-2">sync_alt</span> Refresh Components
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="space-y-10 pb-20">
        <!-- Dashboard Style Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-surface-container-lowest p-6 rounded-[2rem] border border-outline-variant/10 shadow-sm">
                <div class="text-[10px] font-black uppercase tracking-widest text-primary mb-1">Total Modules</div>
                <div class="text-3xl font-black text-on-surface">{{ count($availableModules) }}</div>
            </div>
            <div class="bg-surface-container-lowest p-6 rounded-[2rem] border border-outline-variant/10 shadow-sm">
                <div class="text-[10px] font-black uppercase tracking-widest text-success mb-1">Active Core</div>
                <div class="text-3xl font-black text-on-surface">{{ count($dbModules) }}</div>
            </div>
            <div class="bg-surface-container-lowest p-6 rounded-[2rem] border border-outline-variant/10 shadow-sm">
                <div class="text-[10px] font-black uppercase tracking-widest text-secondary mb-1">Status</div>
                <div class="text-3xl font-black text-on-surface">{{ count($availableModules) === count($dbModules) ? 'Synced' : 'Pending' }}</div>
            </div>
            <div class="bg-surface-container-lowest p-6 rounded-[2rem] border border-outline-variant/10 shadow-sm flex items-center justify-center">
                <div class="flex -space-x-3">
                    @foreach(collect($availableModules)->take(4) as $m)
                        <div class="w-10 h-10 rounded-full bg-surface-container-high border-2 border-surface-container-lowest flex items-center justify-center text-[10px] font-black text-primary uppercase">
                            {{ substr($m['name'], 0, 1) }}
                        </div>
                    @endforeach
                    @if(count($availableModules) > 4)
                        <div class="w-10 h-10 rounded-full bg-primary text-white border-2 border-surface-container-lowest flex items-center justify-center text-[10px] font-black">
                            +{{ count($availableModules) - 4 }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Modules Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            @foreach($availableModules as $slug => $module)
                @php
                    $icons = [
                        'hr' => 'group',
                        'attendance' => 'event_available',
                        'leave' => 'event_busy',
                        'payroll' => 'payments',
                        'performance' => 'bolt',
                        'recruitment' => 'work_history',
                        'operations' => 'settings_applications',
                        'reports' => 'insert_chart'
                    ];
                    $icon = $icons[$slug] ?? 'widgets';
                    $isActive = isset($dbModules[$slug]);
                @endphp
                <div class="group relative bg-surface-container-lowest rounded-[3rem] border border-outline-variant/15 shadow-xl transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-primary/5 overflow-hidden">
                    <!-- Progress Bar Background -->
                    <div class="absolute top-0 left-0 w-full h-1 bg-surface-container-high">
                        <div class="h-full bg-primary transition-all duration-1000" style="width: {{ $isActive ? '100' : '0' }}%"></div>
                    </div>

                    <div class="p-10">
                        <div class="flex items-start justify-between mb-8">
                            <div class="w-16 h-16 rounded-[2rem] bg-gradient-to-br {{ $isActive ? 'from-primary to-tertiary' : 'from-surface-container-high to-surface-container-low' }} text-white flex items-center justify-center shadow-2xl transition-transform duration-500 group-hover:scale-110">
                                <span class="material-symbols-outlined text-3xl">{{ $icon }}</span>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="px-3 py-1 rounded-full {{ $module['free'] ? 'bg-success/10 text-success' : 'bg-secondary/10 text-secondary' }} font-black text-[9px] uppercase tracking-widest border {{ $module['free'] ? 'border-success/20' : 'border-secondary/20' }}">
                                    {{ $module['free'] ? 'Core Component' : 'Premium Addon' }}
                                </span>
                                @if(!$isActive)
                                    <span class="mt-2 flex items-center gap-1 text-[8px] font-black text-error uppercase tracking-widest animate-pulse">
                                        <span class="material-symbols-outlined text-[10px]">warning</span> Not Synced
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <h3 class="text-2xl font-black font-headline text-on-surface uppercase tracking-tight group-hover:text-primary transition-colors">
                                    {{ $slug === 'leave' ? 'Leave & Time-Off' : $module['name'] }}
                                </h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[9px] font-black text-on-surface-variant/40 uppercase tracking-[0.2em]">{{ $slug }} module</span>
                                    @if($slug === 'leave')
                                        <span class="px-1.5 py-0.5 rounded bg-tertiary/10 text-tertiary text-[7px] font-black uppercase">Includes Comp-Off</span>
                                    @endif
                                </div>
                            </div>

                            <p class="text-sm text-on-surface-variant font-medium leading-relaxed opacity-60 line-clamp-2 h-10">
                                @if($slug === 'leave')
                                    Advanced absence management engine covering Leaves, Compensatory-Off (Comp-Off) accruals, and Holiday calendars.
                                @else
                                    Comprehensive system integration for handling {{ strtolower($module['name']) }} logic, database migrations, and unified policies.
                                @endif
                            </p>

                            <div class="flex items-center gap-4 pt-6">
                                <div class="flex-1 flex flex-col gap-1">
                                    <div class="flex justify-between items-center text-[8px] font-black uppercase tracking-widest opacity-40">
                                        <span>Infrastructure</span>
                                        <span>OK</span>
                                    </div>
                                    <div class="h-1 bg-surface-container-high rounded-full overflow-hidden">
                                        <div class="h-full bg-success/60 w-full"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-8 border-t border-outline-variant/10 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-surface-container-low flex items-center justify-center text-on-surface-variant/40" title="Policies">
                                    <span class="material-symbols-outlined text-sm">security</span>
                                </div>
                                <div class="w-8 h-8 rounded-xl bg-surface-container-low flex items-center justify-center text-on-surface-variant/40" title="Database">
                                    <span class="material-symbols-outlined text-sm">database</span>
                                </div>
                                <div class="w-8 h-8 rounded-xl bg-surface-container-low flex items-center justify-center text-on-surface-variant/40" title="API">
                                    <span class="material-symbols-outlined text-sm">api</span>
                                </div>
                            </div>
                            <a href="{{ route('admin.modules.show', $slug) }}" class="group/btn relative overflow-hidden btn btn-ghost btn-sm rounded-2xl font-black uppercase text-[10px] tracking-widest px-6 hover:text-primary transition-all">
                                <span class="relative z-10">Details</span>
                                <div class="absolute inset-0 bg-primary/10 translate-y-full group-hover/btn:translate-y-0 transition-transform"></div>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
