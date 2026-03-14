<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface">Access Roles</h2>
                <p class="text-sm text-on-surface-variant font-medium mt-1">Manage global system roles and their permissions.</p>
            </div>
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary primary-gradient border-none rounded-xl font-bold text-xs uppercase tracking-widest px-6 shadow-lg">
                <span class="material-symbols-outlined text-lg">shield_person</span> Create Role
            </a>
        </div>
    </x-slot>

    <div class="bg-surface-container-lowest rounded-[2.5rem] border border-outline-variant/15 premium-shadow overflow-hidden">
        <x-table :headers="['Role Name', 'Guard', 'Permissions', 'Users Assigned', 'Actions']" :striped="false">
            @forelse($roles as $role)
                <tr class="hover:bg-primary/5 transition-colors border-b border-outline-variant/5">
                    <td class="py-5 px-6">
                        <div class="flex items-center gap-4">
                            <div class="avatar placeholder">
                                <div class="bg-primary/10 text-primary rounded-2xl w-10 h-10 font-black text-sm premium-shadow border border-primary/5">
                                    <span class="material-symbols-outlined text-lg">admin_panel_settings</span>
                                </div>
                            </div>
                            <div class="font-black text-on-surface text-base uppercase tracking-wider">{{ str_replace('_', ' ', $role->name) }}</div>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-ghost font-mono text-[10px] uppercase tracking-widest px-3 py-2">
                            {{ $role->guard_name }}
                        </span>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-black text-primary">{{ $role->permissions_count }}</span>
                            <span class="text-[9px] text-on-surface-variant uppercase tracking-widest font-bold">Permissions</span>
                        </div>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-black text-secondary">{{ $role->users_count }}</span>
                            <span class="text-[9px] text-on-surface-variant uppercase tracking-widest font-bold">Users</span>
                        </div>
                    </td>
                    <td class="text-right px-6">
                        <button class="btn btn-ghost btn-sm btn-square rounded-xl hover:bg-secondary/10 hover:text-secondary">
                            <span class="material-symbols-outlined text-sm">edit</span>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-20 text-center">
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
