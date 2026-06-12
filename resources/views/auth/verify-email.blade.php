<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Verify Your Email</h2>
        <p class="text-slate-500 mt-2 text-sm leading-relaxed">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 rounded-lg bg-green-50 text-sm font-medium text-green-800 border border-green-200">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route((saas_tenant() ? 'tenant.' : '') . 'verification.send') }}">
            @csrf
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                {{ __('Resend Verification Email') }}
            </button>
        </form>

        <form method="POST" action="{{ route((saas_tenant() ? 'tenant.' : '') . 'logout') }}" class="text-center pt-4 border-t border-slate-100">
            @csrf
            <button type="submit" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors focus:outline-none focus:underline">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
