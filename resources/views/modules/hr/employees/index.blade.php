<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface">Employee Directory</h2>
                <p class="text-sm text-on-surface-variant font-medium mt-1">Manage and curate your organizational talent.</p>
            </div>
            <div class="flex gap-3">
                <button class="btn btn-ghost border-outline-variant/20 rounded-xl font-bold text-xs uppercase tracking-widest px-6">
                    <span class="material-symbols-outlined text-lg">filter_list</span> Filters
                </button>
                <a href="{{ route('hr.employees.create') }}" class="btn btn-primary primary-gradient border-none rounded-xl font-bold text-xs uppercase tracking-widest px-6 shadow-lg">
                    <span class="material-symbols-outlined text-lg">person_add</span> Add Employee
                </a>
            </div>
        </div>
    </x-slot>

    <div class="bg-surface-container-lowest rounded-[2.5rem] border border-outline-variant/15 premium-shadow overflow-hidden">
        <div class="p-6 border-b border-outline-variant/10 bg-surface-container-low/30">
            <div class="relative max-w-md">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">search</span>
                <input type="text" placeholder="Search by name, ID or email..." class="w-full pl-12 pr-4 py-3 bg-surface-container-lowest border border-outline-variant/20 rounded-2xl text-sm focus:border-primary focus:ring-0 transition-all font-medium">
            </div>
        </div>

        <x-table :headers="['Employee', 'Department', 'Status', 'Joining Date', 'Actions']" :striped="false">
            @forelse($employees as $employee)
                <tr class="hover:bg-primary/5 transition-colors border-b border-outline-variant/5">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-4">
                            <div class="avatar placeholder">
                                <div class="bg-primary/10 text-primary rounded-2xl w-12 h-12 font-black text-sm premium-shadow border border-primary/5">
                                    {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                                </div>
                            </div>
                            <div>
                                <div class="font-black text-on-surface text-base">{{ $employee->full_name }}</div>
                                <div class="text-xs text-on-surface-variant font-bold uppercase tracking-tighter opacity-70">{{ $employee->employee_id }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="font-bold text-on-surface">{{ $employee->department->name ?? 'Unassigned' }}</div>
                        <div class="text-[10px] text-on-surface-variant font-black uppercase tracking-widest opacity-60">Architectural Dept</div>
                    </td>
                    <td>
                        @php
                            $statusClasses = [
                                'active' => 'badge-success',
                                'on_leave' => 'badge-warning',
                                'inactive' => 'badge-error',
                                'terminated' => 'badge-neutral',
                                'resigned' => 'badge-ghost',
                            ];
                            $statusBadge = $statusClasses[$employee->status] ?? 'badge-ghost';
                        @endphp
                        <span class="badge {{ $statusBadge }} text-white font-black text-[9px] px-3 py-2 uppercase tracking-widest">{{ str_replace('_', ' ', $employee->status) }}</span>
                    </td>
                    <td>
                        <div class="text-sm font-bold text-on-surface">{{ $employee->date_of_joining->format('M d, Y') }}</div>
                        <div class="text-[10px] text-on-surface-variant font-medium">Joined {{ $employee->date_of_joining->diffForHumans() }}</div>
                    </td>
                    <td class="text-right px-6">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('hr.employees.show', $employee->id) }}" class="btn btn-ghost btn-sm btn-square rounded-xl hover:bg-primary/10 hover:text-primary">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                            </a>
                            <a href="{{ route('hr.employees.edit', $employee->id) }}" class="btn btn-ghost btn-sm btn-square rounded-xl hover:bg-secondary/10 hover:text-secondary">
                                <span class="material-symbols-outlined text-xl">edit</span>
                            </a>
                            <form action="{{ route('hr.employees.destroy', $employee->id) }}" method="POST" class="inline" onsubmit="return confirm('Archive this architect? This will remove them from the active directory.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-ghost btn-sm btn-square rounded-xl hover:bg-error/10 hover:text-error">
                                    <span class="material-symbols-outlined text-xl">archive</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-20 text-center">
                        <div class="flex flex-col items-center gap-4 opacity-40">
                            <span class="material-symbols-outlined text-6xl">person_off</span>
                            <p class="font-headline font-bold text-lg">No talent curated yet.</p>
                            <a href="{{ route('hr.employees.create') }}" class="btn btn-sm btn-outline border-outline-variant/30 rounded-lg">Begin Onboarding</a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if($employees->hasPages())
            <div class="p-6 bg-surface-container-low/30 border-t border-outline-variant/10">
                {{ $employees->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
