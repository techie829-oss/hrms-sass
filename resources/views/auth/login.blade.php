<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Welcome Back</h2>
        @if(isset($activeTenant) && $activeTenant)
            <div class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold border border-indigo-100">
                {{ $activeTenant->name ?? 'Tenant Portal' }}
            </div>
        @else
            <div class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold border border-slate-200">
                Super Admin Portal
            </div>
        @endif
        <p class="text-slate-500 mt-2 text-sm">Sign in to your account to continue.</p>
    </div>

    {{-- Demo Tenant Quick Login --}}
    @if(isset($activeTenant) && $activeTenant && $activeTenant->id === 'demo')
        <div class="mb-6 p-4 rounded-xl border border-amber-200 bg-amber-50">
            <p class="text-xs font-bold text-amber-700 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">bolt</span>
                Demo — Quick Login
            </p>
            <div class="grid grid-cols-3 gap-2">
                <button type="button"
                    onclick="fillDemo('admin@demo.com', 'password')"
                    class="py-2 px-1 text-xs font-semibold text-indigo-700 bg-white border border-indigo-200 rounded-lg hover:bg-indigo-50 transition-colors text-center shadow-sm">
                    🛡️ Admin
                </button>
                <button type="button"
                    onclick="fillDemo('arjun@demo.com', 'password')"
                    class="py-2 px-1 text-xs font-semibold text-emerald-700 bg-white border border-emerald-200 rounded-lg hover:bg-emerald-50 transition-colors text-center shadow-sm">
                    👔 Manager
                </button>
                <button type="button"
                    onclick="fillDemo('ravi@demo.com', 'password')"
                    class="py-2 px-1 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors text-center shadow-sm">
                    👤 Employee
                </button>
            </div>
            <p class="text-[10px] text-amber-600 mt-2 text-center">Password: <strong>password</strong></p>
        </div>
    @endif



    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2">
            <x-input-label for="email" :value="__('Email Address')" class="text-slate-700 font-semibold" />
            <x-text-input id="email" class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm sm:text-sm" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="name@company.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <x-input-label for="password" :value="__('Password')" class="text-slate-700 font-semibold" />
            <x-text-input id="password" class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm sm:text-sm" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm font-medium text-slate-600 group-hover:text-slate-900 transition-colors">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-indigo-600 hover:text-indigo-500 hover:underline transition-colors" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                {{ __('Sign In') }}
            </button>
        </div>
    </form>
</x-guest-layout>

<script>
function fillDemo(email, password) {
    var emailInput = document.getElementById('email');
    var passInput  = document.getElementById('password');
    if (emailInput) emailInput.value = email;
    if (passInput)  passInput.value  = password;
    // Highlight the filled fields briefly
    [emailInput, passInput].forEach(function(el) {
        if (!el) return;
        el.classList.add('ring-2', 'ring-amber-400');
        setTimeout(function() { el.classList.remove('ring-2', 'ring-amber-400'); }, 1500);
    });
}
</script>

