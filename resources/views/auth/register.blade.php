<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Create Account</h2>
        <p class="text-slate-500 mt-2 text-sm">Start your 14-day Professional trial today.</p>
    </div>

    <form method="POST" action="{{ route('central.contact') }}" class="space-y-6">
        @csrf

        <!-- Company Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-slate-50 rounded-xl border border-slate-200 mb-8">
            <div class="space-y-2">
                <x-input-label for="company_name" :value="__('Company Name')" class="text-slate-700 font-semibold" />
                <x-text-input id="company_name" class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm sm:text-sm" type="text" name="company_name" :value="old('company_name')" required autofocus placeholder="Acme Corp" />
                <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
            </div>

            <div class="space-y-2">
                <x-input-label for="subdomain" :value="__('Subdomain')" class="text-slate-700 font-semibold" />
                <div class="flex items-center">
                    <x-text-input id="subdomain" class="block w-full rounded-l-lg rounded-r-none border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm sm:text-sm" type="text" name="subdomain" :value="old('subdomain')" required placeholder="acme" />
                    <span class="inline-flex items-center px-4 py-2 rounded-r-lg border border-l-0 border-slate-300 bg-slate-100 text-slate-500 sm:text-sm h-[38px] mt-1">.{{ config('app.url') }}</span>
                </div>
                <x-input-error :messages="$errors->get('subdomain')" class="mt-2" />
            </div>
        </div>

        <!-- Name -->
        <div class="space-y-2">
            <x-input-label for="name" :value="__('Admin Name')" class="text-slate-700 font-semibold" />
            <x-text-input id="name" class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm sm:text-sm" type="text" name="name" :value="old('name')" required autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="space-y-2">
            <x-input-label for="email" :value="__('Work Email')" class="text-slate-700 font-semibold" />
            <x-text-input id="email" class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm sm:text-sm" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="john@company.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <x-input-label for="password" :value="__('Password')" class="text-slate-700 font-semibold" />
            <x-text-input id="password" class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm sm:text-sm" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-2">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-slate-700 font-semibold" />
            <x-text-input id="password_confirmation" class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm sm:text-sm" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                {{ __('Start Free Trial') }}
            </button>
        </div>

        <div class="text-center pt-6 border-t border-slate-100">
            <p class="text-sm text-slate-600">Already architecting with us? <a href="{{ route('login') }}" class="text-indigo-600 font-semibold hover:text-indigo-500 transition-colors">Login</a></p>
        </div>
    </form>
</x-guest-layout>
