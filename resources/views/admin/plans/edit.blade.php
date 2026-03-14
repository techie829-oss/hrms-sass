<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.plans.index') }}" class="btn btn-ghost btn-sm btn-square rounded-xl">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface">Edit Plan: {{ $plan->name }}</h2>
                <p class="text-sm text-on-surface-variant font-medium mt-1">Adjust limits and pricing for this subscription tier.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <form action="{{ route('admin.plans.update', $plan->id) }}" method="POST" class="bg-surface-container-lowest p-8 md:p-12 rounded-[3rem] premium-shadow border border-outline-variant/15 space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <x-input-label for="name" :value="__('Plan Name')" />
                    <x-text-input id="name" name="name" type="text" class="block w-full" :value="old('name', $plan->name)" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                
                <div class="space-y-2 flex items-center pt-8">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="toggle toggle-primary" {{ $plan->is_active ? 'checked' : '' }} />
                        <span class="ml-3 text-sm font-bold text-on-surface uppercase tracking-widest">Plan Active</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <x-input-label for="price_monthly" :value="__('Monthly Price (₹)')" />
                    <x-text-input id="price_monthly" name="price_monthly" type="number" step="0.01" class="block w-full" :value="old('price_monthly', $plan->price_monthly)" required />
                    <x-input-error :messages="$errors->get('price_monthly')" class="mt-2" />
                </div>
                <div class="space-y-2">
                    <x-input-label for="price_yearly" :value="__('Yearly Price (₹)')" />
                    <x-text-input id="price_yearly" name="price_yearly" type="number" step="0.01" class="block w-full" :value="old('price_yearly', $plan->price_yearly)" required />
                    <x-input-error :messages="$errors->get('price_yearly')" class="mt-2" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <x-input-label for="max_employees" :value="__('Max Employees (-1 for unlimited)')" />
                    <x-text-input id="max_employees" name="max_employees" type="number" class="block w-full" :value="old('max_employees', $plan->max_employees)" required />
                    <x-input-error :messages="$errors->get('max_employees')" class="mt-2" />
                </div>
                <div class="space-y-2">
                    <x-input-label for="max_modules" :value="__('Max Modules (-1 for unlimited)')" />
                    <x-text-input id="max_modules" name="max_modules" type="number" class="block w-full" :value="old('max_modules', $plan->max_modules)" required />
                    <x-input-error :messages="$errors->get('max_modules')" class="mt-2" />
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-6 border-t border-outline-variant/10">
                <a href="{{ route('admin.plans.index') }}" class="btn btn-ghost rounded-xl font-bold uppercase tracking-widest text-xs px-8">Cancel</a>
                <button type="submit" class="btn btn-primary primary-gradient border-none rounded-xl font-bold uppercase tracking-widest text-xs px-8 shadow-lg">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
