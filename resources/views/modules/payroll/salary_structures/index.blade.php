<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface uppercase tracking-tighter">Salary Architecture</h2>
                <p class="text-sm text-on-surface-variant font-medium mt-1">Manage employee compensation frameworks and active structures.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('payroll.salary_structures.create') }}" class="btn btn-primary bg-gradient-to-br from-primary to-secondary border-none rounded-xl font-bold text-xs uppercase tracking-widest px-6 shadow-lg">
                    <span class="material-symbols-outlined text-lg">assignment_ind</span> Assign Structure
                </a>
                <a href="{{ route('payroll.components.index') }}" class="btn btn-ghost border-outline-variant/20 rounded-xl font-bold text-xs uppercase tracking-widest px-6 text-primary">
                    <span class="material-symbols-outlined text-sm">settings</span> Settings
                </a>
            </div>
        </div>
    </x-slot>

    <div class="bg-surface-container-lowest rounded-[2.5rem] border border-outline-variant/15 shadow-xl overflow-hidden">
        <x-table :headers="['Employee', 'Net Salary', 'CTC', 'Status', 'Effective Date', 'Actions']" :striped="false">
            @forelse($structures as $structure)
                <tr class="hover:bg-primary/5 transition-colors border-b border-outline-variant/5">
                    <td class="py-5 px-8">
                        <div class="flex items-center gap-4">
                            <div class="avatar placeholder">
                                <div class="bg-primary/10 text-primary rounded-xl w-10 h-10 font-black text-xs uppercase tracking-widest">
                                    {{ substr($structure->employee->first_name, 0, 1) }}{{ substr($structure->employee->last_name, 0, 1) }}
                                </div>
                            </div>
                            <div>
                                <div class="font-black text-on-surface text-sm uppercase tracking-tight">{{ $structure->employee->first_name }} {{ $structure->employee->last_name }}</div>
                                <div class="text-[10px] text-on-surface-variant opacity-60 font-bold uppercase tracking-widest">{{ $structure->employee->employee_id }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="text-base font-black text-on-surface tracking-tighter">₹{{ number_format($structure->net_salary, 2) }}</div>
                        <div class="text-[9px] font-bold text-on-surface-variant opacity-40 uppercase tracking-widest">Per Month</div>
                    </td>
                    <td>
                        <div class="text-sm font-black text-on-surface-variant tracking-tighter opacity-70">₹{{ number_format($structure->ctc, 2) }}</div>
                        <div class="text-[9px] font-bold text-on-surface-variant opacity-30 uppercase tracking-widest">Annual Cost</div>
                    </td>
                    <td>
                        @if($structure->is_active)
                            <span class="badge badge-success text-white font-black text-[8px] px-3 py-2 uppercase tracking-widest shadow-sm">Active</span>
                        @else
                            <span class="badge badge-neutral text-on-surface-variant opacity-40 font-black text-[8px] px-3 py-2 uppercase tracking-widest">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="text-[11px] font-black text-on-surface uppercase tracking-widest">
                            {{ $structure->effective_from->format('d M Y') }}
                        </div>
                        @if($structure->effective_to)
                            <div class="text-[9px] font-bold text-on-surface-variant opacity-40 uppercase tracking-widest">To: {{ $structure->effective_to->format('d M Y') }}</div>
                        @endif
                    </td>
                    <td class="text-right px-8">
                        <a href="{{ route('payroll.salary_structures.show', $structure->id) }}" class="btn btn-ghost btn-xs btn-square rounded-lg hover:bg-primary/10 hover:text-primary">
                            <span class="material-symbols-outlined text-sm">visibility</span>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-20 text-center opacity-30">
                        <span class="material-symbols-outlined text-6xl">account_balance_wallet</span>
                        <p class="font-headline font-black text-lg uppercase tracking-tighter mt-4">No Salary Frameworks Assigned</p>
                    </td>
                </tr>
            @endforelse
        </x-table>
    </div>
</x-app-layout>
