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

    {{-- Desktop Table View --}}
    <div class="hidden lg:block bg-surface-container-lowest rounded-[2.5rem] border border-outline-variant/15 shadow-xl overflow-hidden">
        <x-table :headers="['Employee', 'Net Salary', 'CTC', 'Status', 'Effective Date', 'Actions']" :striped="false">
            @forelse($structures as $structure)
                @php
                    $colors = ['bg-blue-600', 'bg-indigo-600', 'bg-violet-600', 'bg-purple-600', 'bg-teal-600', 'bg-emerald-600', 'bg-cyan-600', 'bg-sky-600'];
                    $colorClass = $colors[$structure->employee->id % count($colors)];
                @endphp
                <tr class="hover:bg-primary/5 transition-colors border-b border-outline-variant/5">
                    <td class="py-5 px-8">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl {{ !empty($structure->employee->profile_photo) ? 'bg-slate-100 text-slate-800' : $colorClass . ' text-white' }} font-bold text-xs flex items-center justify-center shrink-0 border border-slate-200/60 shadow-sm overflow-hidden">
                                @if(!empty($structure->employee->profile_photo))
                                    <img src="{{ asset('storage/' . $structure->employee->profile_photo) }}" alt="" class="w-full h-full object-cover">
                                @else
                                    {{ strtoupper(substr($structure->employee->first_name, 0, 1) . substr($structure->employee->last_name, 0, 1)) }}
                                @endif
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

    {{-- Mobile Card Stack View --}}
    <div class="lg:hidden space-y-3">
        @forelse($structures as $structure)
            @php
                $colors = ['bg-blue-600', 'bg-indigo-600', 'bg-violet-600', 'bg-purple-600', 'bg-teal-600', 'bg-emerald-600', 'bg-cyan-600', 'bg-sky-600'];
                $colorClass = $colors[$structure->employee->id % count($colors)];
            @endphp
            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm space-y-3">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-xl {{ !empty($structure->employee->profile_photo) ? 'bg-slate-100 text-slate-800' : $colorClass . ' text-white' }} font-bold text-xs flex items-center justify-center shrink-0 border border-slate-200/60 shadow-sm overflow-hidden">
                            @if(!empty($structure->employee->profile_photo))
                                <img src="{{ asset('storage/' . $structure->employee->profile_photo) }}" alt="" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr($structure->employee->first_name, 0, 1) . substr($structure->employee->last_name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="min-w-0">
                            <div class="font-bold text-sm text-slate-800 truncate">{{ $structure->employee->first_name }} {{ $structure->employee->last_name }}</div>
                            <div class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider truncate">{{ $structure->employee->employee_id }}</div>
                        </div>
                    </div>
                    @if($structure->is_active)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60 uppercase shrink-0">Active</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200/60 uppercase shrink-0">Inactive</span>
                    @endif
                </div>

                <div class="bg-slate-50/80 rounded-xl p-3 grid grid-cols-2 gap-3 border border-slate-100 text-xs">
                    <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Net Salary / Mo</span>
                        <span class="font-bold text-primary-600 text-sm mt-0.5 block">₹{{ number_format($structure->net_salary, 2) }}</span>
                    </div>
                    <div class="text-right">
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Annual CTC</span>
                        <span class="font-bold text-slate-700 text-sm mt-0.5 block">₹{{ number_format($structure->ctc, 2) }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <span class="text-[10px] font-medium text-slate-400">Effective: {{ $structure->effective_from->format('d M Y') }}</span>
                    <a href="{{ route('payroll.salary_structures.show', $structure->id) }}" class="btn btn-primary btn-xs rounded-lg px-3 text-white font-semibold flex items-center gap-1 shadow-sm">
                        View Structure <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="py-16 text-center bg-white border border-slate-200 rounded-xl shadow-sm">
                <span class="material-symbols-outlined text-4xl text-slate-400 mb-2">account_balance_wallet</span>
                <p class="font-bold text-sm text-slate-600">No Salary Frameworks Assigned</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
