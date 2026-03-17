<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.tenants.show', $tenant) }}" class="btn btn-ghost btn-sm btn-square rounded-xl">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface uppercase">Refine Sanctuary</h2>
                <p class="text-sm text-on-surface-variant font-medium mt-1">Modify the foundational logic of {{ $tenant->name }}.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('admin.tenants.update', $tenant) }}" method="POST" class="space-y-8">
            @csrf
            @method('PATCH')
            
            <!-- Company Intel -->
            <div class="bg-surface-container-lowest p-8 md:p-12 rounded-[3rem] shadow-xl border border-outline-variant/15 space-y-10">
                <div class="flex items-center gap-4 border-b border-outline-variant/10 pb-6">
                    <span class="material-symbols-outlined text-primary text-3xl">edit_note</span>
                    <h3 class="font-black font-headline text-xl uppercase tracking-widest text-on-surface">Basic Information</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <x-input-label for="name" :value="__('Organizational Name')" />
                        <x-text-input id="name" name="name" class="block w-full" type="text" :value="old('name', $tenant->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="space-y-2">
                        <x-input-label for="email" :value="__('Primary Admin Email')" />
                        <x-text-input id="email" name="email" class="block w-full" type="email" :value="old('email', $tenant->email)" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="space-y-2">
                        <x-input-label for="contact_no" :value="__('Contact Number')" />
                        <x-text-input id="contact_no" name="contact_no" class="block w-full" type="text" :value="old('contact_no', $tenant->contact_no)" required />
                        <x-input-error :messages="$errors->get('contact_no')" class="mt-2" />
                    </div>
                </div>

                <div class="space-y-2 opacity-60">
                    <x-input-label for="id" :value="__('Tenant ID / Slug (Read-only)')" />
                    <x-text-input id="id" class="block w-full bg-base-200 cursor-not-allowed" type="text" :value="$tenant->id" readonly />
                    <p class="text-[10px] text-on-surface-variant font-bold uppercase tracking-widest mt-2">Architecture slugs are permanent once provisioned.</p>
                </div>
            </div>

            <!-- Deployment Intel (Read-only in Edit) -->
            <div class="bg-surface-container-low p-8 md:p-12 rounded-[3rem] shadow-xl border border-outline-variant/10 space-y-10">
                <div class="flex items-center gap-4 border-b border-outline-variant/10 pb-6">
                    <span class="material-symbols-outlined text-secondary text-3xl">info</span>
                    <h3 class="font-black font-headline text-xl uppercase tracking-widest text-on-surface">Infrastructure Identity</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2 opacity-60">
                        <label class="block font-bold text-xs uppercase tracking-widest text-on-surface-variant mb-2">Current Mode</label>
                        <div class="bg-surface-container-lowest p-4 rounded-xl font-black uppercase tracking-widest text-xs border border-outline-variant/10">
                            {{ $tenant->mode }} Sanctuary
                        </div>
                    </div>
                    <div class="space-y-2 opacity-60">
                        <label class="block font-bold text-xs uppercase tracking-widest text-on-surface-variant mb-2">Active Plan</label>
                        <div class="bg-surface-container-lowest p-4 rounded-xl font-black uppercase tracking-widest text-xs border border-outline-variant/10">
                            {{ $tenant->plan_id }} Licensing
                        </div>
                    </div>
                </div>
                <p class="text-[10px] text-on-surface-variant font-bold uppercase tracking-widest text-center italic">Deployment mode and Plan must be modified through the central hub for safety.</p>
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-4 pt-6">
                <a href="{{ route('admin.tenants.show', $tenant) }}" class="btn btn-ghost rounded-2xl font-black uppercase tracking-widest text-xs px-10">Discard</a>
                <button type="submit" class="btn btn-primary bg-gradient-to-br from-primary to-tertiary border-none rounded-2xl font-black uppercase tracking-[0.2em] text-xs h-auto py-5 px-12 shadow-xl hover:scale-105 transition-transform">
                    Update Sanctuary Log
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
