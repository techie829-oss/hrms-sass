<x-guest-layout>
    <div class="mb-10 text-center">
        <h2 class="text-3xl font-extrabold font-headline text-on-surface tracking-tight">Welcome Back</h2>
        @if(isset($activeTenant) && $activeTenant)
            <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase tracking-widest">
                {{ $activeTenant->name ?? 'Tenant Portal' }}
            </div>
        @else
            <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full bg-tertiary/10 text-tertiary text-xs font-bold uppercase tracking-widest">
                Super Admin Portal
            </div>
        @endif
        <p class="text-on-surface-variant mt-3 italic">Enter your credentials to access your sanctuary.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="name@company.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded-lg border-outline-variant/30 text-primary shadow-sm focus:ring-primary focus:ring-offset-0 bg-surface-container-low" name="remember">
                <span class="ms-2 text-sm font-bold text-on-surface-variant uppercase tracking-widest font-label">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-bold text-primary hover:underline font-label uppercase tracking-widest" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="pt-4">
            <x-primary-button class="w-full py-4 text-sm font-black tracking-[0.2em]">
                {{ __('Secure Login') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
