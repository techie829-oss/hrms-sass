<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface">Access Roles</h2>
                <p class="text-sm text-on-surface-variant font-medium mt-1">Manage global system roles and their
                    permissions.</p>
            </div>
            <a href="{{ route('admin.roles.create') }}"
                class="btn btn-primary bg-gradient-to-br from-primary to-tertiary border-none rounded-xl font-bold text-xs uppercase tracking-widest px-6 shadow-lg">
                <span class="material-symbols-outlined text-lg">shield_person</span> Create Role
            </a>
        </div>
    </x-slot>

    @php
        $roleIconMap = [
            'super_admin' => 'verified_user',
            'admin' => 'admin_panel_settings',
            'hr_manager' => 'badge',
            'manager' => 'manage_accounts',
            'employee' => 'person',
            'user' => 'person',
        ];
        
        $tableHeaders = ['Role Name', 'Guard', 'Permissions', 'Users Assigned', 'Actions'];
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
                        <button
                            class="btn btn-ghost btn-sm btn-square rounded-xl hover:bg-secondary/10 hover:text-secondary group-hover:scale-110 transition-transform">
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
