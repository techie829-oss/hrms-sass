@extends('layouts.tenant.app')

@section('title', 'Create Custom Role')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Create Custom Role</h1>
            <p class="text-xs text-gray-500 mt-1">Define custom role names and assign fine-grained permissions.</p>
        </div>
        <a href="{{ route('tenant.roles.index') }}" class="text-xs font-semibold text-gray-600 hover:text-gray-900 flex items-center">
            <span class="material-symbols-outlined text-[18px] mr-1">arrow_back</span> Back to Roles
        </a>
    </div>

    <form action="{{ route('tenant.roles.store') }}" method="POST" class="bg-white border border-gray-200 rounded-2xl p-6 shadow-xs space-y-6">
        @csrf

        <div>
            <label for="name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Role Name</label>
            <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="e.g. accountant, shift-supervisor" class="w-full text-xs rounded-lg border-gray-300 shadow-xs focus:border-primary-500 focus:ring-primary-500">
            @error('name')
                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-4">Assign Module Permissions</h3>
            
            <div class="space-y-6">
                @foreach($permissions as $group => $groupPermissions)
                    <div class="border border-gray-200 rounded-xl p-4 bg-gray-50/50">
                        <h4 class="text-xs font-bold text-gray-800 uppercase tracking-widest mb-3 flex items-center">
                            <span class="w-2 h-2 rounded-full bg-primary-500 mr-2"></span>
                            {{ ucfirst($group) }} Module
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($groupPermissions as $permission)
                                <label class="flex items-center space-x-2 text-xs text-gray-700 cursor-pointer bg-white p-2 rounded-lg border border-gray-200 hover:border-primary-300">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                    <span>{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
            <a href="{{ route('tenant.roles.index') }}" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800">Cancel</a>
            <button type="submit" class="px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold rounded-lg shadow-sm">
                Save Role
            </button>
        </div>
    </form>
</div>
@endsection
