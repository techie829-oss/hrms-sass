<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.tenants.show', $tenant) }}" class="p-2 -ml-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Tenant</h2>
                <p class="text-sm text-slate-500 mt-1">Modify the foundational details of {{ $tenant->name }}.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto pb-12">
        <form action="{{ route('admin.tenants.update', $tenant) }}" method="POST" class="space-y-8">
            @csrf
            @method('PATCH')
            
            <!-- Company Intel -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
                <div class="flex items-center gap-3 border-b border-slate-100 pb-5 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-700 shadow-sm border border-indigo-100/50">
                        <span class="material-symbols-outlined text-[20px]">edit_note</span>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Basic Information</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Company Name <span class="text-rose-500">*</span></label>
                        <input type="text" id="name" name="name" required value="{{ old('name', $tenant->name) }}" class="mt-1 block w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-slate-400 shadow-sm">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Primary Admin Email <span class="text-rose-500">*</span></label>
                        <input type="email" id="email" name="email" required value="{{ old('email', $tenant->email) }}" class="mt-1 block w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-slate-400 shadow-sm">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div>
                        <label for="contact_no" class="block text-sm font-semibold text-slate-700 mb-1">Contact Number <span class="text-rose-500">*</span></label>
                        <input type="text" id="contact_no" name="contact_no" required value="{{ old('contact_no', $tenant->contact_no) }}" class="mt-1 block w-full rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-slate-400 shadow-sm">
                        <x-input-error :messages="$errors->get('contact_no')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6">
                    <label for="id" class="block text-sm font-semibold text-slate-700 mb-1">Tenant ID / Subdomain (Read-only)</label>
                    <input type="text" id="id" value="{{ $tenant->id }}" readonly class="mt-1 block w-full rounded-xl border-slate-300 bg-slate-50 text-slate-500 text-sm cursor-not-allowed shadow-sm">
                    <p class="mt-2 text-xs text-slate-500">Subdomains are permanent once provisioned.</p>
                </div>
            </div>

            <!-- Deployment Intel (Read-only in Edit) -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
                <div class="flex items-center gap-3 border-b border-slate-100 pb-5 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 shadow-sm border border-slate-200/50">
                        <span class="material-symbols-outlined text-[20px]">info</span>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Infrastructure Identity</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Current Mode</label>
                        <div class="mt-1 w-full px-3 py-2.5 rounded-xl border border-slate-300 bg-slate-50 text-sm text-slate-700 font-medium shadow-sm">
                            {{ ucfirst($tenant->mode) }} Infrastructure
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Active Plan</label>
                        <div class="mt-1 w-full px-3 py-2.5 rounded-xl border border-slate-300 bg-slate-50 text-sm text-slate-700 font-medium shadow-sm">
                            {{ ucfirst($tenant->plan_id) }} Licensing
                        </div>
                    </div>
                </div>
                <p class="mt-4 text-xs text-slate-500 text-center italic">Deployment mode and Plan must be modified through the tenant overview view for safety.</p>
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('admin.tenants.show', $tenant) }}" class="inline-flex justify-center py-2 px-4 border border-slate-300 rounded-xl shadow-sm text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Discard Changes
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Update Tenant
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
