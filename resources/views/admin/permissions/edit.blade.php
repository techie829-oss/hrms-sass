<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.permissions.index') }}" class="btn btn-ghost btn-sm btn-square rounded-xl">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface uppercase">Edit Permission</h2>
                <p class="text-sm text-on-surface-variant font-medium mt-1">Modify permission key and its feature description mapping.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-xl mx-auto">
        <form action="{{ route('admin.permissions.update', $permission->id) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            
            <div class="bg-surface-container-lowest p-8 md:p-12 rounded-[3rem] shadow-xl border border-outline-variant/15 space-y-8">
                <div class="space-y-6">
                    <div class="space-y-2">
                        <x-input-label for="name" :value="__('Permission Name')" />
                        <x-text-input id="name" name="name" type="text" class="block w-full" required :value="old('name', $permission->name)" placeholder="view timesheet" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="description" :value="__('Feature / Module Description')" />
                        <textarea id="description" name="description" rows="4" 
                            class="textarea textarea-bordered w-full rounded-2xl border-outline-variant/30 bg-surface-container-low text-sm font-medium focus:outline-none focus:border-primary text-on-surface placeholder-on-surface-variant/40" 
                            placeholder="Describe what feature this permission governs... (e.g. Access Personal Timesheet & Activity Logs)">{{ old('description', $permission->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-8 border-t border-outline-variant/10">
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-ghost rounded-xl font-bold uppercase tracking-widest text-xs px-8">Cancel</a>
                    <button type="submit" class="btn btn-primary bg-gradient-to-br from-primary to-tertiary border-none rounded-xl font-black uppercase tracking-[0.2em] text-xs px-10 shadow-lg">
                        Update Permission
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
