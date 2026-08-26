@extends('layouts.tenant.app')

@section('title', 'Roles & Permissions - Company Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Roles & Permissions</h1>
            <p class="text-xs text-gray-500 mt-1">Manage system access roles and fine-grained permissions for your company staff.</p>
        </div>
        <a href="{{ route('tenant.roles.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
            <span class="material-symbols-outlined text-[18px] mr-1.5">add</span>
            Create Custom Role
        </a>
    </div>

    @if (session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs rounded-xl flex items-center">
            <span class="material-symbols-outlined text-[18px] mr-2">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-xl flex items-center">
            <span class="material-symbols-outlined text-[18px] mr-2">error</span>
            {{ session('error') }}
        </div>
    @endif

    <!-- Roles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($roles as $role)
            @php
                $isSystemRole = in_array($role->name, \App\Core\Constants\RoleConstants::getReservedRoles());
            @endphp
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-xs flex flex-col justify-between hover:shadow-md transition-shadow">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg flex items-center justify-center {{ $isSystemRole ? 'bg-indigo-50 text-indigo-600' : 'bg-primary-50 text-primary-600' }}">
                                <span class="material-symbols-outlined text-[20px]">{{ $isSystemRole ? 'verified_user' : 'badge' }}</span>
                            </span>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 capitalize">{{ $role->name }}</h3>
                                <span class="text-[10px] font-semibold uppercase px-2 py-0.5 rounded-full {{ $isSystemRole ? 'bg-gray-100 text-gray-600' : 'bg-primary-50 text-primary-700 border border-primary-200' }}">
                                    {{ $isSystemRole ? 'System Default' : 'Custom Dynamic' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2 mt-4 pt-3 border-t border-gray-100 text-xs text-gray-600">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Assigned Staff:</span>
                            <span class="font-semibold text-gray-800">{{ $role->users_count }} user(s)</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Active Permissions:</span>
                            <span class="font-semibold text-gray-800">{{ $role->permissions_count }} permission(s)</span>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-3 border-t border-gray-100 flex items-center justify-between gap-2">
                    @if(!$isSystemRole || $role->tenant_id !== null)
                        <a href="{{ route('tenant.roles.edit', $role->id) }}" class="text-xs font-semibold text-primary-600 hover:text-primary-800 flex items-center">
                            <span class="material-symbols-outlined text-[16px] mr-1">edit</span> Edit Permissions
                        </a>
                        <form action="{{ route('tenant.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this custom role?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-800 flex items-center">
                                <span class="material-symbols-outlined text-[16px] mr-0.5">delete</span> Delete
                            </button>
                        </form>
                    @else
                        <span class="text-[11px] text-gray-400 font-medium italic">Protected System Role</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
