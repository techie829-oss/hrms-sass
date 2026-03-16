<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">Employee Directory</h2>
                <p class="text-xs font-medium mt-1 opacity-70">Manage your team and organizational structure.</p>
            </div>
            <div class="flex gap-2">
                <button class="btn btn-ghost btn-sm">
                    <span class="material-symbols-outlined text-base">filter_list</span> Filters
                </button>
                <a href="{{ route('hr.employees.create') }}" class="btn btn-primary btn-sm">
                    <span class="material-symbols-outlined text-base">person_add</span> Add Employee
                </a>
            </div>
        </div>
    </x-slot>

    <div class="card bg-base-100 shadow-sm border border-base-200 overflow-hidden">
        <div class="p-4 border-b border-base-200 bg-base-200/50">
            <div class="relative max-w-md">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 opacity-50 text-base">search</span>
                <input type="text" placeholder="Search by name, ID or email..." class="input input-sm input-bordered w-full pl-10 focus:outline-none">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead class="bg-base-200/50 text-[10px] uppercase font-bold tracking-wider">
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Joining Date</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar placeholder">
                                        <div class="bg-primary/10 text-primary rounded-lg w-10 h-10 font-bold text-xs">
                                            {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold text-sm">{{ $employee->full_name }}</div>
                                        <div class="text-[10px] font-bold uppercase tracking-wider opacity-60">{{ $employee->employee_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-sm font-medium">{{ $employee->department->name ?? 'Unassigned' }}</div>
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
                                <span class="badge {{ $statusBadge }} font-bold text-[10px] uppercase tracking-wider">{{ str_replace('_', ' ', $employee->status) }}</span>
                            </td>
                            <td>
                                <div class="text-sm font-medium">{{ $employee->date_of_joining?->format('M d, Y') ?? 'N/A' }}</div>
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('hr.employees.show', $employee->id) }}" class="btn btn-ghost btn-xs btn-square">
                                        <span class="material-symbols-outlined text-base">visibility</span>
                                    </a>
                                    <a href="{{ route('hr.employees.edit', $employee->id) }}" class="btn btn-ghost btn-xs btn-square text-secondary">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                    </a>
                                    <form action="{{ route('hr.employees.destroy', $employee->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to archive this employee?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-xs btn-square text-error">
                                            <span class="material-symbols-outlined text-base">archive</span>
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
                                    <p class="font-bold text-sm">No employees found.</p>
                                    <a href="{{ route('hr.employees.create') }}" class="btn btn-ghost btn-sm btn-outline">Add First Employee</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($employees->hasPages())
            <div class="p-4 border-t border-base-200">
                {{ $employees->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
