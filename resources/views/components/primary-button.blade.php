<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 primary-gradient border border-transparent rounded-xl font-bold text-sm text-on-primary tracking-widest uppercase hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all premium-shadow disabled:opacity-50']) }}>
    {{ $slot }}
</button>
