<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.plans.index') }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Plan: {{ $plan->name }}</h2>
                <p class="text-sm text-slate-500 mt-1">Adjust limits and pricing for this subscription tier.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <form action="{{ route('admin.plans.update', $plan->slug) }}" method="POST" class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 sm:p-8 space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <x-input-label for="name" :value="__('Plan Name')" class="text-slate-700 font-semibold" />
                    <x-text-input id="name" name="name" type="text" class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" :value="old('name', $plan->name)" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                
                <div class="space-y-2 flex items-center pt-8">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ $plan->is_active ? 'checked' : '' }}>
                        <div class="relative w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        <span class="ms-3 text-sm font-medium text-slate-700">Plan Active</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <x-input-label for="price_monthly" :value="__('Monthly Price (₹)')" class="text-slate-700 font-semibold" />
                    <x-text-input id="price_monthly" name="price_monthly" type="number" step="0.01" class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" :value="old('price_monthly', $plan->price_monthly)" required />
                    <x-input-error :messages="$errors->get('price_monthly')" class="mt-2" />
                </div>
                <div class="space-y-2">
                    <x-input-label for="price_yearly" :value="__('Yearly Price (₹)')" class="text-slate-700 font-semibold" />
                    <x-text-input id="price_yearly" name="price_yearly" type="number" step="0.01" class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" :value="old('price_yearly', $plan->price_yearly)" required />
                    <x-input-error :messages="$errors->get('price_yearly')" class="mt-2" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <x-input-label for="max_employees" :value="__('Max Employees (-1 for unlimited)')" class="text-slate-700 font-semibold" />
                    <x-text-input id="max_employees" name="max_employees" type="number" class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" :value="old('max_employees', $plan->max_employees)" required />
                    <x-input-error :messages="$errors->get('max_employees')" class="mt-2" />
                </div>
                <div class="space-y-2">
                    <x-input-label for="max_modules" :value="__('Max Modules (-1 for unlimited)')" class="text-slate-700 font-semibold" />
                    <x-text-input id="max_modules" name="max_modules" type="number" class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" :value="old('max_modules', $plan->max_modules)" required />
                    <x-input-error :messages="$errors->get('max_modules')" class="mt-2" />
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.plans.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
