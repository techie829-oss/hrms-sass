<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.tenants.index') }}" class="p-2 -ml-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Provision Tenant</h2>
                <p class="text-sm text-slate-500 mt-1">Create a new organization and initialize their workspace.</p>
            </div>
        </div>
    </x-slot>

    <div class="w-full pb-12">
        <form action="{{ route('admin.tenants.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- Company Intel -->
            <div class="card-crm p-6 md:p-8">
                <div class="flex items-center gap-3 border-b border-outline-variant/50 pb-5 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary shadow-sm border border-primary/20">
                        <span class="material-symbols-outlined text-[20px]">corporate_fare</span>
                    </div>
                    <h3 class="text-lg font-bold text-on-surface">Company Details</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="label-crm">
                            <span class="label-text-crm">Company Name <span class="text-error">*</span></span>
                        </label>
                        <input type="text" id="name" name="name" required placeholder="e.g. Acme Corp" class="input-crm">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <label for="email" class="label-crm">
                            <span class="label-text-crm">Primary Admin Email <span class="text-error">*</span></span>
                        </label>
                        <input type="email" id="email" name="email" required placeholder="admin@acmecorp.com" class="input-crm">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div>
                        <label for="contact_no" class="label-crm">
                            <span class="label-text-crm">Contact Number <span class="text-error">*</span></span>
                        </label>
                        <input type="text" id="contact_no" name="contact_no" required placeholder="+1 (555) 000-0000" class="input-crm">
                        <x-input-error :messages="$errors->get('contact_no')" class="mt-2" />
                    </div>
                    <div>
                        <label for="subdomain" class="label-crm">
                            <span class="label-text-crm">Workspace URL (Subdomain) <span class="text-error">*</span></span>
                        </label>
                        <div class="flex mt-1">
                            <input type="text" id="subdomain" name="subdomain" required placeholder="acme" class="input-crm rounded-r-none flex-1">
                            <span class="inline-flex items-center px-4 border border-l-0 border-outline-variant bg-surface-container-low text-on-surface-variant font-bold text-xs uppercase tracking-wider rounded-r-lg">
                                .{{ parse_url(config('app.url'), PHP_URL_HOST) ?? config('app.url') }}
                            </span>
                        </div>
                        <p class="mt-2 text-xs text-on-surface-variant/70">Only letters, numbers, and dashes. No spaces.</p>
                        <x-input-error :messages="$errors->get('subdomain')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Infrastructure & Licensing -->
            <div class="card-crm p-6 md:p-8">
                <div class="flex items-center gap-3 border-b border-outline-variant/50 pb-5 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-info/10 flex items-center justify-center text-info shadow-sm border border-info/20">
                        <span class="material-symbols-outlined text-[20px]">storage</span>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Infrastructure & Licensing</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="mode" class="label-crm">
                            <span class="label-text-crm">Deployment Mode</span>
                        </label>
                        <select id="mode" name="mode" class="select-crm">
                            <option value="shared">Shared Infrastructure (Standard)</option>
                            <option value="dedicated">Dedicated Database (Enterprise)</option>
                        </select>
                    </div>
                    <div>
                        <label for="plan_id" class="label-crm">
                            <span class="label-text-crm">Licensing Plan</span>
                        </label>
                        <select id="plan_id" name="plan_id" class="select-crm">
                            <option value="free">Foundational (Free)</option>
                            <option value="starter">Growth (Starter)</option>
                            <option value="professional" selected>Architect (Professional)</option>
                            <option value="enterprise">Omni (Enterprise)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('admin.tenants.index') }}" class="inline-flex justify-center py-2 px-4 border border-slate-300 rounded-xl shadow-sm text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Provision Tenant
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
