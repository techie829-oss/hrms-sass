<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="badge badge-primary badge-sm font-bold text-[10px] uppercase">{{ $run->status }}</span>
                    <h2 class="text-xl font-bold">{{ $run->title }}</h2>
                </div>
                <p class="text-xs font-medium mt-1 opacity-70">Payroll distribution for {{ \Carbon\Carbon::createFromDate($run->year, $run->month, 1)->format('F, Y') }}.</p>
            </div>
            <div class="flex gap-2 mt-4 md:mt-0">
                @if($run->status === 'draft' || $run->status === 'processing')
                    <form action="{{ route('payroll.generate', $run->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">
                            <span class="material-symbols-outlined text-base">settings_suggest</span> Generate Payslips
                        </button>
                    </form>
                @endif
                <a href="{{ route('payroll.index') }}" class="btn btn-ghost btn-sm btn-outline">
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body p-6">
                <p class="text-[9px] font-bold uppercase tracking-wider opacity-70 mb-2">Gross Distribution</p>
                <h3 class="text-xl font-bold">₹{{ number_format($run->total_gross, 2) }}</h3>
            </div>
        </div>
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body p-6">
                <p class="text-[9px] font-bold uppercase tracking-wider opacity-70 mb-2">Total Deductions</p>
                <h3 class="text-xl font-bold text-error">₹{{ number_format($run->total_deductions, 2) }}</h3>
            </div>
        </div>
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body p-6">
                <p class="text-[9px] font-bold uppercase tracking-wider opacity-70 mb-2">Net Payout</p>
                <h3 class="text-xl font-bold text-primary">₹{{ number_format($run->total_net, 2) }}</h3>
            </div>
        </div>
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body p-6">
                <p class="text-[9px] font-bold uppercase tracking-wider opacity-70 mb-2">Payment Date</p>
                <h3 class="text-xl font-bold">{{ $run->pay_date->format('M d, Y') }}</h3>
            </div>
        </div>
    </div>

    <div class="card bg-base-100 shadow-sm border border-base-200 overflow-hidden mb-12">
        <div class="p-6 border-b border-base-200 flex justify-between items-center bg-base-200/30">
            <h3 class="font-bold text-lg">Employee Individual Records</h3>
            <div class="text-[10px] font-bold uppercase tracking-wider opacity-40">Showing {{ $payslips->count() }} Records</div>
        </div>

        <x-table :headers="['Employee', 'ID / Code', 'Gross', 'Net Salary', 'Status', 'Actions']" :striped="false">
            @forelse($payslips as $payslip)
                <tr class="hover:bg-base-200/50 transition-colors border-b border-base-200">
                    <td class="py-3 px-6">
                        <div class="flex items-center gap-3">
                            <div class="avatar placeholder">
                                <div class="bg-primary/10 text-primary rounded-lg w-8 h-8 font-bold text-[10px] border border-primary/10">
                                    {{ substr($payslip->employee->first_name, 0, 1) }}{{ substr($payslip->employee->last_name, 0, 1) }}
                                </div>
                            </div>
                            <div class="font-bold text-sm">{{ $payslip->employee->full_name }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="text-[10px] font-bold uppercase tracking-wider opacity-70">{{ $payslip->payslip_number }}</div>
                    </td>
                    <td>
                        <div class="text-xs font-bold">₹{{ number_format($payslip->gross_earnings, 2) }}</div>
                    </td>
                    <td>
                        <div class="text-sm font-bold text-primary">₹{{ number_format($payslip->net_salary, 2) }}</div>
                    </td>
                    <td>
                        <span class="badge {{ $payslip->status === 'paid' ? 'badge-success' : 'badge-ghost' }} badge-sm font-bold text-[10px] uppercase">{{ $payslip->status }}</span>
                    </td>
                    <td class="text-right px-6">
                        <button class="btn btn-ghost btn-xs btn-square text-primary hover:bg-primary/10" title="View PDF">
                            <span class="material-symbols-outlined text-base">picture_as_pdf</span>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-20 text-center">
                        <div class="flex flex-col items-center gap-4 opacity-40">
                            <span class="material-symbols-outlined text-6xl">description</span>
                            <p class="font-bold text-sm">Records Not Yet Generated</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-table>
        
        @if($payslips->hasPages())
            <div class="p-8 border-t border-base-200 bg-base-200/30">
                {{ $payslips->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
