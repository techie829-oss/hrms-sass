<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('reports.index') }}" class="btn btn-ghost btn-xs btn-circle">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                </a>
                <h2 class="text-xl font-bold text-on-surface">Payroll Analytics</h2>
            </div>
            <div class="flex gap-2 items-center">
                <form method="GET" class="flex items-center gap-2">
                    <select name="month" class="select select-xs select-bordered rounded-lg text-[10px] font-bold" onchange="this.form.submit()">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endfor
                    </select>
                    <select name="year" class="select select-xs select-bordered rounded-lg text-[10px] font-bold" onchange="this.form.submit()">
                        @for($y = now()->year; $y >= now()->year - 3; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </form>
                <button onclick="window.print()" class="btn btn-sm btn-ghost border-outline-variant/20 rounded-lg font-bold text-[10px] uppercase tracking-wider gap-2">
                    <span class="material-symbols-outlined text-sm">print</span> Print
                </button>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- Top Stat Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-surface-container-lowest border border-outline-variant/10 p-5 rounded-xl shadow-sm">
                <div class="text-[9px] font-bold uppercase tracking-widest text-on-surface-variant opacity-60 mb-2">Gross Payroll</div>
                <div class="text-2xl font-black text-on-surface">₹{{ number_format($totalGross) }}</div>
                <div class="mt-2 flex items-center gap-1">
                    <div class="w-1 h-1 rounded-full bg-primary"></div>
                    <span class="text-[9px] font-bold text-primary uppercase">Total Earnings</span>
                </div>
            </div>
            <div class="bg-surface-container-lowest border border-outline-variant/10 p-5 rounded-xl shadow-sm">
                <div class="text-[9px] font-bold uppercase tracking-widest text-on-surface-variant opacity-60 mb-2">Deductions</div>
                <div class="text-2xl font-black text-error">₹{{ number_format($totalDeductions) }}</div>
                <div class="mt-2 flex items-center gap-1">
                    <div class="w-1 h-1 rounded-full bg-error"></div>
                    <span class="text-[9px] font-bold text-error uppercase">Tax + Statutory</span>
                </div>
            </div>
            <div class="bg-surface-container-lowest border border-outline-variant/10 p-5 rounded-xl shadow-sm">
                <div class="text-[9px] font-bold uppercase tracking-widest text-on-surface-variant opacity-60 mb-2">Net Payout</div>
                <div class="text-2xl font-black text-success">₹{{ number_format($totalNet) }}</div>
                <div class="mt-2 flex items-center gap-1">
                    <div class="w-1 h-1 rounded-full bg-success"></div>
                    <span class="text-[9px] font-bold text-success uppercase">Disbursed</span>
                </div>
            </div>
            <div class="bg-surface-container-lowest border border-outline-variant/10 p-5 rounded-xl shadow-sm">
                <div class="text-[9px] font-bold uppercase tracking-widest text-on-surface-variant opacity-60 mb-2">Employees Paid</div>
                <div class="text-2xl font-black text-secondary">{{ $totalEmployeesPaid }}</div>
                <div class="mt-2 flex items-center gap-1">
                    <div class="w-1 h-1 rounded-full bg-secondary"></div>
                    <span class="text-[9px] font-bold text-secondary uppercase">Headcount</span>
                </div>
            </div>
            <div class="bg-surface-container-lowest border border-outline-variant/10 p-5 rounded-xl shadow-sm">
                <div class="text-[9px] font-bold uppercase tracking-widest text-on-surface-variant opacity-60 mb-2">Avg Salary</div>
                <div class="text-2xl font-black text-accent">₹{{ number_format($avgSalary) }}</div>
                <div class="mt-2 flex items-center gap-1">
                    <span class="text-[9px] font-bold text-on-surface-variant uppercase opacity-40">Per Employee</span>
                </div>
            </div>
        </div>

        {{-- Payroll Run Status Banner --}}
        @if($payrollRun)
            <div class="flex items-center gap-4 p-4 rounded-xl border {{ $payrollRun->status === 'processed' ? 'bg-success/5 border-success/10' : 'bg-warning/5 border-warning/10' }}">
                <span class="material-symbols-outlined {{ $payrollRun->status === 'processed' ? 'text-success' : 'text-warning' }} text-xl">
                    {{ $payrollRun->status === 'processed' ? 'verified' : 'pending' }}
                </span>
                <div>
                    <p class="text-[10px] font-black text-on-surface uppercase tracking-wider">
                        {{ $payrollRun->title ?? 'Payroll Run' }} — {{ ucfirst($payrollRun->status) }}
                    </p>
                    <p class="text-[9px] font-bold text-on-surface-variant opacity-50 mt-0.5">
                        Pay Date: {{ $payrollRun->pay_date?->format('d M, Y') ?? 'Not set' }}
                        @if($payrollRun->processed_at)
                            · Processed {{ $payrollRun->processed_at->diffForHumans() }}
                        @endif
                    </p>
                </div>
            </div>
        @else
            <div class="flex items-center gap-4 p-4 rounded-xl bg-surface-container-low/30 border border-outline-variant/10">
                <span class="material-symbols-outlined text-on-surface-variant opacity-40 text-xl">event_busy</span>
                <p class="text-[10px] font-bold text-on-surface-variant opacity-50 uppercase tracking-wider">No payroll run found for {{ date('F', mktime(0,0,0,$month,1)) }} {{ $year }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Payslip Table --}}
            <div class="lg:col-span-2 bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-outline-variant/5 bg-surface-container-low/20 flex items-center justify-between">
                    <h3 class="font-bold text-[10px] uppercase tracking-wider text-on-surface opacity-80 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">receipt_long</span> Payslip Breakdown
                    </h3>
                    <span class="text-[8px] font-bold text-on-surface-variant opacity-40 uppercase">{{ $payslips->count() }} Records</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-xs w-full">
                        <thead>
                            <tr class="bg-surface-container-low/30">
                                <th class="text-[8px] font-black uppercase tracking-widest opacity-60">Employee</th>
                                <th class="text-[8px] font-black uppercase tracking-widest opacity-60">Dept</th>
                                <th class="text-[8px] font-black uppercase tracking-widest opacity-60 text-right">Gross</th>
                                <th class="text-[8px] font-black uppercase tracking-widest opacity-60 text-right">Deductions</th>
                                <th class="text-[8px] font-black uppercase tracking-widest opacity-60 text-right">Net</th>
                                <th class="text-[8px] font-black uppercase tracking-widest opacity-60 text-center">Days</th>
                                <th class="text-[8px] font-black uppercase tracking-widest opacity-60 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payslips as $slip)
                                <tr class="hover:bg-surface-container-low/20 transition-colors">
                                    <td class="text-[10px] font-bold text-on-surface">{{ $slip->employee?->full_name ?? 'N/A' }}</td>
                                    <td class="text-[10px] font-medium text-on-surface-variant opacity-60">{{ $slip->employee?->department?->name ?? '—' }}</td>
                                    <td class="text-right text-[10px] font-bold text-on-surface">₹{{ number_format($slip->gross_earnings) }}</td>
                                    <td class="text-right text-[10px] font-bold text-error">₹{{ number_format($slip->total_deductions) }}</td>
                                    <td class="text-right text-[10px] font-black text-success">₹{{ number_format($slip->net_salary) }}</td>
                                    <td class="text-center">
                                        <span class="text-[9px] font-bold text-on-surface-variant opacity-70">{{ $slip->present_days ?? 0 }}/{{ $slip->working_days ?? 0 }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-xs text-[7px] font-black px-2 py-0.5 h-auto uppercase {{ $slip->status === 'paid' ? 'badge-success text-white' : 'badge-warning' }}">
                                            {{ $slip->status ?? 'draft' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-8 text-[10px] font-bold opacity-40 italic uppercase">No payslips for this period</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($payslips->isNotEmpty())
                        <tfoot>
                            <tr class="bg-surface-container-low/40 font-black">
                                <td colspan="2" class="text-[9px] uppercase tracking-widest opacity-60">Totals</td>
                                <td class="text-right text-[10px] font-black text-on-surface">₹{{ number_format($payslips->sum('gross_earnings')) }}</td>
                                <td class="text-right text-[10px] font-black text-error">₹{{ number_format($payslips->sum('total_deductions')) }}</td>
                                <td class="text-right text-[10px] font-black text-success">₹{{ number_format($payslips->sum('net_salary')) }}</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Department Salary Distribution --}}
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-outline-variant/5 bg-surface-container-low/20">
                        <h3 class="font-bold text-[10px] uppercase tracking-wider text-on-surface opacity-80 flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">hub</span> Dept Cost Distribution
                        </h3>
                    </div>
                    <div class="p-5 space-y-4">
                        @forelse($departmentPayroll as $dept)
                            @php
                                $deptPercentage = $totalNet > 0 ? ($dept->total_net / $totalNet) * 100 : 0;
                            @endphp
                            <div>
                                <div class="flex justify-between items-center mb-1.5 px-0.5">
                                    <span class="font-bold text-[10px] text-on-surface">{{ $dept->department }}</span>
                                    <span class="text-[9px] font-black opacity-50">₹{{ number_format($dept->total_net) }} <span class="opacity-30">[{{ number_format($deptPercentage, 0) }}%]</span></span>
                                </div>
                                <div class="w-full bg-surface-container-low rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-accent h-full rounded-full transition-all duration-1000" style="width: {{ $deptPercentage }}%"></div>
                                </div>
                                <div class="flex justify-between mt-1">
                                    <span class="text-[8px] font-bold opacity-30">{{ $dept->employee_count }} employees</span>
                                    <span class="text-[8px] font-bold opacity-30">Avg ₹{{ number_format($dept->employee_count > 0 ? $dept->total_net / $dept->employee_count : 0) }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 opacity-30 italic text-[10px]">No department data available</div>
                        @endforelse
                    </div>
                </div>

                {{-- Payroll Trend (Last 6 Months) --}}
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-outline-variant/5 bg-surface-container-low/20">
                        <h3 class="font-bold text-[10px] uppercase tracking-wider text-on-surface opacity-80 flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">trending_up</span> Payroll Trend
                        </h3>
                    </div>
                    <div class="p-5 space-y-3">
                        @forelse($recentRuns as $run)
                            @php
                                $maxNet = max((float)$recentRuns->max('total_net'), 1);
                                $barWidth = ($run->total_net / $maxNet) * 100;
                            @endphp
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-[9px] font-bold text-on-surface opacity-70">{{ date('M', mktime(0,0,0,$run->month,1)) }} {{ $run->year }}</span>
                                    <span class="text-[9px] font-black text-on-surface">₹{{ number_format($run->total_net) }}</span>
                                </div>
                                <div class="w-full bg-surface-container-low rounded-full h-2 overflow-hidden">
                                    <div class="bg-gradient-to-r from-primary to-secondary h-full rounded-full transition-all duration-1000" style="width: {{ $barWidth }}%"></div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 opacity-30 italic text-[10px]">No historical data available</div>
                        @endforelse
                    </div>
                </div>

                {{-- Payroll Composition --}}
                @if($payrollRun)
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-outline-variant/5 bg-surface-container-low/20">
                        <h3 class="font-bold text-[10px] uppercase tracking-wider text-on-surface opacity-80 flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">donut_large</span> Composition
                        </h3>
                    </div>
                    <div class="p-5 space-y-3">
                        @php
                            $grossTotal = max($totalGross, 1);
                            $netPercent = round(($totalNet / $grossTotal) * 100);
                            $dedPercent = round(($totalDeductions / $grossTotal) * 100);
                        @endphp
                        <div class="flex items-center gap-3">
                            <div class="flex-1 bg-surface-container-low rounded-full h-3 overflow-hidden flex">
                                <div class="bg-success h-full transition-all" style="width: {{ $netPercent }}%"></div>
                                <div class="bg-error h-full transition-all" style="width: {{ $dedPercent }}%"></div>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <div class="flex items-center gap-1">
                                <div class="w-2 h-2 rounded-full bg-success"></div>
                                <span class="text-[8px] font-black text-on-surface-variant opacity-60 uppercase">Net {{ $netPercent }}%</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <div class="w-2 h-2 rounded-full bg-error"></div>
                                <span class="text-[8px] font-black text-on-surface-variant opacity-60 uppercase">Deductions {{ $dedPercent }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-between p-4 bg-accent/5 rounded-xl border border-accent/10">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-accent text-lg">info</span>
                <p class="text-[10px] font-bold text-accent uppercase tracking-wider italic">Financial report generated {{ now()->format('M d, Y H:i') }} — {{ date('F', mktime(0,0,0,$month,1)) }} {{ $year }} cycle</p>
            </div>
        </div>
    </div>
</x-app-layout>
