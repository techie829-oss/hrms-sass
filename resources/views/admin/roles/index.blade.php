<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface">Access Roles</h2>
                <p class="text-sm text-on-surface-variant font-medium mt-1">Manage global system roles and their
                    permissions.</p>
            </div>
            <div class="flex items-center gap-4">
                <form action="{{ route('admin.roles.index') }}" method="GET" class="flex items-center gap-2" id="filterForm">
                    <div class="relative">
                        <select name="context" onchange="document.getElementById('filterForm').submit()" class="select select-bordered rounded-xl border-outline-variant/30 bg-surface-container-low text-sm font-medium focus:outline-none focus:border-primary w-48 appearance-none pl-4 pr-10">
                            <option value="">All Contexts</option>
                            <option value="global" {{ request('context') === 'global' ? 'selected' : '' }}>Global (Blueprints)</option>
                            @foreach($contexts as $tenantContext)
                                <option value="{{ $tenantContext }}" {{ request('context') === $tenantContext ? 'selected' : '' }}>Tenant: {{ $tenantContext }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-sm">expand_more</span>
                        </div>
                    </div>
                </form>
                
                <a href="{{ route('admin.permissions.index') }}"
                    class="btn btn-outline border-primary/30 text-primary hover:bg-primary/5 hover:text-primary rounded-xl font-bold text-xs uppercase tracking-widest px-6 shadow-sm">
                    <span class="material-symbols-outlined text-lg">admin_panel_settings</span> Manage Permissions
                </a>

                <a href="{{ route('admin.roles.create') }}"
                    class="btn btn-primary bg-gradient-to-br from-primary to-tertiary border-none rounded-xl font-bold text-xs uppercase tracking-widest px-6 shadow-lg">
                    <span class="material-symbols-outlined text-lg">shield_person</span> Create Role
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

    <div class="bg-surface-container-lowest rounded-[2.5rem] border border-outline-variant/15 shadow-xl overflow-hidden">
        <x-table :headers="$tableHeaders" :striped="false">
            @forelse($roles as $role)
                <tr class="hover:bg-primary/5 transition-colors border-b border-outline-variant/5 group">
                    <td class="py-6 px-8">
                        <div class="flex items-center gap-4">
                            <div class="avatar placeholder">
                                <div
                                    class="bg-primary/10 text-primary rounded-2xl w-12 h-12 font-black text-sm shadow-xl border border-primary/10 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-xl">
                                        {{ $roleIconMap[strtolower($role->name)] ?? 'shield' }}
                                    </span>
                                </div>
                            </div>
                            <div
                                class="font-black text-on-surface text-base uppercase tracking-wider group-hover:text-primary transition-colors">
                                {{ str_replace('_', ' ', $role->name) }}</div>
                        </div>
                    </td>
                    <td class="py-6 px-8">
                        @if(is_null($role->tenant_id))
                            <span class="badge badge-primary font-bold text-[10px] uppercase tracking-widest px-3 py-3 h-auto border-none bg-primary/10 text-primary">
                                <span class="material-symbols-outlined text-[12px] mr-1">public</span> Global
                            </span>
                        @else
                            <span class="badge badge-secondary font-bold text-[10px] uppercase tracking-widest px-3 py-3 h-auto border-none bg-secondary/10 text-secondary">
                                <span class="material-symbols-outlined text-[12px] mr-1">domain</span> {{ $role->tenant_id }}
                            </span>
                        @endif
                    </td>
                    <td class="py-6 px-8">
                        <span
                            class="badge badge-ghost font-mono text-[10px] uppercase tracking-widest px-3 py-3 h-auto border-none bg-outline-variant/10">
                            {{ $role->guard_name }}
                        </span>
                    </td>
                    <td class="py-6 px-8">
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-black text-primary">{{ $role->permissions_count }}</span>
                            <span
                                class="text-[9px] text-on-surface-variant uppercase tracking-[0.2em] font-black opacity-60">Permissions</span>
                        </div>
                    </td>
                    <td class="py-6 px-8">
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-black text-secondary">{{ $role->users_count }}</span>
                            <span
                                class="text-[9px] text-on-surface-variant uppercase tracking-[0.2em] font-black opacity-60">Users</span>
                        </div>
                    </td>
                    <td class="text-right px-8 py-6">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.roles.edit', $role->id) }}"
                                class="btn btn-ghost btn-sm btn-square rounded-xl hover:bg-secondary/10 hover:text-secondary group-hover:scale-110 transition-transform" title="Edit Role">
                                <span class="material-symbols-outlined text-sm">edit</span>
                            </a>
                            @if(!in_array(strtolower($role->name), [\App\Core\Constants\RoleConstants::SADMIN, \App\Core\Constants\RoleConstants::SMANAGER, \App\Core\Constants\RoleConstants::TADMIN, \App\Core\Constants\RoleConstants::TSTAFF]))
                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this role?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-sm btn-square rounded-xl hover:bg-error/10 hover:text-error group-hover:scale-110 transition-transform text-error" title="Delete Role">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-20 text-center">
                        <div class="flex flex-col items-center gap-4 opacity-40">
                            <span class="material-symbols-outlined text-6xl">security</span>
                            <p class="font-headline font-bold text-lg">No roles found.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-table>
    </div>
</x-app-layout>
