<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">Departments</h2>
                <p class="text-xs font-medium mt-1 opacity-70">Manage and define your organizational departments.</p>
            </div>
            <button onclick="add_department_modal.showModal()" class="btn btn-primary btn-sm">
                <span class="material-symbols-outlined text-base">add</span> Add Department
            </button>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="alert alert-success mb-6 text-sm font-semibold">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($departments as $department)
            <div class="card bg-surface-container-lowest shadow-sm border border-outline-variant/10 hover:border-primary/30 transition-all flex flex-col justify-between min-h-[160px] rounded-xl overflow-hidden">
                <div class="card-body p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold text-[10px] uppercase tracking-wider">
                            {{ $department->code }}
                        </div>
                        <div class="flex gap-1">
                            <button onclick="document.getElementById('edit_dept_{{ $department->id }}').showModal()" class="btn btn-ghost btn-xs btn-square text-secondary">
                                <span class="material-symbols-outlined text-base">edit</span>
                            </button>
                            <form action="{{ route('hr.departments.destroy', $department->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete {{ $department->name }}? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-ghost btn-xs btn-square text-error"><span class="material-symbols-outlined text-base">delete</span></button>
                            </form>
                        </div>
                    </div>
                    <a href="{{ route('hr.departments.show', $department->id) }}" class="group/title">
                        <h3 class="text-base font-black mb-1 group-hover/title:text-primary transition-colors uppercase tracking-tight">{{ $department->name }}</h3>
                    </a>
                    <p class="text-[10px] font-bold leading-relaxed opacity-50 uppercase tracking-widest line-clamp-2">
                        {{ $department->description ?? 'No description defined.' }}
                    </p>
                </div>
                
                <div class="px-5 py-3.5 border-t border-outline-variant/5 flex items-center justify-between mt-auto bg-surface-container-low/20">
                    <div class="flex items-center gap-1.5 font-bold text-on-surface-variant opacity-70">
                        <span class="material-symbols-outlined text-sm">groups</span>
                        <span class="text-[9px] uppercase tracking-wider">{{ $department->employees_count ?? 0 }} Members</span>
                    </div>
                    <a href="{{ route('hr.departments.show', $department->id) }}" class="text-[9px] font-bold text-primary uppercase tracking-widest hover:underline italic">Details →</a>
                </div>
            </div>

            {{-- Edit Department Modal (Standardized) --}}
            <dialog id="edit_dept_{{ $department->id }}" class="modal">
                <div class="modal-box max-w-xl bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-2xl p-0 overflow-hidden text-left font-sans">
                    <div class="p-5 border-b border-outline-variant/5 flex items-center justify-between bg-surface-container-low/30">
                        <div>
                            <h3 class="font-bold text-xs uppercase tracking-wider text-on-surface">Modify Department</h3>
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
                            <button type="submit" class="btn btn-primary btn-sm w-full rounded-lg font-bold text-[10px] uppercase tracking-wider shadow-sm">Sync Changes</button>
                        </div>
                    </form>
                </div>
                <form method="dialog" class="modal-backdrop bg-on-surface/20 backdrop-blur-[2px]"><button>close</button></form>
            </dialog>
        @empty
            <div class="col-span-full py-16 text-center border-2 border-dashed border-base-300 rounded-xl">
                <div class="flex flex-col items-center gap-4 opacity-40">
                    <span class="material-symbols-outlined text-5xl">account_tree</span>
                    <p class="font-bold text-sm">No departments found.</p>
                    <button onclick="add_department_modal.showModal()" class="btn btn-ghost btn-sm btn-outline">Add First Department</button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Add Department Modal (Matches Refined Style) -->
    <dialog id="add_department_modal" class="modal">
        <div class="modal-box max-w-xl bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-2xl p-0 overflow-hidden text-left">
            <div class="p-5 border-b border-outline-variant/5 flex items-center justify-between bg-surface-container-low/30">
                <div>
                    <h3 class="font-bold text-xs uppercase tracking-wider text-on-surface">Create New Department</h3>
                    <p class="text-[9px] font-bold opacity-50 uppercase tracking-widest mt-0.5">Organizational Unit Definition</p>
                </div>
                <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
            </div>
            
            <form action="{{ route('hr.departments.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                    <div class="form-control">
                        <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase tracking-wider opacity-60">Department Name</span></label>
                        <input name="name" type="text" class="input input-sm input-bordered focus:input-primary rounded-lg text-xs" required placeholder="e.g. Engineering" />
                    </div>
                    <div class="form-control">
                        <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase tracking-wider opacity-60">Dept Code</span></label>
                        <input name="code" type="text" class="input input-sm input-bordered focus:input-primary rounded-lg text-xs uppercase" required placeholder="e.g. ENG" />
                    </div>
                </div>
                <div class="form-control text-left">
                    <label class="label py-1"><span class="label-text text-[10px] font-bold uppercase tracking-wider opacity-60">Description</span></label>
                    <textarea name="description" class="textarea textarea-bordered focus:textarea-primary rounded-lg text-xs" rows="3" placeholder="Scope and mission..."></textarea>
                </div>
                <div class="pt-2">
                    <button type="submit" class="btn btn-primary btn-sm w-full rounded-lg font-bold text-[10px] uppercase tracking-wider shadow-sm">Initialize Department</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-on-surface/20 backdrop-blur-[2px]"><button>close</button></form>
    </dialog>
</x-app-layout>
