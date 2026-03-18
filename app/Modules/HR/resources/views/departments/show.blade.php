<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('hr.departments.index') }}" class="btn btn-sm btn-ghost btn-square rounded-lg border border-outline-variant/10">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                </a>
                <h2 class="text-xl font-bold text-on-surface">{{ $department->name }}</h2>
                <span class="badge badge-primary border-none rounded-md font-bold text-[9px] uppercase tracking-wider px-2 h-5">{{ $department->code }}</span>
            </div>
            <div class="flex gap-2">
                <button onclick="document.getElementById('edit_dept_{{ $department->id }}').showModal()" class="btn btn-sm btn-ghost border-outline-variant/10 rounded-lg font-bold text-[10px] uppercase tracking-wider">
                    <span class="material-symbols-outlined text-sm">edit</span> Edit
                </button>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Stats Row (Matches Dashboard) -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/10 shadow-sm transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-on-surface-variant">Total Staff</span>
                    <span class="material-symbols-outlined text-primary text-base">groups</span>
                </div>
                <div class="text-2xl font-bold text-on-surface">{{ $department->employees->count() }}</div>
                <div class="text-[9px] font-bold text-on-surface-variant mt-1 italic opacity-70">Active members</div>
            </div>

            <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/10 shadow-sm transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-on-surface-variant">Avg Tenure</span>
                    <span class="material-symbols-outlined text-secondary text-base">history</span>
                </div>
                <div class="text-2xl font-bold text-on-surface">1.4 <span class="text-[10px] opacity-40">yrs</span></div>
                <div class="text-[9px] font-bold text-on-surface-variant mt-1 italic opacity-70">Calculated</div>
            </div>

            <div class="md:col-span-2 lg:col-span-3 bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/10 shadow-sm relative overflow-hidden transition-all">
                <div class="relative z-10">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-on-surface-variant">Description</span>
                    <p class="text-[11px] font-medium text-on-surface mt-2 leading-relaxed opacity-70">
                        {{ $department->description ?? 'No specific mission statement or description defined for this department.' }}
                    </p>
                </div>
                <span class="material-symbols-outlined absolute -right-3 -bottom-3 text-5xl opacity-5 text-on-surface">account_tree</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left Side: Member List (Matches Dashboard Table Style) -->
            <div class="lg:col-span-8">
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-outline-variant/5 flex items-center justify-between">
                        <h3 class="font-bold text-xs uppercase tracking-wider text-on-surface">Member Directory</h3>
                        <div class="flex items-center gap-2">
                            <span class="text-[9px] font-bold text-on-surface-variant uppercase tracking-wider">{{ $department->employees->count() }} Members Found</span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-xs table-zebra w-full text-[11px]">
                            <thead>
                                <tr class="text-on-surface-variant/70 border-b border-outline-variant/5">
                                    <th class="py-3 px-5">Name</th>
                                    <th>Designation</th>
                                    <th>Status</th>
                                    <th class="text-right pr-5">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="font-medium">
                                @forelse($department->employees as $employee)
                                <tr class="hover:bg-primary/5 transition-colors border-b border-outline-variant/5">
                                    <td class="py-3 px-5">
                                        <div class="flex items-center gap-3">
                                            <div class="avatar placeholder">
                                                <div class="bg-primary/10 text-primary rounded-lg w-8 h-8 font-bold text-[9px]">
                                                    {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-bold text-on-surface">{{ $employee->full_name }}</div>
                                                <div class="text-[9px] opacity-60">{{ $employee->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $employee->designation->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($employee->status === 'active')
                                            <span class="badge badge-success badge-sm text-[8px] font-bold text-white px-2 py-0.5 h-auto">ACTIVE</span>
                                        @else
                                            <span class="badge badge-neutral badge-sm text-[8px] font-bold text-white px-2 py-0.5 h-auto uppercase">{{ $employee->status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right pr-5">
                                        <a href="{{ route('hr.employees.show', $employee->id) }}" class="btn btn-ghost btn-xs rounded-md">
                                            <span class="material-symbols-outlined text-sm">visibility</span>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-on-surface-variant opacity-70 italic">No employees assigned.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Side: Recent Activity/Info -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm transition-all">
                    <h4 class="text-[9px] font-bold uppercase tracking-wider mb-6 text-on-surface-variant">Department Info</h4>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between border-b border-outline-variant/5 pb-3">
                            <span class="text-[10px] text-on-surface-variant font-medium">Functional Code</span>
                            <span class="text-[10px] font-bold text-on-surface">{{ $department->code }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-outline-variant/5 pb-3">
                            <span class="text-[10px] text-on-surface-variant font-medium">Founded Date</span>
                            <span class="text-[10px] font-bold text-on-surface">{{ $department->created_at->format('d M, Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] text-on-surface-variant font-medium">Head of Dept.</span>
                            <span class="text-[10px] font-bold text-primary italic uppercase tracking-wider">Unassigned</span>
                        </div>
                    </div>
                </div>

                <!-- Simple hiring widget style -->
                <div class="bg-primary p-6 rounded-xl shadow-sm relative overflow-hidden transition-all">
                    <div class="relative z-10">
                        <span class="text-[9px] font-bold uppercase tracking-wider text-white/80">Recruitment</span>
                        <div class="text-xl font-bold text-white mt-1 italic uppercase tracking-tighter">Growth Status</div>
                        <p class="text-[9px] text-white/70 font-medium mt-1 leading-relaxed">System is ready for new candidate integrations in {{ $department->name }}.</p>
                    </div>
                    <span class="material-symbols-outlined absolute -right-3 -bottom-3 text-5xl opacity-10 text-white">trending_up</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Department Modal (Same as Index for consistency) --}}
    <dialog id="edit_dept_{{ $department->id }}" class="modal">
        <div class="modal-box max-w-xl bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-2xl p-0 overflow-hidden">
            <div class="p-6 border-b border-outline-variant/5 flex items-center justify-between bg-surface-container-low/30">
                <div>
                    <h3 class="font-bold text-xs uppercase tracking-wider text-on-surface">Edit Department</h3>
                    <p class="text-[9px] font-bold opacity-50 uppercase tracking-widest mt-0.5">{{ $department->name }}</p>
                </div>
                <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
            </div>
            
            <form action="{{ route('hr.departments.update', $department->id) }}" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase tracking-wider opacity-60">Department Name</span></label>
                        <input name="name" type="text" class="input input-sm input-bordered focus:input-primary rounded-lg text-xs" required value="{{ $department->name }}" />
                    </div>
                    <div class="form-control">
                        <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase tracking-wider opacity-60">Dept Code</span></label>
                        <input name="code" type="text" class="input input-sm input-bordered focus:input-primary rounded-lg text-xs uppercase" required value="{{ $department->code }}" />
                    </div>
                </div>
                <div class="form-control">
                    <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase tracking-wider opacity-60">Description</span></label>
                    <textarea name="description" class="textarea textarea-bordered focus:textarea-primary rounded-lg text-xs" rows="3">{{ $department->description }}</textarea>
                </div>
                <div class="pt-2">
                    <button type="submit" class="btn btn-primary btn-sm w-full rounded-lg font-bold text-[10px] uppercase tracking-wider">Update Details</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-on-surface/20 backdrop-blur-[2px]"><button>close</button></form>
    </dialog>
</x-app-layout>
