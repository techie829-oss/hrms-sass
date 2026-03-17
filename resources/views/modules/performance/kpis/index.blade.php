<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-on-surface">Key Performance Indicators</h2>
            <div class="flex gap-2">
                <button onclick="kpi_modal.showModal()" class="btn btn-sm btn-primary border-none rounded-lg font-bold text-[10px] uppercase tracking-wider shadow-sm">
                    <span class="material-symbols-outlined text-sm">add_circle</span> Define KPI
                </button>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- KPIs Table (Matches Refined Design) -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-outline-variant/5 flex items-center justify-between">
                <h3 class="font-bold text-xs uppercase tracking-wider text-on-surface">Metric Definitions</h3>
                <div class="flex items-center gap-2">
                    <span class="text-[9px] font-bold text-on-surface-variant uppercase tracking-wider italic">Strategic Trackers</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table table-xs table-zebra w-full text-[11px]">
                    <thead>
                        <tr class="text-on-surface-variant/70 border-b border-outline-variant/5">
                            <th class="py-3 px-5">KPI Name</th>
                            <th>Department Scope</th>
                            <th class="text-center">Target Value</th>
                            <th class="text-center">Tracking Unit</th>
                            <th class="text-right pr-5">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="font-medium">
                        @forelse($kpis as $kpi)
                        <tr class="hover:bg-primary/5 transition-colors border-b border-outline-variant/5">
                            <td class="py-3 px-5">
                                <div class="font-bold text-on-surface">{{ $kpi->name }}</div>
                                <div class="text-[9px] mt-0.5 max-w-xs truncate opacity-60">{{ $kpi->description }}</div>
                            </td>
                            <td>
                                <span class="badge badge-primary badge-outline text-[8px] font-bold h-4 py-0 uppercase">
                                    {{ $kpi->department->name ?? 'Global/All' }}
                                </span>
                            </td>
                            <td class="text-center py-3 font-black text-on-surface">{{ number_format($kpi->target_value, 2) }}</td>
                            <td class="text-center py-3 font-bold uppercase tracking-tight opacity-60">{{ $kpi->unit }}</td>
                            <td class="text-right pr-5">
                                <button class="btn btn-ghost btn-xs rounded-md text-primary font-bold italic">Edit →</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-on-surface-variant opacity-70 italic">No KPI metrics defined.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($kpis->hasPages())
            <div class="p-4 border-t border-outline-variant/5 bg-surface-container-low/20">
                {{ $kpis->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- Define KPI Modal (Matches Refined Style) --}}
    <dialog id="kpi_modal" class="modal">
        <div class="modal-box max-w-xl bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-2xl p-0 overflow-hidden text-left font-sans">
            <div class="p-5 border-b border-outline-variant/5 flex items-center justify-between bg-surface-container-low/30">
                <div>
                    <h3 class="font-bold text-xs uppercase tracking-wider text-on-surface">Define New metric</h3>
                    <p class="text-[9px] font-bold opacity-50 uppercase tracking-widest mt-0.5">Key Performance Indicator Setup</p>
                </div>
                <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
            </div>
            
            <form action="{{ route('performance.kpis.store') }}" method="POST" class="p-6 space-y-5 text-left">
                @csrf
                <div class="form-control">
                    <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase tracking-wider opacity-60">KPI Identifier</span></label>
                    <input type="text" name="name" required class="input input-sm input-bordered focus:input-primary rounded-lg text-xs" placeholder="e.g. Sales Growth, NPS Score" />
                </div>
                <div class="form-control">
                    <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase tracking-wider opacity-60">Definition / Scope</span></label>
                    <textarea name="description" class="textarea textarea-bordered focus:textarea-primary rounded-lg text-xs" rows="3" placeholder="Explain how this metric is measured..."></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase tracking-wider opacity-60">Dept. Alignment</span></label>
                        <select name="department_id" class="select select-sm select-bordered focus:select-primary rounded-lg text-xs font-bold">
                            <option value="">Global (All Departments)</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase tracking-wider opacity-60">Target Value</span></label>
                        <input type="number" name="target_value" required step="0.01" class="input input-sm input-bordered focus:input-primary rounded-lg text-xs font-bold" placeholder="0.00" />
                    </div>
                </div>
                <div class="form-control">
                    <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase tracking-wider opacity-60">Measurement Unit</span></label>
                    <input type="text" name="unit" required class="input input-sm input-bordered focus:input-primary rounded-lg text-xs" placeholder="e.g. Percentage, Currency, Count" />
                </div>
                <div class="pt-2">
                    <button type="submit" class="btn btn-primary btn-sm w-full rounded-lg font-bold text-[10px] uppercase tracking-wider shadow-sm">Initialize Performance KPI</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-on-surface/20 backdrop-blur-[2px]"><button>close</button></form>
    </dialog>
</x-app-layout>
