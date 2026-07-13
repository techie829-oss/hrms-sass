<x-app-layout>
    @section('page-title', 'Employee Directory')
    @section('page-description', 'Manage your team and organizational structure.')

    <div class="space-y-6 pb-20 md:pb-6">
        <!-- Sleek Top Breadcrumb -->
        <div class="flex items-center text-xs font-semibold text-gray-500 space-x-2">
            <a href="{{ route('tenant.dashboard') }}" class="flex items-center hover:text-primary-600 transition-colors">
                <span class="material-symbols-outlined text-[16px] mr-1">home</span>
                Dashboard
            </a>
            <span class="text-gray-300">/</span>
            <span class="text-gray-800 font-bold">Employee Directory</span>
        </div>

        <!-- Sklops Reference Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Total Employees -->
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">TOTAL EMPLOYEES</div>
                    <div class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-1">{{ $stats['total'] ?? $employees->total() }}</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">groups</span>
                </div>
            </div>

            <!-- Active Employees -->
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider">ACTIVE</div>
                    <div class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-1">{{ $stats['active'] ?? 0 }}</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">check_circle</span>
                </div>
            </div>

            <!-- On Leave -->
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-bold text-amber-600 uppercase tracking-wider">ON LEAVE</div>
                    <div class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-1">{{ $stats['on_leave'] ?? 0 }}</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">schedule</span>
                </div>
            </div>
        </div>

        <!-- Main Directory Card Box -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <!-- Card Header -->
            <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-white">
                <div class="flex items-center gap-2.5">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900">Employee Staff</h3>
                    <span class="bg-gray-100 text-gray-700 text-xs font-bold px-2.5 py-0.5 rounded-full border border-gray-200">{{ $employees->total() }}</span>
                </div>

                @can('create', App\Modules\HR\Models\Employee::class)
                <a href="{{ route('hr.employees.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-all">
                    <span class="material-symbols-outlined text-[18px]">add</span> Add Employee
                </a>
                @endcan
            </div>

            <!-- Inline Filter & Search Form (Matches Screenshot 1 EXACTLY) -->
            <div class="p-4 sm:p-5 border-b border-gray-100 bg-gray-50/50">
                <form action="{{ route('hr.employees.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                    <!-- Search input -->
                    <div class="lg:col-span-4 relative">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, ID, email..." class="w-full pl-10 pr-4 py-2 text-xs sm:text-sm border border-gray-200 rounded-xl bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none">
                    </div>

                    <!-- Department filter -->
                    <div class="lg:col-span-3">
                        <select name="department_id" class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-200 rounded-xl bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status filter -->
                    <div class="lg:col-span-2">
                        <select name="status" class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-200 rounded-xl bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="on_leave" {{ request('status') === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                            <option value="terminated" {{ request('status') === 'terminated' ? 'selected' : '' }}>Terminated</option>
                        </select>
                    </div>

                    <!-- Employment Type filter -->
                    <div class="lg:col-span-2">
                        <select name="employment_type" class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-200 rounded-xl bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none">
                            <option value="">All Types</option>
                            <option value="full_time" {{ request('employment_type') === 'full_time' ? 'selected' : '' }}>Full Time</option>
                            <option value="part_time" {{ request('employment_type') === 'part_time' ? 'selected' : '' }}>Part Time</option>
                            <option value="contract" {{ request('employment_type') === 'contract' ? 'selected' : '' }}>Contract</option>
                        </select>
                    </div>

                    <!-- Filter Button -->
                    <div class="lg:col-span-1 flex gap-1.5">
                        <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs sm:text-sm py-2 px-3 rounded-xl transition-all shadow-sm">
                            Filter
                        </button>
                        @if(request()->anyFilled(['department_id', 'status', 'employment_type', 'search']))
                        <a href="{{ route('hr.employees.index') }}" title="Clear Filters" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-2.5 py-2 rounded-xl flex items-center justify-center transition-colors">
                            <span class="material-symbols-outlined text-[18px]">close</span>
                        </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Desktop Table View (hidden on mobile, visible on md+) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/75 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                            <th class="py-3 px-5">Employee</th>
                            <th class="py-3 px-5">Department & Role</th>
                            <th class="py-3 px-5">Today's Attendance</th>
                            <th class="py-3 px-5">Status</th>
                            <th class="py-3 px-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($employees as $employee)
                            @php
                                $colors = ['bg-blue-600', 'bg-indigo-600', 'bg-violet-600', 'bg-emerald-600', 'bg-cyan-600'];
                                $avatarColor = $colors[$employee->id % count($colors)];
                            @endphp
                            <tr class="hover:bg-gray-50/75 transition-colors">
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3.5">
                                        <!-- Vibrant Circular Avatar -->
                                        <div class="w-10 h-10 rounded-full {{ $avatarColor }} text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-sm overflow-hidden">
                                            @if($employee->profile_photo)
                                                <img src="{{ asset('storage/' . $employee->profile_photo) }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 text-sm">{{ $employee->full_name }}</div>
                                            <div class="text-[11px] text-gray-500 font-medium">ID: {{ $employee->employee_id }}</div>
                                            <!-- Blue Email Link exactly like Screenshot 1 -->
                                            <a href="mailto:{{ $employee->email }}" class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800 hover:underline font-medium mt-0.5">
                                                <span class="material-symbols-outlined text-[14px]">mail</span>
                                                {{ $employee->email }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-5">
                                    <div class="font-bold text-gray-800 text-sm">{{ $employee->department->name ?? 'Unassigned' }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5 font-medium">
                                        {{ $employee->designation->name ?? ($employee->user?->roles->first()?->name ?? 'Staff') }}
                                    </div>
                                </td>
                                <td class="py-4 px-5">
                                    @if($employee->todayAttendance)
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-2 h-2 rounded-full {{ $employee->todayAttendance->check_out ? 'bg-gray-400' : 'bg-emerald-500 animate-pulse' }}"></div>
                                            <span class="text-xs font-semibold text-gray-700">
                                                {{ $employee->todayAttendance->check_out ? 'Clocked Out' : 'Clocked In' }}
                                            </span>
                                        </div>
                                        <div class="text-[11px] text-gray-400 mt-0.5">
                                            In: {{ \Carbon\Carbon::parse($employee->todayAttendance->check_in)->format('h:i A') }}
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 font-medium">Not Punched</span>
                                    @endif
                                </td>
                                <td class="py-4 px-5">
                                    @php
                                        $statusClasses = [
                                            'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'on_leave' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'inactive' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        ];
                                        $statusClass = $statusClasses[$employee->status] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $statusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $employee->status)) }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        @can('view', $employee)
                                        <a href="{{ route('hr.employees.show', $employee->id) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View Details">
                                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                                        </a>
                                        @endcan
                                        @can('update', $employee)
                                        <a href="{{ route('hr.employees.edit', $employee->id) }}" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit Employee">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center text-gray-400">
                                    <span class="material-symbols-outlined text-4xl block mb-2">person_off</span>
                                    <p class="font-semibold text-sm">No employees found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Stacked Card List (visible only on mobile md:hidden, Matches Screenshot 1 PERFECTLY) -->
            <div class="md:hidden divide-y divide-gray-100">
                @forelse($employees as $employee)
                    @php
                        $colors = ['bg-blue-600', 'bg-indigo-600', 'bg-violet-600', 'bg-emerald-600', 'bg-cyan-600'];
                        $avatarColor = $colors[$employee->id % count($colors)];
                        $statusClasses = [
                            'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'on_leave' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'inactive' => 'bg-rose-50 text-rose-700 border-rose-200',
                        ];
                        $statusClass = $statusClasses[$employee->status] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                    @endphp
                    <div class="p-4 space-y-3 hover:bg-gray-50/50 transition-colors">
                        <!-- Top Row: Avatar + Name + ID + Status -->
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full {{ $avatarColor }} text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-sm overflow-hidden">
                                    @if($employee->profile_photo)
                                        <img src="{{ asset('storage/' . $employee->profile_photo) }}" alt="" class="w-full h-full object-cover">
                                    @else
                                        {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('hr.employees.show', $employee->id) }}" class="font-bold text-gray-900 text-sm hover:text-blue-600 block">{{ $employee->full_name }}</a>
                                    <span class="text-[11px] text-gray-500 font-medium">ID: {{ $employee->employee_id }}</span>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border shrink-0 {{ $statusClass }}">
                                {{ ucfirst(str_replace('_', ' ', $employee->status)) }}
                            </span>
                        </div>

                        <!-- Email & Contact info -->
                        <div class="flex flex-col gap-1 pl-13">
                            <a href="mailto:{{ $employee->email }}" class="inline-flex items-center gap-1.5 text-xs text-blue-600 hover:underline font-medium">
                                <span class="material-symbols-outlined text-[15px]">mail</span>
                                {{ $employee->email }}
                            </a>
                            @if($employee->phone)
                            <div class="inline-flex items-center gap-1.5 text-xs text-gray-500">
                                <span class="material-symbols-outlined text-[15px]">call</span>
                                {{ str_starts_with($employee->phone, '+') ? $employee->phone : ($employee->country_code . ' ' . $employee->phone) }}
                            </div>
                            @endif
                        </div>

                        <!-- Bottom Row: Department/Role + Action button -->
                        <div class="flex items-center justify-between pt-2 border-t border-gray-100 text-xs">
                            <div>
                                <span class="font-bold text-gray-800">{{ $employee->department->name ?? 'Unassigned' }}</span>
                                <span class="text-gray-400 mx-1">•</span>
                                <span class="text-gray-500">{{ $employee->designation->name ?? 'Staff' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                @can('view', $employee)
                                <a href="{{ route('hr.employees.show', $employee->id) }}" class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors" title="View">
                                    <span class="material-symbols-outlined text-[16px]">visibility</span>
                                </a>
                                @endcan
                                @can('update', $employee)
                                <a href="{{ route('hr.employees.edit', $employee->id) }}" class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-gray-200 transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-gray-400">
                        <span class="material-symbols-outlined text-4xl block mb-2">person_off</span>
                        <p class="font-semibold text-sm">No employees found.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($employees->hasPages())
                <div class="p-4 border-t border-gray-100 bg-gray-50">
                    {{ $employees->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
