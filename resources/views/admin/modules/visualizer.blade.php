<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.modules.index') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-slate-700 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Module Visualizer</h2>
                    <p class="text-sm text-slate-500 mt-1">Structural map of ecosystem dependencies and requirements.</p>
                </div>
            </div>
            <div class="flex items-center gap-4 bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-red-500 shadow-sm shadow-red-500/30"></div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-600">Mandatory Core</span>
                </div>
                <div class="w-px h-4 bg-slate-200"></div>
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-indigo-500 shadow-sm shadow-indigo-500/30"></div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-600">Optional Add-on</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 pb-12">
        <!-- Sidebar: Module Inventory -->
        <div class="lg:col-span-1 space-y-8">
            <div class="card bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                <h3 class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-6 text-center">Module Inventory</h3>
                <div class="space-y-3">
                    @foreach($availableModules as $slug => $module)
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-200 hover:border-indigo-200 hover:bg-indigo-50 transition-colors group">
                            <div class="flex items-center gap-3">
                                @php
                                    $icons = [
                                        'hr' => 'hub',
                                        'attendance' => 'event_available',
                                        'leave' => 'event_busy',
                                        'payroll' => 'payments',
                                        'performance' => 'bolt',
                                        'recruitment' => 'work_history'
                                    ];
                                @endphp
                                <span class="material-symbols-outlined text-indigo-600 text-lg group-hover:scale-110 transition-transform">
                                    {{ $icons[$slug] ?? 'extension' }}
                                </span>
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-900">{{ $module['name'] }}</span>
                            </div>
                            @if($dependencies[$slug]['mandatory'] ?? false)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-red-50 text-red-700 border border-red-200 uppercase tracking-widest">Core</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-slate-100 text-slate-500 border border-slate-200 uppercase tracking-widest">Opt</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card bg-indigo-50 p-6 rounded-xl border border-indigo-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-100/50 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>
                <h4 class="text-xs font-bold uppercase tracking-widest text-indigo-700 mb-3 relative z-10">Architectural Principle</h4>
                <p class="text-sm font-medium text-indigo-900/80 leading-relaxed italic relative z-10">
                    The platform utilizes a "Hub-and-Spoke" model where the HR module serves as the central identity registry for all other capabilities.
                </p>
            </div>
        </div>

        <!-- Main: Dependency Graph -->
        <div class="lg:col-span-3">
            <div class="card bg-white border border-slate-200 rounded-xl shadow-sm p-8 min-h-[600px] flex flex-col relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-tr from-indigo-50/50 via-transparent to-transparent"></div>
                
                <div class="relative flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-md">
                            <span class="material-symbols-outlined text-2xl">account_tree</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 tracking-tight">Ecosystem Map</h3>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mt-1">Live dependency visualization</p>
                        </div>
                    </div>
                </div>

                <div class="relative flex-1 flex items-center justify-center bg-slate-50/50 rounded-lg border border-slate-200 p-8 overflow-hidden">
                    <div id="mermaid-graph" class="w-full h-full overflow-auto">
                        <pre class="mermaid">
graph TD
    classDef core fill:#fee2e2,stroke:#ef4444,stroke-width:2px,color:#991b1b,font-weight:bold;
    classDef addon fill:#eef2ff,stroke:#6366f1,stroke-width:2px,color:#4338ca,font-weight:bold;
    classDef leaf fill:#ffffff,stroke:#cbd5e1,stroke-width:1px,color:#475569;
    classDef sub fill:#f8fafc,stroke:#e2e8f0,stroke-width:1px,stroke-dasharray: 5 5;

    SAAS[SaaS Core Infrastructure]:::core
    HR[HR Management]:::core

    SAAS --> HR

    subgraph "Workforce Management"
        AT[Attendance System]:::addon
        PF[Performance & KPIs]:::addon
    end

    subgraph "Time-Off Ecosystem"
        LV[Leave & Time-Off]:::addon
        CO[Comp-Off Management]:::leaf
        HL[Holiday Calendar]:::leaf
        AD[Leave Audit Trail]:::leaf
        
        LV --- CO
        LV --- HL
        LV --- AD
    end

    subgraph "Business Operations"
        RC[Recruitment Portal]:::addon
        OP[Operations & CRM]:::addon
    end

    subgraph "Financial Engine"
        PY[Payroll Engine]:::addon
        RP[Global Reports]:::leaf
        PY -.-> RP
    end

    %% Main Flow
    HR --> AT
    HR --> PF
    HR --> LV
    HR --> RC
    HR --> OP

    %% Data Flow to Payroll
    AT --> PY
    LV --> PY
    HR --> PY

    %% Links
    RC -.->|Onboarding| HR
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
                fontSize: '14px',
                primaryColor: '#6366f1',
                edgeLabelBackground: '#ffffff',
                mainBkg: 'transparent',
                nodeBorder: '#6366f1',
                lineColor: '#94a3b8',
                textColor: '#0f172a',
                tertiaryColor: '#f8fafc',
                curve: 'basis'
            }
        });
    </script>
    @endpush
</x-app-layout>
