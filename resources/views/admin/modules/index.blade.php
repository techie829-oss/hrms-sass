<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">System Ecosystem</h2>
                <p class="text-sm text-slate-500 mt-1">Architecture, modules, and unified capabilities of your SaaS platform.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.modules.visualizer') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <span class="material-symbols-outlined text-sm mr-2">account_tree</span> Module Map
                </a>
                <form action="{{ route('admin.modules.sync') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                        <span class="material-symbols-outlined text-sm mr-2">sync_alt</span> Refresh Components
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8 pb-12">
        <!-- Dashboard Style Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 flex flex-col justify-between">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Total Modules</div>
                <div class="text-3xl font-bold text-slate-900">{{ count($availableModules) }}</div>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 flex flex-col justify-between">
                <div class="text-xs font-semibold uppercase tracking-wider text-emerald-600 mb-2">Active Core</div>
                <div class="text-3xl font-bold text-slate-900">{{ count($dbModules) }}</div>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 flex flex-col justify-between">
                <div class="text-xs font-semibold uppercase tracking-wider text-indigo-600 mb-2">Status</div>
                <div class="text-3xl font-bold text-slate-900">{{ count($availableModules) === count($dbModules) ? 'Synced' : 'Pending' }}</div>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 flex items-center justify-center">
                <div class="flex -space-x-3">
                    @foreach(collect($availableModules)->take(4) as $m)
                        <div class="w-10 h-10 rounded-full bg-slate-100 border-2 border-white flex items-center justify-center text-xs font-bold text-slate-600 uppercase shadow-sm">
                            {{ substr($m['name'], 0, 1) }}
                        </div>
                    @endforeach
                    @if(count($availableModules) > 4)
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 border-2 border-white flex items-center justify-center text-xs font-bold shadow-sm">
                            +{{ count($availableModules) - 4 }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Modules Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
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
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden relative flex flex-col">
                    <!-- Progress Bar Background -->
                    <div class="absolute top-0 left-0 w-full h-1 bg-slate-100">
                        <div class="h-full {{ $isActive ? 'bg-indigo-500' : 'bg-slate-300' }} transition-all duration-1000" style="width: {{ $isActive ? '100' : '0' }}%"></div>
                    </div>

                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex items-start justify-between mb-6">
                            <div class="w-14 h-14 rounded-lg {{ $isActive ? 'bg-indigo-50 text-indigo-600 border-indigo-100' : 'bg-slate-50 text-slate-400 border-slate-100' }} border flex items-center justify-center">
                                <span class="material-symbols-outlined text-2xl">{{ $icon }}</span>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $module['free'] ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }} uppercase tracking-wider">
                                    {{ $module['free'] ? 'Core Component' : 'Premium Addon' }}
                                </span>
                                @if(!$isActive)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-red-600 uppercase tracking-widest bg-red-50 px-2 py-0.5 rounded border border-red-100">
                                        <span class="material-symbols-outlined text-[12px]">warning</span> Not Synced
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-3 flex-1">
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 tracking-tight">
                                    {{ $slug === 'leave' ? 'Leave & Time-Off' : $module['name'] }}
                                </h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">{{ $slug }} module</span>
                                    @if($slug === 'leave')
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-indigo-50 text-indigo-700 text-[10px] font-bold uppercase tracking-wider">Includes Comp-Off</span>
                                    @endif
                                </div>
                            </div>

                            <p class="text-sm text-slate-600 line-clamp-2">
                                @if($slug === 'leave')
                                    Advanced absence management engine covering Leaves, Compensatory-Off (Comp-Off) accruals, and Holiday calendars.
                                @else
                                    Comprehensive system integration for handling {{ strtolower($module['name']) }} logic, database migrations, and unified policies.
                                @endif
                            </p>

                            <div class="pt-4">
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex justify-between items-center text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                        <span>Infrastructure</span>
                                        <span class="{{ $isActive ? 'text-emerald-600' : 'text-slate-400' }}">{{ $isActive ? 'OK' : 'Pending' }}</span>
                                    </div>
                                    <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full {{ $isActive ? 'bg-emerald-500' : 'bg-slate-300' }} w-full"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2 text-slate-400">
                                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center hover:bg-slate-100 hover:text-slate-600 transition-colors" title="Policies">
                                    <span class="material-symbols-outlined text-sm">security</span>
                                </div>
                                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center hover:bg-slate-100 hover:text-slate-600 transition-colors" title="Database">
                                    <span class="material-symbols-outlined text-sm">database</span>
                                </div>
                                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center hover:bg-slate-100 hover:text-slate-600 transition-colors" title="API">
                                    <span class="material-symbols-outlined text-sm">api</span>
                                </div>
                            </div>
                            <a href="{{ route('admin.modules.show', $slug) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                                Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
