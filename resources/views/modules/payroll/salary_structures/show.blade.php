<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface uppercase tracking-tighter">Structure Details</h2>
                <p class="text-sm text-on-surface-variant font-medium mt-1">Detailed fiscal framework for {{ $salary_structure->employee->full_name }}.</p>
            </div>
            <div>
                <a href="{{ route('payroll.salary_structures.index') }}" class="btn btn-ghost border-outline-variant/20 rounded-xl font-bold text-xs uppercase tracking-widest px-6">
                    Back to Registry
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-8">
        <div class="bg-surface-container-lowest rounded-[2.5rem] border border-outline-variant/15 shadow-xl overflow-hidden p-12">
            <div class="flex justify-between items-start mb-12">
                <div class="flex items-center gap-6">
                    <div class="avatar placeholder">
                        <div class="bg-primary text-white rounded-[2rem] w-20 h-20 font-black text-2xl uppercase tracking-widest">
                            {{ substr($salary_structure->employee->first_name, 0, 1) }}{{ substr($salary_structure->employee->last_name, 0, 1) }}
                        </div>
                    </div>
                    <div>
                        <h3 class="text-3xl font-black text-on-surface uppercase tracking-tighter">{{ $salary_structure->employee->full_name }}</h3>
                        <p class="text-sm text-on-surface-variant font-bold uppercase tracking-widest opacity-60">{{ $salary_structure->employee->employee_id }} • {{ $salary_structure->employee->designation->name ?? 'Staff' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    @if($salary_structure->is_active)
                        <span class="badge badge-success text-white font-black text-[10px] px-4 py-3 uppercase tracking-widest shadow-lg">Active Contract</span>
                    @else
                        <span class="badge badge-neutral text-on-surface-variant opacity-40 font-black text-[10px] px-4 py-3 uppercase tracking-widest">Legacy Structure</span>
                    @endif
                    <p class="text-[10px] font-black uppercase tracking-widest mt-2 opacity-40">Effective: {{ $salary_structure->effective_from->format('d M Y') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="space-y-8">
                    <div>
                        <h4 class="font-black text-xs uppercase tracking-widest text-on-surface-variant opacity-40 mb-6 border-b border-outline-variant/10 pb-2">Earnings Breakdown</h4>
                        <div class="space-y-4">
                            @foreach($salary_structure->earnings as $compId => $amount)
                                <div class="flex justify-between items-center group">
                                    <span class="text-sm font-bold text-on-surface uppercase tracking-tight">{{ $components[$compId]->name ?? 'Unknown Component' }}</span>
                                    <span class="text-base font-black text-on-surface tracking-tighter">₹{{ number_format($amount, 2) }}</span>
                                </div>
                            @endforeach
                            <div class="pt-4 border-t border-outline-variant/10 flex justify-between items-center text-primary">
                                <span class="text-xs font-black uppercase tracking-widest">Gross Monthly</span>
                                <span class="text-xl font-black tracking-tighter">₹{{ number_format($salary_structure->gross_salary, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-primary/5 p-8 rounded-[2rem] border border-primary/10">
                        <h4 class="font-black text-[10px] uppercase tracking-widest text-primary mb-4">Annual Package (CTC)</h4>
                        <div class="text-4xl font-black text-primary tracking-tighter">₹{{ number_format($salary_structure->ctc, 2) }}</div>
                        <p class="text-[9px] font-bold text-primary opacity-60 uppercase tracking-widest mt-2">Total Cost to Company per Annum</p>
                    </div>
                </div>

                <div class="space-y-8">
                    <div>
                        <h4 class="font-black text-xs uppercase tracking-widest text-on-surface-variant opacity-40 mb-6 border-b border-outline-variant/10 pb-2">Deductions</h4>
                        <div class="space-y-4">
                            @foreach($salary_structure->deductions as $compId => $amount)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-bold text-on-surface-variant opacity-70 uppercase tracking-tight">{{ $components[$compId]->name ?? 'Deduction' }}</span>
                                    <span class="text-base font-black text-error/70 tracking-tighter">₹{{ number_format($amount, 2) }}</span>
                                </div>
                            @endforeach
                            @if(empty($salary_structure->deductions))
                                <p class="text-xs italic opacity-30">No active deductions</p>
                            @endif
                        </div>
                    </div>

                    <div class="bg-surface-container-high p-8 rounded-[2rem] border border-outline-variant/20 shadow-inner">
                        <h4 class="font-black text-[10px] uppercase tracking-widest text-on-surface-variant opacity-60 mb-4">Monthly take-home</h4>
                        <div class="text-4xl font-black text-on-surface tracking-tighter">₹{{ number_format($salary_structure->net_salary, 2) }}</div>
                        <p class="text-[9px] font-bold text-on-surface-variant opacity-40 uppercase tracking-widest mt-2">Net Payable Amount (Post Deductions)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
