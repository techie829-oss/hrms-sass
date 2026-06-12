<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Access Roles</h2>
                <p class="text-sm text-slate-500 mt-1">Manage global system roles and their permissions.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <form action="{{ route('admin.roles.index') }}" method="GET" class="flex items-center gap-2" id="filterForm">
                    <div class="relative">
                        <select name="context" onchange="document.getElementById('filterForm').submit()" class="block w-full rounded-lg border-0 py-2 pl-3 pr-10 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6 bg-white appearance-none">
                            <option value="">All Contexts</option>
                            <option value="global" {{ request('context') === 'global' ? 'selected' : '' }}>Global (Blueprints)</option>
                            @foreach($contexts as $tenantContext)
                                <option value="{{ $tenantContext }}" {{ request('context') === $tenantContext ? 'selected' : '' }}>Tenant: {{ $tenantContext }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                            <span class="material-symbols-outlined text-sm">expand_more</span>
                        </div>
                    </div>
                </form>
                
                <a href="{{ route('admin.permissions.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    <span class="material-symbols-outlined text-sm mr-2">admin_panel_settings</span> Manage Permissions
                </a>

                <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <span class="material-symbols-outlined text-sm mr-2">shield_person</span> Create Role
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $roleIconMap = [
            \App\Core\Constants\RoleConstants::SADMIN => 'verified_user',
            \App\Core\Constants\RoleConstants::SMANAGER => 'admin_panel_settings',
            \App\Core\Constants\RoleConstants::SSTAFF => 'support_agent',
            \App\Core\Constants\RoleConstants::TADMIN => 'apartment',
            \App\Core\Constants\RoleConstants::TMANAGER => 'badge',
            \App\Core\Constants\RoleConstants::TSTAFF => 'person',
        ];
        
        $tableHeaders = ['Role Name', 'Context', 'Guard', 'Permissions', 'Users Assigned', 'Actions'];
    @endphp

    <div class="card bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <x-table :headers="$tableHeaders" :striped="false">
            @forelse($roles as $role)
                <tr class="hover:bg-slate-50 transition-colors border-b border-slate-100 group">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                                <span class="material-symbols-outlined text-lg">
                                    {{ $roleIconMap[strtolower($role->name)] ?? 'shield' }}
                                </span>
                            </div>
                            <div class="font-bold text-slate-900 text-sm tracking-wide">
                                {{ str_replace('_', ' ', $role->name) }}
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        @if(is_null($role->tenant_id))
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                <span class="material-symbols-outlined text-[14px] mr-1">public</span> Global
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-100">
                                <span class="material-symbols-outlined text-[14px] mr-1">domain</span> {{ $role->tenant_id }}
                            </span>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono bg-slate-100 text-slate-600 border border-slate-200">
                            {{ $role->guard_name }}
                        </span>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-bold text-indigo-600">{{ $role->permissions_count }}</span>
                            <span class="text-xs text-slate-500 font-medium">Permissions</span>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-bold text-slate-700">{{ $role->users_count }}</span>
                            <span class="text-xs text-slate-500 font-medium">Users</span>
                        </div>
                    </td>
                    <td class="text-right px-6 py-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.roles.edit', $role->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Edit Role">
                                <span class="material-symbols-outlined text-sm">edit</span>
                            </a>
                            @if(!in_array(strtolower($role->name), [\App\Core\Constants\RoleConstants::SADMIN, \App\Core\Constants\RoleConstants::SMANAGER, \App\Core\Constants\RoleConstants::TADMIN, \App\Core\Constants\RoleConstants::TSTAFF]))
                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this role?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete Role">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-12 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <span class="material-symbols-outlined text-4xl text-slate-300">security</span>
                            <p class="font-medium text-slate-500 text-sm">No roles found.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-table>
    </div>
</x-app-layout>
