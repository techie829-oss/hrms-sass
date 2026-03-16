<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-ghost btn-sm btn-square rounded-xl">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface uppercase">Define Role</h2>
                <p class="text-sm text-on-surface-variant font-medium mt-1">Create a new access level with specific permissions.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="bg-surface-container-lowest p-8 md:p-12 rounded-[3rem] shadow-xl border border-outline-variant/15 space-y-8">
                <div class="space-y-2">
                    <x-input-label for="name" :value="__('Role Name (e.g. system_auditor)')" />
                    <x-text-input id="name" name="name" type="text" class="block w-full" required placeholder="system_auditor" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="pt-8 border-t border-outline-variant/10">
                    <h3 class="font-black font-headline text-xl uppercase tracking-widest text-on-surface mb-6">Assign Permissions</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($permissions as $group => $perms)
                            <div class="bg-surface-container-low p-6 rounded-2xl border border-outline-variant/10">
                                <h4 class="font-bold text-xs uppercase tracking-widest text-primary mb-4 border-b border-primary/10 pb-2">{{ ucfirst($group) }}</h4>
                                <div class="space-y-3">
                                    @foreach($perms as $permission)
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="checkbox checkbox-sm checkbox-primary rounded" />
                                            <span class="text-sm font-medium text-on-surface group-hover:text-primary transition-colors capitalize">{{ str_replace('-', ' ', explode(' ', $permission->name)[0]) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-8">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-ghost rounded-xl font-bold uppercase tracking-widest text-xs px-8">Cancel</a>
                    <button type="submit" class="btn btn-primary bg-gradient-to-br from-primary to-tertiary border-none rounded-xl font-black uppercase tracking-[0.2em] text-xs px-10 shadow-lg">
                        Create Role
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
