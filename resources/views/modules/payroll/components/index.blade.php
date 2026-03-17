<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface uppercase tracking-tighter">Treasury Definitions</h2>
                <p class="text-sm text-on-surface-variant font-medium mt-1">Configure individual fiscal components for salary architecture.</p>
            </div>
            <div class="flex gap-3">
                <button onclick="document.getElementById('add_component_modal').showModal()" class="btn btn-primary bg-gradient-to-br from-primary to-tertiary border-none rounded-xl font-bold text-xs uppercase tracking-widest px-6 shadow-lg">
                    <span class="material-symbols-outlined text-lg">add_circle</span> Define Component
                </button>
                <a href="{{ route('payroll.index') }}" class="btn btn-ghost border-outline-variant/20 rounded-xl font-bold text-xs uppercase tracking-widest px-6">
                    Back to Registry
                </a>
            </div>
        </div>
    </x-slot>

    <div class="bg-surface-container-lowest rounded-[2.5rem] border border-outline-variant/15 shadow-xl overflow-hidden">
        <x-table :headers="['Component', 'Code', 'Type', 'Calc Type', 'Default', 'Taxable', 'Actions']" :striped="false">
            @forelse($components as $component)
                <tr class="hover:bg-primary/5 transition-colors border-b border-outline-variant/5">
                    <td class="py-5 px-8">
                        <div class="font-black text-on-surface text-sm uppercase tracking-tight">{{ $component->name }}</div>
                    </td>
                    <td>
                        <div class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant opacity-60">{{ $component->code }}</div>
                    </td>
                    <td>
                        <span class="badge {{ $component->type === 'earning' ? 'badge-success' : 'badge-error' }} text-white font-black text-[8px] px-3 py-2 uppercase tracking-widest shadow-sm">
                            {{ $component->type }}
                        </span>
                    </td>
                    <td>
                        <div class="text-xs font-bold text-on-surface uppercase tracking-widest">{{ $component->calculation_type }}</div>
                    </td>
                    <td>
                        <div class="text-sm font-black text-on-surface tracking-tighter">
                            {{ $component->calculation_type === 'percentage' ? $component->default_amount . '%' : '₹' . number_format($component->default_amount, 2) }}
                        </div>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm {{ $component->is_taxable ? 'text-success' : 'text-on-surface-variant/20' }}">
                                {{ $component->is_taxable ? 'verified_user' : 'disabled_by_default' }}
                            </span>
                            <span class="text-[9px] font-black uppercase tracking-widest opacity-40">{{ $component->is_taxable ? 'Taxable' : 'Exempt' }}</span>
                        </div>
                    </td>
                    <td class="text-right px-8">
                        <button class="btn btn-ghost btn-xs btn-square rounded-lg hover:bg-primary/10 hover:text-primary">
                            <span class="material-symbols-outlined text-sm">edit_note</span>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-20 text-center opacity-30">
                        <span class="material-symbols-outlined text-6xl">account_tree</span>
                        <p class="font-headline font-black text-lg uppercase tracking-tighter mt-4">No Components Defined</p>
                    </td>
                </tr>
            @endforelse
        </x-table>
    </div>

    <!-- Add Component Modal -->
    <dialog id="add_component_modal" class="modal">
        <div class="modal-box bg-surface-container-lowest rounded-[3rem] p-12 shadow-2xl border border-outline-variant/15 text-left max-w-2xl">
            <h3 class="font-black font-headline text-3xl mb-8 text-on-surface tracking-tighter uppercase">Define New Component</h3>
            <form action="{{ route('payroll.components.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" name="name" type="text" class="block w-full" required placeholder="e.g., Basic Salary" />
                    </div>
                    <div class="space-y-2">
                        <x-input-label for="code" :value="__('System Code')" />
                        <x-text-input id="code" name="code" type="text" class="block w-full" required placeholder="BASIC" />
                    </div>
                    <div class="space-y-2">
                        <x-input-label for="type" :value="__('Entry Type')" />
                        <select name="type" class="w-full bg-surface-container-low border-transparent focus:border-primary focus:ring-0 rounded-2xl p-4 text-on-surface font-bold uppercase tracking-widest text-xs" required>
                            <option value="earning">Earning</option>
                            <option value="deduction">Deduction</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <x-input-label for="calculation_type" :value="__('Calculation Path')" />
                        <select name="calculation_type" class="w-full bg-surface-container-low border-transparent focus:border-primary focus:ring-0 rounded-2xl p-4 text-on-surface font-bold uppercase tracking-widest text-xs" required>
                            <option value="fixed">Fixed Amount</option>
                            <option value="percentage">Percentage Based</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <x-input-label for="default_amount" :value="__('Default Value / %')" />
                        <x-text-input id="default_amount" name="default_amount" type="number" step="0.01" class="block w-full" required />
                    </div>
                    <div class="space-y-2">
                        <x-input-label for="percentage_base" :value="__('% Calculated On')" />
                        <select name="percentage_base" class="w-full bg-surface-container-low border-transparent focus:border-primary focus:ring-0 rounded-2xl p-4 text-on-surface font-bold uppercase tracking-widest text-xs">
                            <option value="">None (Fixed)</option>
                            <option value="BASIC">Basic Salary</option>
                            <option value="GROSS">Gross Salary</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-6 bg-surface-container-low p-6 rounded-2xl">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_taxable" value="1" class="checkbox checkbox-primary rounded-lg" checked>
                        <span class="text-xs font-black uppercase tracking-widest">Subject to Taxation</span>
                    </div>
                    <div class="flex items-center gap-3 border-l border-outline-variant/20 pl-6">
                        <input type="checkbox" name="is_mandatory" value="1" class="checkbox checkbox-primary rounded-lg">
                        <span class="text-xs font-black uppercase tracking-widest">Mandatory</span>
                    </div>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="submit" class="flex-1 btn btn-primary rounded-2xl font-black uppercase tracking-[0.2em] text-xs h-auto py-5 shadow-lg">Record Definition</button>
                    <form method="dialog" class="flex-none"><button class="btn btn-ghost rounded-2xl font-bold uppercase tracking-widest text-xs px-8">Abort</button></form>
                </div>
            </form>
        </div>
    </dialog>
</x-app-layout>
