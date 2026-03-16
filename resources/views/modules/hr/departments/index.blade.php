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

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($departments as $department)
            <div class="card bg-base-100 shadow-sm border border-base-200 hover:border-primary/30 transition-all flex flex-col justify-between min-h-[180px]">
                <div class="card-body p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold text-[10px]">
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
                    <h3 class="text-base font-bold mb-1">{{ $department->name }}</h3>
                    <p class="text-xs font-medium leading-relaxed opacity-70 line-clamp-2">
                        {{ $department->description ?? 'No description defined.' }}
                    </p>
                </div>
                
                <div class="px-6 py-4 border-t border-base-200 flex items-center justify-between mt-auto">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-sm">groups</span>
                        <span class="text-[9px] font-bold uppercase tracking-wider">{{ $department->employees_count ?? 0 }} Members</span>
                    </div>
                    <a href="{{ route('hr.employees.index', ['department_id' => $department->id]) }}" class="text-[9px] font-bold text-primary uppercase tracking-wider hover:underline">Manage →</a>
                </div>
            </div>

            {{-- Edit Department Modal --}}
            <dialog id="edit_dept_{{ $department->id }}" class="modal">
                <div class="modal-box max-w-xl">
                    <form method="dialog">
                        <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
                    </form>
                    <h3 class="font-bold text-xl mb-1">Edit Department</h3>
                    <p class="text-xs mb-6 font-medium opacity-70">Update the details for <strong>{{ $department->name }}</strong>.</p>
                    
                    <form action="{{ route('hr.departments.update', $department->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-control">
                                <label class="label" for="edit_name_{{ $department->id }}">
                                    <span class="label-text font-bold">Department Name <span class="text-error">*</span></span>
                                </label>
                                <input id="edit_name_{{ $department->id }}" name="name" type="text" class="input input-bordered w-full" required value="{{ $department->name }}" />
                            </div>
                            <div class="form-control">
                                <label class="label" for="edit_code_{{ $department->id }}">
                                    <span class="label-text font-bold">Dept Code <span class="text-error">*</span></span>
                                </label>
                                <input id="edit_code_{{ $department->id }}" name="code" type="text" class="input input-bordered w-full uppercase" required value="{{ $department->code }}" />
                            </div>
                        </div>
                        <div class="form-control">
                            <label class="label" for="edit_desc_{{ $department->id }}">
                                <span class="label-text font-bold">Description</span>
                            </label>
                            <textarea id="edit_desc_{{ $department->id }}" name="description" class="textarea textarea-bordered w-full" rows="3">{{ $department->description }}</textarea>
                        </div>
                        <div class="modal-action pt-2">
                            <button type="submit" class="btn btn-primary w-full">Update Department</button>
                        </div>
                    </form>
                </div>
                <form method="dialog" class="modal-backdrop"><button>close</button></form>
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

    <!-- Add Department Modal -->
    <dialog id="add_department_modal" class="modal">
        <div class="modal-box max-w-xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
            </form>
            <h3 class="font-bold text-xl mb-1">Add Department</h3>
            <p class="text-xs mb-6 font-medium opacity-70">Create a new organizational department.</p>
            
            <form action="{{ route('hr.departments.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label" for="name">
                            <span class="label-text font-bold">Department Name <span class="text-error">*</span></span>
                        </label>
                        <input id="name" name="name" type="text" class="input input-bordered w-full" required placeholder="Engineering" />
                    </div>
                    <div class="form-control">
                        <label class="label" for="code">
                            <span class="label-text font-bold">Dept Code <span class="text-error">*</span></span>
                        </label>
                        <input id="code" name="code" type="text" class="input input-bordered w-full uppercase" required placeholder="ENG" />
                    </div>
                </div>
                <div class="form-control">
                    <label class="label" for="description">
                        <span class="label-text font-bold">Description</span>
                    </label>
                    <textarea id="description" name="description" class="textarea textarea-bordered w-full" rows="3" placeholder="Define the department scope..."></textarea>
                </div>
                
                <div class="modal-action pt-2">
                    <button type="submit" class="btn btn-primary w-full">Save Department</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</x-app-layout>
