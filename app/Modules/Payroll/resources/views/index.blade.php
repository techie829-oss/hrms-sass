@php
use App\Core\Constants\PermissionConstants;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-on-surface tracking-tight">Payroll Dashboard</h2>
                <p class="text-xs font-medium mt-0.5 text-on-surface-variant">Manage payroll cycles and salary distributions.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                @can(PermissionConstants::MANAGE_PAYROLL)
                <a href="{{ route('payroll.components.index') }}" class="btn btn-ghost btn-sm btn-outline">
                    <span class="material-symbols-outlined text-base">settings</span> Components
                </a>
                <a href="{{ route('payroll.create') }}" class="btn btn-primary btn-sm rounded-xl px-5 shadow-sm shadow-primary/20">
                    <span class="material-symbols-outlined text-base">add</span> New Payroll Run
                </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
            <div class="p-6">
                <p class="text-[10px] font-bold uppercase tracking-wider opacity-70 mb-2">Total Gross Disbursed</p>
                <h3 class="text-2xl font-bold">₹{{ number_format($runs->sum('total_gross'), 2) }}</h3>
            </div>
        </div>
        
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
            <div class="p-6">
                <p class="text-[10px] font-bold uppercase tracking-wider opacity-70 mb-2">Total Employees Paid</p>
                <h3 class="text-2xl font-bold">{{ $runs->sum('total_employees') }}</h3>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
            <div class="p-6">
                <p class="text-[10px] font-bold uppercase tracking-wider opacity-70 mb-2">Net Distribution</p>
                <h3 class="text-2xl font-bold text-primary">₹{{ number_format($runs->sum('total_net'), 2) }}</h3>
            </div>
        </div>
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden lg:block table-crm">
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
                        @can('view', $run)
                        <a href="{{ route('payroll.show', $run->id) }}" class="btn btn-ghost btn-sm font-bold text-[10px] uppercase tracking-widest text-primary hover:bg-primary/10">
                            Review Details <span class="material-symbols-outlined text-lg">arrow_forward</span>
                        </a>
                        @endcan
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

    {{-- Mobile Card Stack View --}}
    <div class="lg:hidden space-y-4">
        @forelse($runs as $run)
            @php
                $mobileStatusClasses = [
                    'draft' => 'bg-slate-100 text-slate-700 border-slate-200/60',
                    'processing' => 'bg-amber-50 text-amber-700 border-amber-200/60',
                    'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                    'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200/60',
                ];
                $mobileStatusBadge = $mobileStatusClasses[$run->status] ?? 'bg-slate-100 text-slate-700 border-slate-200/60';
            @endphp
            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm space-y-3">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <div class="font-bold text-sm text-slate-800">{{ $run->title }}</div>
                        <div class="text-[10px] font-semibold text-slate-400 mt-0.5 uppercase tracking-wider">
                            {{ \Carbon\Carbon::createFromDate($run->year, $run->month, 1)->format('F, Y') }} • ID: {{ $run->id }}
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold border {{ $mobileStatusBadge }} uppercase shrink-0">
                        {{ $run->status }}
                    </span>
                </div>

                <div class="bg-slate-50/80 rounded-xl p-3 grid grid-cols-2 gap-3 border border-slate-100 text-xs">
                    <div>
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Employees Paid</span>
                        <span class="font-bold text-slate-700 text-sm mt-0.5 block">{{ $run->total_employees }} Employees</span>
                    </div>
                    <div class="text-right">
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Net Distribution</span>
                        <span class="font-bold text-primary-600 text-sm mt-0.5 block">₹{{ number_format($run->total_net, 2) }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <span class="text-[10px] font-medium text-slate-400">Gross: ₹{{ number_format($run->total_gross, 2) }}</span>
                    @can('view', $run)
                    <a href="{{ route('payroll.show', $run->id) }}" class="btn btn-primary btn-xs rounded-lg px-3 text-white font-semibold flex items-center gap-1 shadow-sm">
                        Review Details <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                    @endcan
                </div>
            </div>
        @empty
            <div class="py-16 text-center bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="w-14 h-14 rounded-xl bg-slate-100 border border-slate-200/60 flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-3xl text-slate-400">wallet</span>
                </div>
                <p class="font-bold text-sm text-slate-600">No payroll runs found.</p>
            </div>
        @endforelse

        @if($runs->hasPages())
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                {{ $runs->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
