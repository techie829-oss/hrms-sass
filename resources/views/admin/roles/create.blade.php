<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Define Role</h2>
                <p class="text-sm text-slate-500 mt-1">Create a new access level with specific permissions.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="card bg-white border border-slate-200 rounded-xl shadow-sm p-6 sm:p-8 space-y-8">
                <div class="space-y-2">
                    <x-input-label for="name" :value="__('Role Name (e.g. system_auditor)')" class="text-slate-700 font-semibold" />
                    <x-text-input id="name" name="name" type="text" class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required placeholder="system_auditor" oninput="this.value = this.value.replace(/\s+/g, '_').toLowerCase()" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="pt-8 border-t border-slate-100">
                    <h3 class="font-bold text-lg text-slate-900 mb-6">Assign Permissions</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @php
                            $permissionDetails = \App\Core\Constants\PermissionConstants::getPermissionDetails();
                            $dbPermissions = $permissions->pluck('name')->toArray();
                        @endphp
                        @foreach($permissionDetails as $group => $perms)
                            <div class="bg-slate-50 p-6 rounded-xl border border-slate-200">
                                <h4 class="font-semibold text-xs uppercase tracking-widest text-indigo-600 mb-4 border-b border-indigo-100 pb-2">{{ $group }}</h4>
                                <div class="space-y-3">
                                    @foreach($perms as $rawName => $label)
                                        @if(in_array($rawName, $dbPermissions))
                                            <label class="flex items-start gap-3 cursor-pointer group">
                                                <input type="checkbox" name="permissions[]" value="{{ $rawName }}" class="mt-0.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600" />
                                                <span class="text-sm font-medium text-slate-700 group-hover:text-indigo-600 transition-colors">{{ $label }}</span>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Create Role
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
