<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('admin.modules.index') }}" class="w-12 h-12 rounded-2xl bg-surface-container-low flex items-center justify-center border border-outline-variant/20 hover:bg-surface-container-high transition-all group">
                    <span class="material-symbols-outlined text-on-surface-variant group-hover:-translate-x-1 transition-transform">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-4xl font-black font-headline tracking-tight text-on-surface uppercase">Module Visualizer</h2>
                    <p class="text-sm text-on-surface-variant font-medium mt-1 opacity-70">Structural map of ecosystem dependencies and requirements.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex items-center gap-4 bg-surface-container-low px-6 py-3 rounded-2xl border border-outline-variant/10">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-error shadow-lg shadow-error/30"></div>
                        <span class="text-[9px] font-black uppercase tracking-widest opacity-60">Mandatory Core</span>
                    </div>
                    <div class="w-px h-4 bg-outline-variant/20"></div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-primary shadow-lg shadow-primary/30"></div>
                        <span class="text-[9px] font-black uppercase tracking-widest opacity-60">Optional Add-on</span>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-10 pb-20">
        <!-- Sidebar: Module Inventory -->
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-surface-container-lowest p-8 rounded-[3rem] border border-outline-variant/15 shadow-xl">
                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-on-surface-variant mb-8 opacity-40 text-center">Module Inventory</h3>
                <div class="space-y-4">
                    @foreach($availableModules as $slug => $module)
                        <div class="group flex items-center justify-between p-4 bg-surface-container-low rounded-[1.5rem] border border-outline-variant/5 hover:border-primary/20 transition-all">
                            <div class="flex items-center gap-4">
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
                                <span class="material-symbols-outlined text-primary text-xl group-hover:scale-110 transition-transform">
                                    {{ $icons[$slug] ?? 'extension' }}
                                </span>
                                <span class="text-[10px] font-black uppercase tracking-wider text-on-surface">{{ $module['name'] }}</span>
                            </div>
                            @if($dependencies[$slug]['mandatory'] ?? false)
                                <span class="px-2 py-0.5 rounded-md bg-error/10 text-error text-[8px] font-black uppercase tracking-tighter border border-error/20">Core</span>
                            @else
                                <span class="px-2 py-0.5 rounded-md bg-surface-container-highest text-on-surface-variant text-[8px] font-black uppercase tracking-tighter opacity-50">Opt</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-primary/5 p-10 rounded-[3rem] border border-primary/10 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>
                <h4 class="text-[10px] font-black uppercase tracking-widest text-primary mb-4 relative z-10">Architectural Principle</h4>
                <p class="text-xs font-medium text-on-surface-variant leading-relaxed italic opacity-70 relative z-10">
                    The platform utilizes a "Hub-and-Spoke" model where the HR module serves as the central identity registry for all other capabilities.
                </p>
            </div>
        </div>

        <!-- Main: Dependency Graph -->
        <div class="lg:col-span-3">
            <div class="bg-surface-container-lowest p-12 rounded-[4rem] border border-outline-variant/15 shadow-2xl min-h-[700px] flex flex-col relative overflow-hidden">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-primary/5 via-transparent to-transparent opacity-50"></div>
                
                <div class="relative flex items-center justify-between mb-12">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-tertiary to-primary text-white flex items-center justify-center shadow-2xl">
                            <span class="material-symbols-outlined text-3xl">account_tree</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black font-headline text-on-surface uppercase tracking-tight">Ecosystem Map</h3>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-on-surface-variant/40 mt-1">Live dependency visualization</p>
                        </div>
                    </div>
                </div>

                <div class="relative flex-1 flex items-center justify-center bg-surface-container-low/20 rounded-[3rem] border border-outline-variant/10 p-12 backdrop-blur-sm overflow-hidden">
                    <div id="mermaid-graph" class="w-full h-full overflow-auto">
                        <pre class="mermaid">
graph TD
    classDef core fill:#fee2e2,stroke:#ef4444,stroke-width:3px,color:#991b1b,font-weight:900;
    classDef addon fill:#f5f3ff,stroke:#8b5cf6,stroke-width:2px,color:#5b21b6,font-weight:bold;
    classDef leaf fill:#ffffff,stroke:#94a3b8,stroke-width:1px,color:#475569;
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
                fontFamily: 'Outfit, Inter, system-ui, sans-serif',
                fontSize: '16px',
                primaryColor: '#6366f1',
                edgeLabelBackground: '#ffffff',
                mainBkg: 'transparent',
                nodeBorder: '#6366f1',
                lineColor: '#6366f1',
                textColor: '#1a1c1e',
                tertiaryColor: '#f3f4f6',
                curve: 'basis'
            }
        });
    </script>
    @endpush
</x-app-layout>
