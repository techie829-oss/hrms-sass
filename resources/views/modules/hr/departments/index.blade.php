<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface">Organizational Units</h2>
                <p class="text-sm text-on-surface-variant font-medium mt-1">Structure your sanctuary by defining departments.</p>
            </div>
            <button onclick="add_department_modal.showModal()" class="btn btn-primary primary-gradient border-none rounded-xl font-bold text-xs uppercase tracking-widest px-6 shadow-lg">
                <span class="material-symbols-outlined text-lg">add_chart</span> Add Department
            </button>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($departments as $department)
            <div class="bg-surface-container-lowest p-8 rounded-[2.5rem] border border-outline-variant/15 premium-shadow group hover:bg-surface-bright transition-all flex flex-col justify-between min-h-[240px]">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center font-black text-xs premium-shadow border border-primary/5">
                            {{ $department->code }}
                        </div>
                        <div class="flex gap-1">
                            <button class="btn btn-ghost btn-xs btn-square rounded-lg hover:bg-secondary/10 hover:text-secondary"><span class="material-symbols-outlined text-sm">edit</span></button>
                            <form action="{{ route('hr.departments.destroy', $department->id) }}" method="POST" class="inline" onsubmit="return confirm('Remove this architectural unit?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-ghost btn-xs btn-square rounded-lg hover:bg-error/10 hover:text-error"><span class="material-symbols-outlined text-sm">delete</span></button>
                            </form>
                        </div>
                    </div>
                    <h3 class="text-xl font-black font-headline text-on-surface mb-2">{{ $department->name }}</h3>
                    <p class="text-sm text-on-surface-variant font-medium leading-relaxed line-clamp-2">
                        {{ $department->description ?? 'No description defined for this unit.' }}
                    </p>
                </div>
                
                <div class="mt-8 pt-6 border-t border-outline-variant/10 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-sm">groups</span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-on-surface">{{ $department->employees_count ?? 0 }} Members</span>
                    </div>
                    <a href="#" class="text-[9px] font-black text-primary uppercase tracking-widest hover:underline">Manage Team</a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center bg-surface-container-lowest rounded-[2.5rem] border-2 border-dashed border-outline-variant/20">
                <div class="flex flex-col items-center gap-4 opacity-40">
                    <span class="material-symbols-outlined text-6xl">account_tree</span>
                    <p class="font-headline font-bold text-lg">No departments architected yet.</p>
                    <button onclick="add_department_modal.showModal()" class="btn btn-sm btn-outline border-outline-variant/30 rounded-lg">Define First Unit</button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Add Department Modal -->
    <dialog id="add_department_modal" class="modal">
        <div class="modal-box bg-surface-container-lowest rounded-[2.5rem] p-10 premium-shadow border border-outline-variant/15 max-w-xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-6 top-6">✕</button>
            </form>
            <h3 class="font-black font-headline text-2xl mb-2 text-on-surface uppercase tracking-tight">Define Department</h3>
            <p class="text-on-surface-variant text-sm mb-8 font-medium">Add a new organizational unit to your sanctuary.</p>
            
            <form action="{{ route('hr.departments.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <x-input-label for="name" :value="__('Department Name')" />
                        <x-text-input id="name" name="name" class="block w-full" type="text" required placeholder="Engineering" />
                    </div>
                    <div class="space-y-2">
                        <x-input-label for="code" :value="__('Unit Code')" />
                        <x-text-input id="code" name="code" class="block w-full uppercase" type="text" required placeholder="ENG" />
                    </div>
                </div>
                <div class="space-y-2">
                    <x-input-label for="description" :value="__('Description')" />
                    <textarea id="description" name="description" class="w-full bg-surface-container-low border-transparent focus:border-primary focus:ring-0 rounded-2xl p-4 text-on-surface placeholder-outline/50 transition-all font-medium" rows="3" placeholder="Define the scope of this unit..."></textarea>
                </div>
                
                <div class="pt-4">
                    <button type="submit" class="w-full btn btn-primary primary-gradient border-none rounded-xl font-black uppercase tracking-[0.2em] text-xs h-auto py-4 shadow-lg">
                        Create Architectural Unit
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</x-app-layout>
