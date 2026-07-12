<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Salary Components</h2>
                <p class="text-xs font-medium text-slate-500 mt-0.5">Configure individual earnings and deductions for salary architecture.</p>
            </div>
            <div class="flex gap-2">
                <button onclick="add_component_modal.showModal()" class="btn btn-primary btn-sm rounded-xl px-5 shadow-sm shadow-primary/20 flex items-center gap-1.5 font-bold uppercase tracking-wider text-[10px]">
                    <span class="material-symbols-outlined text-sm font-bold">add_circle</span> Define Component
                </button>
                <a href="{{ route('payroll.index') }}" class="btn btn-ghost btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 rounded-xl px-4 flex items-center gap-1.5 font-bold uppercase tracking-wider text-[10px]">
                    <span class="material-symbols-outlined text-sm font-bold">arrow_back</span> Back to Registry
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Components Table Container -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-zebra w-full text-left">
                <thead class="bg-base-200/50 text-[10px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Component Name</th>
                        <th class="px-6 py-4">Code</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Calculation Path</th>
                        <th class="px-6 py-4">Default Value</th>
                        <th class="px-6 py-4">Taxability</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($components as $component)
                        <tr class="text-xs hover:bg-base-200/20 group">
                            <td class="px-6 py-4">
                                <div class="font-bold text-sm text-slate-900">{{ $component->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono bg-slate-100 text-slate-700 px-2 py-0.5 rounded-lg border border-slate-200/60 font-bold text-[10px] uppercase">{{ $component->code }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $typeClasses = [
                                        'earning' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                                        'deduction' => 'bg-rose-50 text-rose-700 border-rose-200/60',
                                    ];
                                    $badgeClass = $typeClasses[$component->type] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                                @endphp
                                <span class="badge border {{ $badgeClass }} font-bold text-[8px] uppercase tracking-wider px-2 py-1.5 rounded-md">
                                    {{ $component->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-600 uppercase tracking-wider text-[10px]">
                                {{ str_replace('_', ' ', $component->calculation_type) }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900">
                                {{ $component->calculation_type === 'percentage' ? $component->default_amount . '%' : '₹' . number_format($component->default_amount, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-base {{ $component->is_taxable ? 'text-success' : 'text-slate-300' }}">
                                        {{ $component->is_taxable ? 'verified_user' : 'disabled_by_default' }}
                                    </span>
                                    <span class="text-[9px] font-bold uppercase tracking-wider opacity-60">{{ $component->is_taxable ? 'Taxable' : 'Exempt' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button onclick="document.getElementById('edit_component_{{ $component->id }}').showModal()" class="btn btn-ghost btn-xs btn-square rounded-xl hover:bg-slate-100 text-primary-600" title="Edit Component">
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                    </button>
                                    <form action="{{ route('payroll.components.destroy', $component->id) }}" method="POST" onsubmit="return confirm('Delete this component?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-xs btn-square rounded-xl hover:bg-rose-50 text-rose-500" title="Delete Component">
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                        </button>
                                    </form>
                                </div>

                                {{-- Edit Modal --}}
                                <dialog id="edit_component_{{ $component->id }}" class="modal text-left">
                                    <div class="modal-box max-w-lg bg-surface rounded-2xl p-6 shadow-2xl border border-outline-variant/20">
                                        <div class="flex items-center justify-between pb-4 border-b border-outline-variant/10">
                                            <h3 class="text-base font-bold text-on-surface">Edit Component — {{ $component->name }}</h3>
                                            <button type="button" onclick="document.getElementById('edit_component_{{ $component->id }}').close()" class="btn btn-ghost btn-xs btn-circle">
                                                <span class="material-symbols-outlined text-sm">close</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('payroll.components.update', $component->id) }}" method="POST" class="mt-4 space-y-4">
                                            @csrf
                                            @method('PUT')
                                            <div class="grid grid-cols-2 gap-4">
                                                <div class="form-control w-full">
                                                    <label class="label text-[10px] font-bold uppercase opacity-60">Display Name</label>
                                                    <input type="text" name="name" value="{{ $component->name }}" required class="input input-sm border border-slate-200 rounded-xl w-full text-xs" />
                                                </div>
                                                <div class="form-control w-full">
                                                    <label class="label text-[10px] font-bold uppercase opacity-60">System Code</label>
                                                    <input type="text" name="code" value="{{ $component->code }}" required class="input input-sm border border-slate-200 rounded-xl w-full text-xs uppercase" />
                                                </div>
                                                <div class="form-control w-full">
                                                    <label class="label text-[10px] font-bold uppercase opacity-60">Entry Type</label>
                                                    <select name="type" required class="select select-sm border border-slate-200 rounded-xl w-full text-xs">
                                                        <option value="earning" {{ $component->type === 'earning' ? 'selected' : '' }}>Earning</option>
                                                        <option value="deduction" {{ $component->type === 'deduction' ? 'selected' : '' }}>Deduction</option>
                                                    </select>
                                                </div>
                                                <div class="form-control w-full">
                                                    <label class="label text-[10px] font-bold uppercase opacity-60">Calculation Path</label>
                                                    <select name="calculation_type" required class="select select-sm border border-slate-200 rounded-xl w-full text-xs">
                                                        <option value="fixed" {{ $component->calculation_type === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                                        <option value="percentage" {{ $component->calculation_type === 'percentage' ? 'selected' : '' }}>Percentage Based</option>
                                                    </select>
                                                </div>
                                                <div class="form-control w-full">
                                                    <label class="label text-[10px] font-bold uppercase opacity-60">Default Value / %</label>
                                                    <input type="number" name="default_amount" step="0.01" value="{{ $component->default_amount }}" required class="input input-sm border border-slate-200 rounded-xl w-full text-xs" />
                                                </div>
                                                <div class="form-control w-full">
                                                    <label class="label text-[10px] font-bold uppercase opacity-60">% Calculated On</label>
                                                    <select name="percentage_base" class="select select-sm border border-slate-200 rounded-xl w-full text-xs">
                                                        <option value="" {{ empty($component->percentage_base) ? 'selected' : '' }}>None (Fixed)</option>
                                                        <option value="BASIC" {{ $component->percentage_base === 'BASIC' ? 'selected' : '' }}>Basic Salary</option>
                                                        <option value="GROSS" {{ $component->percentage_base === 'GROSS' ? 'selected' : '' }}>Gross Salary</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-200/60 flex items-center gap-6">
                                                <label class="flex items-center gap-2 cursor-pointer select-none">
                                                    <input type="checkbox" name="is_taxable" value="1" class="checkbox checkbox-primary checkbox-sm rounded-md" {{ $component->is_taxable ? 'checked' : '' }} />
                                                    <span class="text-[10px] font-bold uppercase opacity-70">Subject to Taxation</span>
                                                </label>
                                                <label class="flex items-center gap-2 cursor-pointer select-none">
                                                    <input type="checkbox" name="is_mandatory" value="1" class="checkbox checkbox-primary checkbox-sm rounded-md" {{ $component->is_mandatory ? 'checked' : '' }} />
                                                    <span class="text-[10px] font-bold uppercase opacity-70">Mandatory</span>
                                                </label>
                                            </div>
                                            <div class="flex gap-2 justify-end pt-2">
                                                <button type="button" onclick="document.getElementById('edit_component_{{ $component->id }}').close()" class="btn btn-ghost btn-sm rounded-xl text-xs uppercase font-bold">Cancel</button>
                                                <button type="submit" class="btn btn-primary btn-sm rounded-xl text-xs uppercase font-bold">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                    <form method="dialog" class="modal-backdrop bg-base-900/40 backdrop-blur-xs">
                                        <button>close</button>
                                    </form>
                                </dialog>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-20 text-center">
                                <div class="flex flex-col items-center gap-4 opacity-40">
                                    <span class="material-symbols-outlined text-6xl">account_tree</span>
                                    <p class="font-bold text-sm">No salary components defined yet.</p>
                                    <button onclick="add_component_modal.showModal()" class="btn btn-ghost btn-sm btn-outline">Define First Component</button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Define Component Modal -->
    <dialog id="add_component_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box bg-base-100 rounded-2xl border border-base-200 shadow-xl max-w-lg p-6">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4 focus:outline-none">✕</button>
            </form>

            <h3 class="text-sm font-bold uppercase tracking-wider text-primary flex items-center gap-2 mb-6">
                <span class="material-symbols-outlined text-base font-bold">add_circle</span>
                Define New Component
            </h3>

            <form action="{{ route('payroll.components.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control w-full">
                        <label class="label text-[10px] font-bold uppercase opacity-60">Component Name</label>
                        <input type="text" name="name" required placeholder="e.g. Basic Salary" class="input input-sm border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl w-full text-xs bg-white placeholder-slate-400/75 transition-all shadow-sm" />
                    </div>

                    <div class="form-control w-full">
                        <label class="label text-[10px] font-bold uppercase opacity-60">System Code</label>
                        <input type="text" name="code" required placeholder="e.g. BASIC" class="input input-sm border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl w-full text-xs bg-white placeholder-slate-400/75 transition-all shadow-sm uppercase" />
                    </div>

                    <div class="form-control w-full">
                        <label class="label text-[10px] font-bold uppercase opacity-60">Entry Type</label>
                        <select name="type" required class="select select-sm border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl w-full text-xs bg-white transition-all shadow-sm">
                            <option value="earning">Earning</option>
                            <option value="deduction">Deduction</option>
                        </select>
                    </div>

                    <div class="form-control w-full">
                        <label class="label text-[10px] font-bold uppercase opacity-60">Calculation Path</label>
                        <select name="calculation_type" required class="select select-sm border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl w-full text-xs bg-white transition-all shadow-sm">
                            <option value="fixed">Fixed Amount</option>
                            <option value="percentage">Percentage Based</option>
                        </select>
                    </div>

                    <div class="form-control w-full">
                        <label class="label text-[10px] font-bold uppercase opacity-60">Default Value / %</label>
                        <input type="number" name="default_amount" step="0.01" required placeholder="e.g. 50.00" class="input input-sm border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl w-full text-xs bg-white placeholder-slate-400/75 transition-all shadow-sm" />
                    </div>

                    <div class="form-control w-full">
                        <label class="label text-[10px] font-bold uppercase opacity-60">% Calculated On</label>
                        <select name="percentage_base" class="select select-sm border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl w-full text-xs bg-white transition-all shadow-sm">
                            <option value="">None (Fixed)</option>
                            <option value="BASIC">Basic Salary</option>
                            <option value="GROSS">Gross Salary</option>
                        </select>
                    </div>
                </div>

                <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-200/60 flex items-center justify-start gap-6 mt-4">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="is_taxable" value="1" class="checkbox checkbox-primary checkbox-sm rounded-md" checked />
                        <span class="text-[10px] font-bold uppercase opacity-70">Subject to Taxation</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="is_mandatory" value="1" class="checkbox checkbox-primary checkbox-sm rounded-md" />
                        <span class="text-[10px] font-bold uppercase opacity-70">Mandatory</span>
                    </label>
                </div>

                <div class="flex gap-2 justify-end pt-4">
                    <button type="button" onclick="add_component_modal.close()" class="btn btn-ghost btn-sm rounded-xl text-xs uppercase font-bold tracking-wider">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-xl text-xs uppercase font-bold tracking-wider flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">save</span>
                        Save Definition
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-base-900/40 backdrop-blur-xs">
            <button>close</button>
        </form>
    </dialog>
</x-app-layout>
