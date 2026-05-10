<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface">Module Visualizer</h2>
                <p class="text-sm text-on-surface-variant font-medium mt-1">Structural map of ecosystem dependencies and requirements.</p>
            </div>
            <a href="{{ route('admin.modules.index') }}" class="btn btn-ghost rounded-xl font-bold text-xs uppercase tracking-widest px-6">
                <span class="material-symbols-outlined text-lg">arrow_back</span> Back to List
            </a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        {{-- Sidebar: Module Status --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-surface-container-lowest p-6 rounded-[2rem] border border-outline-variant/15 shadow-sm">
                <h3 class="text-xs font-black uppercase tracking-widest text-on-surface-variant mb-6 opacity-50 text-center">Module Inventory</h3>
                <div class="space-y-3">
                    @foreach($availableModules as $slug => $module)
                        <div class="flex items-center justify-between p-3 bg-surface-container-low rounded-2xl border border-outline-variant/5">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary text-xl">
                                    {{ $slug === 'hr' ? 'hub' : ($slug === 'attendance' ? 'timer' : ($slug === 'leave' ? 'event_busy' : 'extension')) }}
                                </span>
                                <span class="text-[10px] font-black uppercase tracking-wider text-on-surface">{{ $module['name'] }}</span>
                            </div>
                            @if($dependencies[$slug]['mandatory'] ?? false)
                                <span class="badge badge-error badge-xs text-[8px] font-black uppercase tracking-tighter text-white">Core</span>
                            @else
                                <span class="badge badge-neutral badge-xs text-[8px] font-black uppercase tracking-tighter opacity-50">Opt</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-primary/5 p-8 rounded-[2rem] border border-primary/10">
                <h4 class="text-[10px] font-black uppercase tracking-widest text-primary mb-2">Architectural Note</h4>
                <p class="text-xs font-medium text-on-surface-variant leading-relaxed italic opacity-70">
                    Modules are designed to be loosely coupled. While HR is the mandatory foundation, Attendance and Leave can operate independently.
                </p>
            </div>
        </div>

        {{-- Main: Dependency Graph --}}
        <div class="lg:col-span-3">
            <div class="bg-surface-container-lowest p-10 rounded-[2.5rem] border border-outline-variant/15 shadow-2xl min-h-[600px] flex flex-col">
                <div class="flex items-center justify-between mb-10">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-tertiary/10 text-tertiary flex items-center justify-center">
                            <span class="material-symbols-outlined text-2xl">account_tree</span>
                        </div>
                        <h3 class="text-xl font-black font-headline text-on-surface uppercase tracking-tight">Dependency Map</h3>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-error shadow-sm shadow-error/30"></div>
                            <span class="text-[9px] font-black uppercase tracking-widest opacity-50">Mandatory</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-primary shadow-sm shadow-primary/30"></div>
                            <span class="text-[9px] font-black uppercase tracking-widest opacity-50">Optional</span>
                        </div>
                    </div>
                </div>

                <div class="flex-1 flex items-center justify-center bg-surface-container-low/30 rounded-[2rem] border border-dashed border-outline-variant/30 p-8">
                    <div id="mermaid-graph" class="w-full">
                        <pre class="mermaid text-center">
graph TD
    classDef mandatory fill:#fee2e2,stroke:#ef4444,stroke-width:2px,color:#991b1b,font-weight:bold;
    classDef optional fill:#e0e7ff,stroke:#6366f1,stroke-width:2px,color:#3730a3,font-weight:bold;
    classDef placeholder fill:#f3f4f6,stroke:#d1d5db,stroke-dasharray: 5 5;

    HR[Human Resources]:::mandatory
    AT[Attendance]:::optional
    LV[Leave]:::optional
    PY[Payroll]:::optional
    PF[Performance]:::optional
    RC[Recruitment]:::optional

    HR --> AT
    HR --> LV
    HR --> PY
    AT --> PY
    LV --> PY
    HR --> PF
    RC -.->|Hiring| HR
                        </pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>
    <script>
        mermaid.initialize({
            startOnLoad: true,
            theme: 'base',
            themeVariables: {
                fontFamily: 'Inter, system-ui, sans-serif',
                fontSize: '12px',
                primaryColor: '#6366f1',
                edgeLabelBackground: '#ffffff',
                mainBkg: '#ffffff',
                nodeBorder: '#6366f1',
                lineColor: '#6366f1'
            }
        });
    </script>
    @endpush
</x-app-layout>
