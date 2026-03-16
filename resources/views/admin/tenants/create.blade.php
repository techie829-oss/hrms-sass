<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.tenants.index') }}" class="btn btn-ghost btn-sm btn-square rounded-xl">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface uppercase">Provision Sanctuary</h2>
                <p class="text-sm text-on-surface-variant font-medium mt-1">Architect a new organizational space in the ecosystem.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('admin.tenants.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- Company Intel -->
            <div class="bg-surface-container-lowest p-8 md:p-12 rounded-[3rem] shadow-xl border border-outline-variant/15 space-y-10">
                <div class="flex items-center gap-4 border-b border-outline-variant/10 pb-6">
                    <span class="material-symbols-outlined text-primary text-3xl">corporate_fare</span>
                    <h3 class="font-black font-headline text-xl uppercase tracking-widest text-on-surface">Company Intel</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <x-input-label for="name" :value="__('Organizational Name')" />
                        <x-text-input id="name" name="name" class="block w-full" type="text" required placeholder="Lumina Tech" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="space-y-2">
                        <x-input-label for="email" :value="__('Primary Admin Email')" />
                        <x-text-input id="email" name="email" class="block w-full" type="email" required placeholder="admin@lumina.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="space-y-2">
                        <x-input-label for="contact_no" :value="__('Contact Number')" />
                        <x-text-input id="contact_no" name="contact_no" class="block w-full" type="text" required placeholder="+91 98765-43210" />
                        <x-input-error :messages="$errors->get('contact_no')" class="mt-2" />
                    </div>
                </div>

                <div class="space-y-2">
                    <x-input-label for="subdomain" :value="__('Subdomain / Architectural Slug')" />
                    <div class="flex items-stretch mt-1">
                        <x-text-input id="subdomain" name="subdomain" class="block w-full rounded-r-none uppercase font-black tracking-widest !mt-0" type="text" required placeholder="Lumina" />
                        <span class="inline-flex items-center px-6 rounded-r-xl border border-l-0 border-outline-variant/15 bg-surface-container-low text-on-surface-variant text-sm font-black whitespace-nowrap">.{{ parse_url(config('app.url'), PHP_URL_HOST) ?? config('app.url') }}</span>
                    </div>
                    <p class="text-[10px] text-on-surface-variant/60 font-bold uppercase tracking-widest mt-2">Only letters, numbers, and dashes. No spaces.</p>
                    <x-input-error :messages="$errors->get('subdomain')" class="mt-2" />
                </div>
            </div>

            <!-- Infrastructure & Licensing -->
            <div class="bg-surface-container-low p-8 md:p-12 rounded-[3rem] shadow-xl border border-outline-variant/10 space-y-10">
                <div class="flex items-center gap-4 border-b border-outline-variant/10 pb-6">
                    <span class="material-symbols-outlined text-secondary text-3xl">storage</span>
                    <h3 class="font-black font-headline text-xl uppercase tracking-widest text-on-surface">Infrastructure</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <x-input-label for="mode" :value="__('Deployment Mode')" />
                        <select id="mode" name="mode" class="w-full bg-surface-container-lowest border-transparent focus:border-primary focus:ring-0 rounded-xl p-3 text-on-surface transition-all font-bold uppercase tracking-widest text-xs h-12">
                            <option value="shared">Shared Sanctuary (Standard)</option>
                            <option value="dedicated">Dedicated Isolation (Enterprise)</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <x-input-label for="plan_id" :value="__('Licensing Plan')" />
                        <select id="plan_id" name="plan_id" class="w-full bg-surface-container-lowest border-transparent focus:border-primary focus:ring-0 rounded-xl p-3 text-on-surface transition-all font-bold uppercase tracking-widest text-xs h-12">
                            <option value="free">Foundational (Free)</option>
                            <option value="starter">Growth (Starter)</option>
                            <option value="professional" selected>Architect (Professional)</option>
                            <option value="enterprise">Omni (Enterprise)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-4 pt-6">
                <a href="{{ route('admin.tenants.index') }}" class="btn btn-ghost rounded-2xl font-black uppercase tracking-widest text-xs px-10">Cancel</a>
                <button type="submit" class="btn btn-primary bg-gradient-to-br from-primary to-tertiary border-none rounded-2xl font-black uppercase tracking-[0.2em] text-xs h-auto py-5 px-12 shadow-xl hover:scale-105 transition-transform">
                    Initialize Provisioning
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
