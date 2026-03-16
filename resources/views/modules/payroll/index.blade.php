<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">Payroll Dashboard</h2>
                <p class="text-xs font-medium mt-1 opacity-70">Manage payroll cycles and salary distributions.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('payroll.components.index') }}" class="btn btn-ghost btn-sm btn-outline">
                    <span class="material-symbols-outlined text-base">settings</span> Components
                </a>
                <a href="{{ route('payroll.create') }}" class="btn btn-primary btn-sm">
                    <span class="material-symbols-outlined text-base">add</span> New Payroll Run
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body p-6">
                <p class="text-[10px] font-bold uppercase tracking-wider opacity-70 mb-2">Total Gross Disbursed</p>
                <h3 class="text-2xl font-bold">₹{{ number_format($runs->sum('total_gross'), 2) }}</h3>
            </div>
        </div>
        
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body p-6">
                <p class="text-[10px] font-bold uppercase tracking-wider opacity-70 mb-2">Total Employees Paid</p>
                <h3 class="text-2xl font-bold">{{ $runs->sum('total_employees') }}</h3>
            </div>
        </div>

        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body p-6">
                <p class="text-[10px] font-bold uppercase tracking-wider opacity-70 mb-2">Net Distribution</p>
                <h3 class="text-2xl font-bold text-primary">₹{{ number_format($runs->sum('total_net'), 2) }}</h3>
            </div>
        </div>
    </div>

    <div class="card bg-base-100 shadow-sm border border-base-200 overflow-hidden">
        <x-table :headers="['Payroll Run', 'Period', 'Status', 'Employees', 'Net Salary', 'Actions']" :striped="false">
            @forelse($runs as $run)
                <tr class="hover:bg-base-200/50 transition-colors border-b border-base-200">
                    <td class="py-4 px-6">
                        <div>
                            <div class="font-bold text-sm">{{ $run->title }}</div>
                            <div class="text-[10px] font-bold uppercase tracking-widest mt-0.5 opacity-60">ID: {{ $run->id }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="text-sm font-bold">{{ \Carbon\Carbon::createFromDate($run->year, $run->month, 1)->format('F, Y') }}</div>
                    </td>
                    <td>
                        @php
                            $statusClasses = [
                                'draft' => 'badge-ghost',
                                'processing' => 'badge-warning',
                                'completed' => 'badge-success',
                                'cancelled' => 'badge-error',
                            ];
                            $statusBadge = $statusClasses[$run->status] ?? 'badge-ghost';
                        @endphp
                        <span class="badge {{ $statusBadge }} badge-sm font-bold text-[10px] uppercase">{{ $run->status }}</span>
                    </td>
                    <td>
                        <div class="text-sm font-medium">{{ $run->total_employees }} Employees</div>
                    </td>
                    <td>
                        <div class="text-sm font-bold text-primary">₹{{ number_format($run->total_net, 2) }}</div>
                    </td>
                    <td class="text-right px-8">
                        <a href="{{ route('payroll.show', $run->id) }}" class="btn btn-ghost btn-sm font-bold text-[10px] uppercase tracking-widest text-primary hover:bg-primary/10">
                            Review Details <span class="material-symbols-outlined text-lg">arrow_forward</span>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-20 text-center">
                        <div class="flex flex-col items-center gap-4 opacity-40">
                            <span class="material-symbols-outlined text-6xl">wallet</span>
                            <div>
                                <p class="font-bold text-sm">No payroll runs found.</p>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if($runs->hasPages())
            <div class="p-8 bg-base-200/30 border-t border-base-200">
                {{ $runs->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
