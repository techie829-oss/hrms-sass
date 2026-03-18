<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('hr.employees.index') }}" class="btn btn-ghost btn-sm btn-square">
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-xl font-bold">{{ $employee->full_name }}</h2>
                    <p class="text-xs font-medium mt-1 opacity-70">
                        <span class="text-primary font-bold">{{ $employee->employee_id }}</span> • {{ $employee->department->name ?? 'Unassigned' }}
                    </p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('hr.employees.edit', $employee->id) }}" class="btn btn-secondary btn-sm">
                    <span class="material-symbols-outlined text-base">edit</span> Edit Profile
                </a>
                <button class="btn btn-ghost btn-sm">
                    <span class="material-symbols-outlined text-base">print</span> Print
                </button>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar: Basic Info & Actions -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Profile Card -->
            <div class="card bg-base-100 shadow-sm border border-base-200 text-center">
                <div class="card-body">
                    <div class="avatar placeholder mb-4 mx-auto">
                        <div class="bg-primary/10 text-primary rounded-2xl w-24 h-24 font-bold text-2xl">
                            {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                        </div>
                    </div>
                    <h3 class="text-lg font-bold">{{ $employee->full_name }}</h3>
                    <p class="text-xs font-bold uppercase tracking-widest opacity-60 mb-4">{{ $employee->designation ?? 'Team Member' }}</p>
                    
                    <div class="flex flex-wrap justify-center gap-2 mb-6">
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
                        <span class="badge badge-outline font-bold text-[10px] uppercase tracking-wider">{{ str_replace('_', ' ', $employee->employment_type) }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-6 mt-auto border-t border-base-200">
                        <div class="text-center">
                            <div class="text-[10px] font-bold uppercase tracking-wider mb-1 opacity-50">Joining Date</div>
                            <div class="text-xs font-bold">{{ $employee->date_of_joining?->format('d M, Y') ?? 'N/A' }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-[10px] font-bold uppercase tracking-wider mb-1 opacity-50">Experience</div>
                            <div class="text-xs font-bold">{{ $employee->date_of_joining?->diffForHumans(['syntax' => true, 'short' => true]) ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h4 class="text-[10px] font-bold uppercase tracking-widest mb-4 opacity-50">Contact Information</h4>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-base-200 flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary text-base">mail</span>
                            </div>
                            <div class="overflow-hidden">
                                <div class="text-[10px] font-bold opacity-70">Email Address</div>
                                <div class="text-xs font-medium truncate">{{ $employee->email }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-base-200 flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary text-base">call</span>
                            </div>
                            <div>
                                <div class="text-[10px] font-bold opacity-70">Phone Number</div>
                                <div class="text-xs font-medium">{{ $employee->phone ?? 'Not provided' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: Detailed Tabs -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body p-0">
                    <div role="tablist" class="tabs tabs-bordered bg-base-200/50 pt-2 px-4 rounded-t-2xl">
                        <!-- Overview Tab -->
                        <input type="radio" name="employee_tabs" role="tab" class="tab text-[10px] font-bold uppercase tracking-widest [--tab-border-color:theme(colors.primary)] [--tab-bg:theme(colors.base-100)]" aria-label="Overview" checked="checked" />
                        <div role="tabpanel" class="tab-content bg-base-100 p-8 border-none">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <section>
                                    <h5 class="text-xs font-bold text-primary mb-4 flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                                        Personal Details
                                    </h5>
                                    <div class="space-y-4">
                                        <div class="flex justify-between py-2 border-b border-base-200">
                                            <span class="text-xs font-medium opacity-70">Full Name</span>
                                            <span class="text-xs font-bold">{{ $employee->full_name }}</span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-base-200">
                                            <span class="text-xs font-medium opacity-70">Date of Birth</span>
                                            <span class="text-xs font-bold">{{ $employee->date_of_birth?->format('d M, Y') ?? 'Not set' }}</span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-base-200">
                                            <span class="text-xs font-medium opacity-70">Gender</span>
                                            <span class="text-xs font-bold">{{ $employee->gender ?? 'Not specified' }}</span>
                                        </div>
                                    </div>
                                </section>

                                <section>
                                    <h5 class="text-xs font-bold text-secondary mb-4 flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                                        Employment Info
                                    </h5>
                                    <div class="space-y-4">
                                        <div class="flex justify-between py-2 border-b border-base-200">
                                            <span class="text-xs font-medium opacity-70">Department</span>
                                            <span class="text-xs font-bold">{{ $employee->department->name ?? 'Unassigned' }}</span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-base-200">
                                            <span class="text-xs font-medium opacity-70">Basic Salary</span>
                                            <span class="text-xs font-bold">{{ number_format($employee->basic_salary ?? 0, 2) }} INR</span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-base-200">
                                            <span class="text-xs font-medium opacity-70">Manager</span>
                                            <span class="text-xs font-bold">{{ $employee->reporting_to ?? 'Direct Report' }}</span>
                                        </div>
                                    </div>
                                </section>
                            </div>

                            <!-- Company Info Summary -->
                            <div class="mt-10 p-6 bg-primary/5 rounded-xl border border-primary/10">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-primary text-2xl">info</span>
                                    </div>
                                    <div>
                                        <h6 class="text-sm font-bold">Organizational Status</h6>
                                        <p class="text-xs mt-1 leading-relaxed opacity-70">
                                            This employee is currently <span class="font-bold text-primary">{{ $employee->status }}</span> and belongs to the <span class="font-bold text-primary">{{ $employee->department->name ?? 'Core' }}</span> department. 
                                            They joined on <span class="font-bold text-primary">{{ $employee->date_of_joining?->format('F jS, Y') }}</span>.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Performance Tab -->
                        <input type="radio" name="employee_tabs" role="tab" class="tab text-[10px] font-bold uppercase tracking-widest [--tab-border-color:theme(colors.primary)] [--tab-bg:theme(colors.base-100)]" aria-label="Performance" />
                        <div role="tabpanel" class="tab-content bg-base-100 p-8 border-none">
                            <div class="space-y-8">
                                <!-- Goals Section -->
                                <section>
                                    <h5 class="text-xs font-bold text-primary mb-6 flex items-center justify-between">
                                        <span class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                                            Active Goals
                                        </span>
                                        <span class="text-[10px] opacity-60 uppercase">Total: {{ $employee->goals->count() }}</span>
                                    </h5>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @forelse($employee->goals as $goal)
                                            <div class="card bg-base-100 shadow-sm border border-base-200 p-4">
                                                <div class="flex justify-between items-start mb-2">
                                                    <h6 class="text-xs font-bold">{{ $goal->title }}</h6>
                                                    <span class="text-[10px] font-bold text-primary">{{ $goal->progress_percentage }}%</span>
                                                </div>
                                                <div class="w-full bg-base-200 rounded-full h-1.5 mb-3">
                                                    <div class="bg-primary h-1.5 rounded-full transition-all duration-500" style="width: {{ $goal->progress_percentage }}%"></div>
                                                </div>
                                                <div class="flex justify-between items-center text-[10px] font-medium opacity-70">
                                                    <span>{{ $goal->status }}</span>
                                                    <span>Ends: {{ $goal->end_date?->format('M d, Y') }}</span>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-span-2 py-10 text-center border-2 border-dashed border-base-300 rounded-xl">
                                                <p class="text-xs font-medium opacity-70">No active goals assigned.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </section>

                                <!-- Appraisals Section -->
                                <section>
                                    <h5 class="text-xs font-bold text-secondary mb-6 flex items-center justify-between">
                                        <span class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                                            Recent Appraisals
                                        </span>
                                        <span class="text-[10px] opacity-60 uppercase">Total: {{ $employee->appraisals->count() }}</span>
                                    </h5>

                                    <div class="overflow-x-auto rounded-xl border border-base-200">
                                        <table class="table table-zebra w-full text-left">
                                            <thead class="bg-base-200/50 text-[10px] font-bold uppercase tracking-wider">
                                                <tr>
                                                    <th>Review Period</th>
                                                    <th>Score</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($employee->appraisals as $appraisal)
                                                    <tr class="text-xs">
                                                        <td class="font-bold">{{ $appraisal->review_period }}</td>
                                                        <td>
                                                            @if($appraisal->score)
                                                                <span class="font-bold text-primary">{{ $appraisal->score }}</span>/100
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td class="uppercase font-bold text-[8px] tracking-widest">{{ $appraisal->status }}</td>
                                                        <td class="opacity-60">{{ $appraisal->created_at->format('M d, Y') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="py-10 text-center text-xs font-medium opacity-70">
                                                            No appraisal records found.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </section>
                            </div>
                        </div>

                        <!-- Work History Tab -->
                        <input type="radio" name="employee_tabs" role="tab" class="tab text-[10px] font-bold uppercase tracking-widest [--tab-border-color:theme(colors.primary)] [--tab-bg:theme(colors.base-100)]" aria-label="Work History" />
                        <div role="tabpanel" class="tab-content bg-base-100 p-8 border-none">
                            <div class="py-20 text-center opacity-40">
                                <span class="material-symbols-outlined text-6xl">history</span>
                                <p class="font-bold text-sm mt-4">Work history tracking coming soon.</p>
                            </div>
                        </div>

                        <!-- Documents Tab -->
                        <input type="radio" name="employee_tabs" role="tab" class="tab text-[10px] font-bold uppercase tracking-widest [--tab-border-color:theme(colors.primary)] [--tab-bg:theme(colors.base-100)]" aria-label="Documents" />
                        <div role="tabpanel" class="tab-content bg-base-100 p-8 border-none">
                            <div class="py-20 text-center opacity-40">
                                <span class="material-symbols-outlined text-6xl">description</span>
                                <p class="font-bold text-sm mt-4">Document management coming soon.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
