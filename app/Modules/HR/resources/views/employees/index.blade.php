<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-on-surface tracking-tight">Employee Directory</h2>
                <p class="text-xs font-medium mt-0.5 text-on-surface-variant">Manage your team and organizational structure.</p>
            </div>
            <div class="flex gap-2">
                <button class="btn btn-ghost btn-sm border-outline-variant/20 rounded-xl px-4">
                    <span class="material-symbols-outlined text-base">filter_list</span> Filters
                </button>
                @can('create', App\Modules\HR\Models\Employee::class)
                <a href="{{ route('hr.employees.create') }}" class="btn btn-primary btn-sm rounded-xl px-5 shadow-sm shadow-primary/20">
                    <span class="material-symbols-outlined text-base">person_add</span> Add Employee
                </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="card-crm">
        <div class="card-crm-header">
            <div class="relative max-w-md">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 opacity-50 text-base">search</span>
                <input type="text" placeholder="Search by name, ID or email..." class="input input-sm input-bordered w-full pl-10 focus:outline-none">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table-crm">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Contact Info</th>
                        <th>Department & Role</th>
                        <th>Today's Attendance</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar {{ !$employee->profile_photo ? 'placeholder' : '' }}">
                                        <div class="bg-primary/10 text-primary rounded-xl w-10 h-10 font-bold text-xs overflow-hidden">
                                            @if($employee->profile_photo)
                                                <img src="{{ asset('storage/' . $employee->profile_photo) }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold text-sm">{{ $employee->full_name }}</div>
                                        <div class="text-[10px] font-bold uppercase tracking-wider opacity-60">{{ $employee->employee_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-[11px] font-bold">
                                    <span class="material-symbols-outlined text-[12px] align-middle opacity-50 mr-1">mail</span>
                                    {{ $employee->email }}
                                </div>
                                <div class="text-[11px] font-bold opacity-70 mt-1">
                                    <span class="material-symbols-outlined text-[12px] align-middle opacity-50 mr-1">call</span>
                                    {{ $employee->phone ? ($employee->country_code . ' ' . $employee->phone) : 'No phone' }}
                                </div>
                            </td>
                            <td>
                                <div class="text-sm font-medium">{{ $employee->department->name ?? 'Unassigned' }}</div>
                                <div class="text-[10px] font-bold uppercase tracking-wider opacity-60 text-primary mt-1">
                                    {{ $employee->user?->roles->first()?->name ?? 'No System Access' }}
                                </div>
                            </td>
                            <td>
                                @if($employee->todayAttendance)
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-2 h-2 rounded-full {{ $employee->todayAttendance->check_out ? 'bg-neutral' : 'bg-success animate-pulse' }}"></div>
                                        <div class="text-[11px] font-bold">
                                            {{ $employee->todayAttendance->check_out ? 'Clocked Out' : 'Clocked In' }}
                                        </div>
                                    </div>
                                    <div class="text-[10px] font-bold opacity-50 mt-1">
                                        In: {{ \Carbon\Carbon::parse($employee->todayAttendance->check_in)->format('h:i A') }}
                                    </div>
                                @else
                                    <div class="text-[11px] font-bold opacity-50">Not Punched</div>
                                @endif
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
                            <td class="text-right">
                                <div class="flex justify-end gap-1">
                                    @can('view', $employee)
                                    <a href="{{ route('hr.employees.show', $employee->id) }}" class="btn btn-ghost btn-xs btn-square">
                                        <span class="material-symbols-outlined text-base">visibility</span>
                                    </a>
                                    @endcan
                                    @can('update', $employee)
                                    <a href="{{ route('hr.employees.edit', $employee->id) }}" class="btn btn-ghost btn-xs btn-square text-secondary">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                    </a>
                                    @endcan
                                    @can('delete', $employee)
                                    <form action="{{ route('hr.employees.destroy', $employee->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to archive this employee?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-xs btn-square text-error">
                                            <span class="material-symbols-outlined text-base">archive</span>
                                        </button>
                                    </form>
                                    @endcan
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
            <div class="p-4 border-t border-outline-variant/10 bg-surface-container-lowest">
                {{ $employees->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
