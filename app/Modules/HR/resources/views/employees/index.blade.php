<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-on-surface tracking-tight">Employee Directory</h2>
                <p class="text-xs font-medium mt-0.5 text-on-surface-variant">Manage your team and organizational structure.</p>
            </div>
            <div class="flex gap-2">
                <button onclick="toggleFilterPanel()" class="btn btn-ghost btn-sm border border-slate-200 rounded-xl px-4 flex items-center gap-1 bg-white hover:bg-slate-50">
                    <span class="material-symbols-outlined text-base text-slate-500">filter_list</span> Filters
                </button>
                @can('create', App\Modules\HR\Models\Employee::class)
                <a href="{{ route('hr.employees.create') }}" class="btn btn-primary btn-sm rounded-xl px-5 shadow-sm shadow-primary/20">
                    <span class="material-symbols-outlined text-base">person_add</span> Add Employee
                </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
        <!-- Search Bar Header -->
        <div class="px-6 py-4 border-b border-slate-200 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 bg-slate-50/50 rounded-t-xl">
            <form action="{{ route('hr.employees.index') }}" method="GET" class="relative max-w-md w-full">
                @if(request('department_id'))
                    <input type="hidden" name="department_id" value="{{ request('department_id') }}">
                @endif
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @if(request('employment_type'))
                    <input type="hidden" name="employment_type" value="{{ request('employment_type') }}">
                @endif

                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 opacity-50 text-base">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, ID or email..." class="input input-sm border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl w-full pl-10 bg-white placeholder-slate-400/75 transition-all shadow-sm">
            </form>

            @if(request()->anyFilled(['department_id', 'status', 'employment_type', 'search']))
                <div class="flex flex-wrap gap-1.5 items-center">
                    <span class="text-[10px] font-bold uppercase tracking-wider opacity-40 mr-1">Active Filters:</span>
                    @if(request('search'))
                        <span class="badge badge-primary bg-primary/10 text-primary border border-primary/20 text-[10px] font-bold py-2 rounded-lg">"{{ request('search') }}"</span>
                    @endif
                    @if(request('department_id'))
                        <span class="badge bg-slate-100 text-slate-700 border border-slate-200 text-[10px] font-bold py-2 rounded-lg">{{ $departments->firstWhere('id', request('department_id'))->name ?? 'Dept' }}</span>
                    @endif
                    @if(request('status'))
                        <span class="badge bg-slate-100 text-slate-700 border border-slate-200 text-[10px] font-bold py-2 rounded-lg">{{ ucfirst(str_replace('_', ' ', request('status'))) }}</span>
                    @endif
                    @if(request('employment_type'))
                        <span class="badge bg-slate-100 text-slate-700 border border-slate-200 text-[10px] font-bold py-2 rounded-lg">{{ ucfirst(str_replace('_', ' ', request('employment_type'))) }}</span>
                    @endif
                </div>
            @endif
        </div>

        <!-- Collapsible Filter Panel -->
        <div id="filterPanel" class="hidden border-b border-slate-200 bg-slate-50/30 px-6 py-4 transition-all duration-300">
            <form action="{{ route('hr.employees.index') }}" method="GET" id="searchFilterForm">
                <input type="hidden" name="search" value="{{ request('search') }}">
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="form-control">
                        <label class="label text-[10px] font-bold uppercase opacity-60">Department</label>
                        <select name="department_id" onchange="document.getElementById('searchFilterForm').submit()" class="select select-sm border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl w-full text-xs bg-white transition-all shadow-sm">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label text-[10px] font-bold uppercase opacity-60">Status</label>
                        <select name="status" onchange="document.getElementById('searchFilterForm').submit()" class="select select-sm border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl w-full text-xs bg-white transition-all shadow-sm">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="on_leave" {{ request('status') === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                            <option value="terminated" {{ request('status') === 'terminated' ? 'selected' : '' }}>Terminated</option>
                            <option value="resigned" {{ request('status') === 'resigned' ? 'selected' : '' }}>Resigned</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label text-[10px] font-bold uppercase opacity-60">Employment Type</label>
                        <select name="employment_type" onchange="document.getElementById('searchFilterForm').submit()" class="select select-sm border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl w-full text-xs bg-white transition-all shadow-sm">
                            <option value="">All Types</option>
                            <option value="full_time" {{ request('employment_type') === 'full_time' ? 'selected' : '' }}>Full Time</option>
                            <option value="part_time" {{ request('employment_type') === 'part_time' ? 'selected' : '' }}>Part Time</option>
                            <option value="contract" {{ request('employment_type') === 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="intern" {{ request('employment_type') === 'intern' ? 'selected' : '' }}>Intern</option>
                        </select>
                    </div>
                </div>

                @if(request()->anyFilled(['department_id', 'status', 'employment_type', 'search']))
                <div class="flex justify-end mt-4">
                    <a href="{{ route('hr.employees.index') }}" class="btn btn-ghost btn-xs text-xs text-error font-bold uppercase tracking-wider flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">clear_all</span> Clear Filters
                    </a>
                </div>
                @endif
            </form>
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
                            <td colspan="6" class="py-20 text-center">
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

    @push('scripts')
    <script>
        function toggleFilterPanel() {
            const panel = document.getElementById('filterPanel');
            if (panel) {
                panel.classList.toggle('hidden');
            }
        }

        // Auto-open filter panel if any filter is already active
        document.addEventListener('DOMContentLoaded', function() {
            const hasFilters = {{ request()->anyFilled(['department_id', 'status', 'employment_type']) ? 'true' : 'false' }};
            if (hasFilters) {
                const panel = document.getElementById('filterPanel');
                if (panel) {
                    panel.classList.remove('hidden');
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
