<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface">Manage Permissions</h2>
                <p class="text-sm text-on-surface-variant font-medium mt-1">Control granular global permissions available for assignment to roles.</p>
            </div>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-ghost rounded-xl font-bold text-xs uppercase tracking-widest px-6 border border-outline-variant/30">
                <span class="material-symbols-outlined text-lg mr-1">arrow_back</span> Back to Roles
            </a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- 1. Left Column: Create Permission Form -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-surface-container-lowest p-8 rounded-[2rem] shadow-xl border border-outline-variant/15 space-y-6">
                <h3 class="text-xl font-black font-headline uppercase tracking-wider text-on-surface">New Permission</h3>
                <p class="text-xs text-on-surface-variant font-medium">Add a new access key. Use lower case and hyphens for words (e.g., <code class="bg-surface-container-high px-1.5 py-0.5 rounded font-mono font-bold text-[10px]">view-timesheet</code>).</p>
                
                <form action="{{ route('admin.permissions.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-2">
                        <x-input-label for="name" :value="__('Permission Name')" />
                        <x-text-input id="name" name="name" type="text" class="block w-full" required placeholder="view-timesheet" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="description" :value="__('Description / Usage')" />
                        <textarea id="description" name="description" rows="3" 
                            class="textarea textarea-bordered w-full rounded-2xl border-outline-variant/30 bg-surface-container-low text-xs font-medium focus:outline-none focus:border-primary text-on-surface placeholder-on-surface-variant/40" 
                            placeholder="e.g. Access Personal Timesheet & Activity Logs"></textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <button type="submit" class="btn btn-primary bg-gradient-to-br from-primary to-tertiary border-none w-full rounded-xl font-black uppercase tracking-[0.2em] text-xs py-3 shadow-lg">
                        Add Permission
                    </button>
                </form>
            </div>
        </div>

        <!-- 2. Right Column: Permissions List -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Search & Filters -->
            <div class="bg-surface-container-lowest p-6 rounded-[2rem] shadow-xl border border-outline-variant/15 flex items-center justify-between gap-4">
                <form action="{{ route('admin.permissions.index') }}" method="GET" class="flex-1 max-w-md">
                    <div class="relative flex items-center">
                        <input type="text" name="search" placeholder="Search permissions..." value="{{ request('search') }}"
                            class="input input-bordered w-full rounded-xl border-outline-variant/30 pl-12 bg-surface-container-low text-sm font-medium focus:outline-none focus:border-primary" />
                        <span class="material-symbols-outlined absolute left-4 text-on-surface-variant/60">search</span>
                    </div>
                </form>
                @if(request('search'))
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-ghost btn-sm rounded-xl font-bold uppercase text-[10px] tracking-wider">
                        Clear Search
                    </a>
                @endif
            </div>

            <!-- List Card -->
            <div class="bg-surface-container-lowest rounded-[2rem] border border-outline-variant/15 shadow-xl overflow-hidden">
                <x-table :headers="['Permission Name', 'Feature / Module Usage', 'Group', 'Guard', 'Actions']" :striped="false">
                    @forelse($permissions as $permission)
                        <tr class="hover:bg-primary/5 transition-colors border-b border-outline-variant/5 group">
                            <td class="py-5 px-8">
                                <div class="flex items-center gap-3">
                                    <div class="bg-primary/5 text-primary rounded-xl p-2 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-sm">key</span>
                                    </div>
                                    <span class="font-bold text-on-surface font-mono text-sm uppercase tracking-wider">{{ $permission->name }}</span>
                                </div>
                            </td>
                            <td class="py-5 px-8">
                                <span class="text-xs font-semibold text-on-surface-variant">{{ $permission->feature_description }}</span>
                            </td>
                            <td class="py-5 px-8">
                                <span class="badge badge-outline text-primary border-primary/20 font-black text-[9px] uppercase tracking-widest px-2.5 py-2.5 h-auto">
                                    {{ explode('-', $permission->name)[1] ?? 'general' }}
                                </span>
                            </td>
                            <td class="py-5 px-8">
                                <span class="badge badge-ghost font-mono text-[9px] uppercase tracking-widest px-2.5 py-2.5 h-auto border-none bg-outline-variant/10">
                                    {{ $permission->guard_name }}
                                </span>
                            </td>
                            <td class="text-right px-8 py-5">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.permissions.edit', $permission->id) }}"
                                        class="btn btn-ghost btn-sm btn-square rounded-xl hover:bg-secondary/10 hover:text-secondary group-hover:scale-110 transition-transform" title="Edit Permission">
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                    </a>
                                    <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this permission? This could affect roles currently using it.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-sm btn-square rounded-xl hover:bg-error/10 hover:text-error group-hover:scale-110 transition-transform text-error" title="Delete Permission">
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-20 text-center">
                                <div class="flex flex-col items-center gap-4 opacity-40">
                                    <span class="material-symbols-outlined text-6xl">key_off</span>
                                    <p class="font-headline font-bold text-lg">No permissions found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </x-table>

                @if($permissions->hasPages())
                    <div class="p-6 border-t border-outline-variant/10 bg-surface-container-lowest">
                        {{ $permissions->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
