<x-guest-layout>
    <div class="mb-10 text-center">
        <h2 class="text-3xl font-extrabold font-headline text-on-surface tracking-tight">Create Account</h2>
        <p class="text-on-surface-variant mt-2">Start your 14-day Professional trial today.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Company Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-primary/5 rounded-2xl border border-primary/10 mb-8">
            <div class="space-y-2">
                <x-input-label for="company_name" :value="__('Company Name')" />
                <x-text-input id="company_name" class="block mt-1 w-full" type="text" name="company_name" :value="old('company_name')" required autofocus placeholder="Acme Corp" />
                <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
            </div>

            <div class="space-y-2">
                <x-input-label for="subdomain" :value="__('Subdomain')" />
                <div class="flex items-center">
                    <x-text-input id="subdomain" class="block mt-1 w-full rounded-r-none" type="text" name="subdomain" :value="old('subdomain')" required placeholder="acme" />
                    <span class="mt-1 inline-flex items-center px-4 py-3 rounded-r-xl border border-l-0 border-outline-variant/15 bg-surface-container-low text-on-surface-variant text-sm font-bold">.{{ config('app.url') }}</span>
                </div>
                <x-input-error :messages="$errors->get('subdomain')" class="mt-2" />
            </div>
        </div>

        <!-- Name -->
        <div class="space-y-2">
            <x-input-label for="name" :value="__('Admin Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="space-y-2">
            <x-input-label for="email" :value="__('Work Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="john@company.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-2">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-4">
            <x-primary-button class="w-full py-4 text-sm font-black tracking-[0.2em]">
                {{ __('Start Free Trial') }}
            </x-primary-button>
        </div>

        <div class="text-center pt-6 border-t border-outline-variant/10">
            <p class="text-sm text-on-surface-variant">Already architecting with us? <a href="{{ route('login') }}" class="text-primary font-bold hover:underline">Login</a></p>
        </div>
    </form>
</x-guest-layout>
