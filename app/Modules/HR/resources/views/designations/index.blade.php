<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold font-sans tracking-tight">Designations</h2>
                <p class="text-[10px] font-bold mt-1 opacity-50 uppercase tracking-widest leading-none">Define and manage organizational roles & salary grades</p>
            </div>
            <button onclick="add_designation_modal.showModal()" class="btn btn-primary btn-sm rounded-lg shadow-sm border-none bg-primary hover:bg-primary-focus transition-all group px-4">
                <span class="material-symbols-outlined text-base group-hover:rotate-90 transition-transform duration-300">add</span> 
                <span class="text-[10px] font-black uppercase tracking-widest">New Designation</span>
            </button>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="alert alert-success mb-6 text-[10px] font-black uppercase tracking-widest rounded-xl border-none shadow-sm bg-success/20 text-success-content py-3 px-4">
            <span class="material-symbols-outlined text-lg">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($designations as $designation)
            <div class="card bg-surface-container-lowest shadow-sm border border-outline-variant/10 hover:border-primary/30 transition-all flex flex-col justify-between min-h-[200px] rounded-2xl overflow-hidden group/card relative">
                <div class="card-body p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-primary uppercase tracking-[2px] mb-1 leading-none">{{ $designation->department->name ?? 'Unassigned' }}</span>
                            <h3 class="text-base font-black text-on-surface uppercase tracking-tight leading-tight group-hover/card:text-primary transition-colors">{{ $designation->name }}</h3>
                            <span class="text-[10px] font-bold opacity-30 uppercase tracking-widest mt-1">{{ $designation->code }}</span>
                        </div>
                        <div class="flex gap-1 opacity-0 group-hover/card:opacity-100 transition-opacity duration-300 -mt-1 -mr-1">
                            <button onclick="document.getElementById('edit_desig_{{ $designation->id }}').showModal()" class="btn btn-ghost btn-xs btn-square text-secondary hover:bg-secondary/10 transition-colors">
                                <span class="material-symbols-outlined text-base">edit</span>
                            </button>
                            <form action="{{ route('hr.designations.destroy', $designation->id) }}" method="POST" class="inline" onsubmit="return confirm('Archive {{ $designation->name }}? This will affect salary structures.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-ghost btn-xs btn-square text-error hover:bg-error/10 transition-colors">
                                    <span class="material-symbols-outlined text-base">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <p class="text-[11px] font-medium leading-relaxed opacity-60 line-clamp-2 italic mb-6">
                        {{ $designation->description ?? 'Standard role description for organizational hierarchy.' }}
                    </p>

                    <div class="flex items-center gap-4 border-t border-outline-variant/5 pt-4 mt-auto">
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[8px] font-black uppercase tracking-widest opacity-40 leading-none">Salary Grade</span>
                            <div class="flex items-center gap-1.5 font-black text-on-surface">
                                <span class="text-[10px] leading-none tracking-tight">₹{{ number_format($designation->min_salary ?? 0) }}</span>
                                <span class="text-[8px] opacity-30 mt-0.5 tracking-tighter">—</span>
                                <span class="text-[10px] leading-none tracking-tight">₹{{ number_format($designation->max_salary ?? 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-3 border-t border-outline-variant/5 flex items-center justify-between bg-surface-container-low/20">
                    <div class="flex items-center gap-1.5">
                        <div class="w-1.5 h-1.5 rounded-full {{ $designation->is_active ? 'bg-success' : 'bg-error' }} animate-pulse"></div>
                        <span class="text-[9px] font-black uppercase tracking-widest opacity-60">{{ $designation->is_active ? 'Active' : 'Inactive' }}</span>
                    </div>
                    <div class="flex items-center gap-1 text-[9px] font-black text-primary uppercase tracking-[1.5px] hover:underline cursor-pointer">
                        {{ $designation->employees_count ?? 0 }} Staff <span class="material-symbols-outlined text-xs">chevron_right</span>
                    </div>
                </div>

                {{-- Edit Designation Modal --}}
                <dialog id="edit_desig_{{ $designation->id }}" class="modal">
                    <div class="modal-box max-w-xl bg-surface-container-lowest rounded-2xl border border-outline-variant/10 shadow-2xl p-0 overflow-hidden text-left font-sans">
                        <div class="p-6 border-b border-outline-variant/5 flex items-center justify-between bg-surface-container-low/30">
                            <div>
                                <h3 class="font-black text-xs uppercase tracking-[2px] text-on-surface">Modify Designation</h3>
                                <p class="text-[9px] font-bold opacity-40 uppercase tracking-widest mt-1">{{ $designation->name }} ({{ $designation->code }})</p>
                            </div>
                            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
                        </div>
                        
                        <form action="{{ route('hr.designations.update', $designation->id) }}" method="POST" class="p-8 space-y-6">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="form-control">
                                    <label class="label py-1"><span class="label-text text-[9px] font-black uppercase tracking-widest opacity-50">Role Title</span></label>
                                    <input name="name" type="text" class="input input-sm h-10 bg-surface-container-low border-outline-variant/10 focus:border-primary focus:ring-1 focus:ring-primary rounded-xl text-xs font-bold" required value="{{ $designation->name }}" />
                                </div>
                                <div class="form-control">
                                    <label class="label py-1"><span class="label-text text-[9px] font-black uppercase tracking-widest opacity-50">Role Code</span></label>
                                    <input name="code" type="text" class="input input-sm h-10 bg-surface-container-low border-outline-variant/10 focus:border-primary focus:ring-1 focus:ring-primary rounded-xl text-xs font-bold uppercase" required value="{{ $designation->code }}" />
                                </div>
                                <div class="form-control">
                                    <label class="label py-1"><span class="label-text text-[9px] font-black uppercase tracking-widest opacity-50">Assigned Department</span></label>
                                    <select name="department_id" class="select select-sm h-10 bg-surface-container-low border-outline-variant/10 focus:border-primary rounded-xl text-xs font-bold" required>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}" {{ $designation->department_id == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-control flex flex-row items-center gap-4 pt-6">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" class="toggle toggle-primary toggle-sm shadow-sm" {{ $designation->is_active ? 'checked' : '' }} />
                                    <span class="text-[9px] font-black uppercase tracking-widest opacity-60">Status Active</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="form-control">
                                    <label class="label py-1"><span class="label-text text-[9px] font-black uppercase tracking-widest opacity-50">Min Salary (Annual)</span></label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-bold opacity-30">₹</span>
                                        <input name="min_salary" type="number" class="input input-sm h-10 w-full pl-7 bg-surface-container-low border-outline-variant/10 focus:border-primary rounded-xl text-xs font-bold" value="{{ (int)$designation->min_salary }}" />
                                    </div>
                                </div>
                                <div class="form-control">
                                    <label class="label py-1"><span class="label-text text-[9px] font-black uppercase tracking-widest opacity-50">Max Salary (Annual)</span></label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-bold opacity-30">₹</span>
                                        <input name="max_salary" type="number" class="input input-sm h-10 w-full pl-7 bg-surface-container-low border-outline-variant/10 focus:border-primary rounded-xl text-xs font-bold" value="{{ (int)$designation->max_salary }}" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-control">
                                <label class="label py-1"><span class="label-text text-[9px] font-black uppercase tracking-widest opacity-50">Responsibility Scope</span></label>
                                <textarea name="description" class="textarea h-24 bg-surface-container-low border-outline-variant/10 focus:border-primary rounded-xl text-xs font-bold p-4 leading-relaxed whitespace-pre-wrap">{{ $designation->description }}</textarea>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="btn btn-primary btn-sm w-full h-11 rounded-xl font-black text-[10px] uppercase tracking-[2px] shadow-lg border-none">Save Grade Definition</button>
                            </div>
                        </form>
                    </div>
                    <form method="dialog" class="modal-backdrop bg-on-surface/40 backdrop-blur-[4px] transition-all"><button>close</button></form>
                </dialog>
            </div>
        @empty
            <div class="col-span-full py-16 text-center border-2 border-dashed border-outline-variant/20 rounded-2xl bg-surface-container-low/10">
                <div class="flex flex-col items-center gap-6 opacity-40">
                    <div class="w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center">
                        <span class="material-symbols-outlined text-4xl">work</span>
                    </div>
                    <div>
                        <p class="font-black text-xs uppercase tracking-[2px]">No roles initialized</p>
                        <p class="text-[10px] font-bold mt-1 max-w-[200px] mx-auto opacity-60 uppercase tracking-widest leading-relaxed">Start by adding your first designation to this tenant.</p>
                    </div>
                    <button onclick="add_designation_modal.showModal()" class="btn btn-primary btn-sm rounded-lg px-6 font-black text-[9px] uppercase tracking-widest">Initialize Grade 01</button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Add Designation Modal (Refined CRM Style) -->
    <dialog id="add_designation_modal" class="modal">
        <div class="modal-box max-w-xl bg-surface-container-lowest rounded-2xl border border-outline-variant/10 shadow-2xl p-0 overflow-hidden text-left font-sans">
            <div class="p-6 border-b border-outline-variant/5 flex items-center justify-between bg-surface-container-low/30">
                <div>
                    <h3 class="font-black text-xs uppercase tracking-[2px] text-on-surface">Designate New Role</h3>
                    <p class="text-[9px] font-bold opacity-40 uppercase tracking-widest mt-1">Hierarchical Definition & Grade Setup</p>
                </div>
                <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
            </div>
            
            <form action="{{ route('hr.designations.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label py-1"><span class="label-text text-[9px] font-black uppercase tracking-widest opacity-50">Role Title</span></label>
                        <input name="name" type="text" class="input input-sm h-10 bg-surface-container-low border-outline-variant/10 focus:border-primary focus:ring-1 focus:ring-primary rounded-xl text-xs font-bold" required placeholder="e.g. Senior Analyst" />
                    </div>
                    <div class="form-control">
                        <label class="label py-1"><span class="label-text text-[9px] font-black uppercase tracking-widest opacity-50">Role Code</span></label>
                        <input name="code" type="text" class="input input-sm h-10 bg-surface-container-low border-outline-variant/10 focus:border-primary focus:ring-1 focus:ring-primary rounded-xl text-xs font-bold uppercase" required placeholder="e.g. SR-ANL" />
                    </div>
                    <div class="form-control">
                        <label class="label py-1"><span class="label-text text-[9px] font-black uppercase tracking-widest opacity-50">Target Department</span></label>
                        <select name="department_id" class="select select-sm h-10 bg-surface-container-low border-outline-variant/10 focus:border-primary rounded-xl text-xs font-bold text-on-surface" required>
                            <option value="" disabled selected>Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-control flex flex-row items-center gap-4 pt-6">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="toggle toggle-primary toggle-sm shadow-sm" checked />
                        <span class="text-[9px] font-black uppercase tracking-widest opacity-60">Status Active</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label py-1"><span class="label-text text-[9px] font-black uppercase tracking-widest opacity-50">Min Salary (Annual)</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-bold opacity-30">₹</span>
                            <input name="min_salary" type="number" class="input input-sm h-10 w-full pl-7 bg-surface-container-low border-outline-variant/10 focus:border-primary rounded-xl text-xs font-bold" placeholder="500000" />
                        </div>
                    </div>
                    <div class="form-control">
                        <label class="label py-1"><span class="label-text text-[9px] font-black uppercase tracking-widest opacity-50">Max Salary (Annual)</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-bold opacity-30">₹</span>
                            <input name="max_salary" type="number" class="input input-sm h-10 w-full pl-7 bg-surface-container-low border-outline-variant/10 focus:border-primary rounded-xl text-xs font-bold" placeholder="1200000" />
                        </div>
                    </div>
                </div>

                <div class="form-control">
                    <label class="label py-1"><span class="label-text text-[9px] font-black uppercase tracking-widest opacity-50">Scope of Responsibility</span></label>
                    <textarea name="description" class="textarea h-24 bg-surface-container-low border-outline-variant/10 focus:border-primary rounded-xl text-xs font-bold p-4 leading-relaxed" placeholder="Define primary objectives of this role..."></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn btn-primary btn-sm w-full h-11 rounded-xl font-black text-[10px] uppercase tracking-[2px] shadow-lg border-none bg-primary text-primary-content hover:bg-primary-focus">Initialize Designation</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-on-surface/40 backdrop-blur-[4px] transition-all"><button>close</button></form>
    </dialog>
</x-app-layout>
