<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-surface-container-lowest border border-outline-variant/30 rounded-xl font-bold text-sm text-on-surface tracking-widest uppercase hover:bg-surface-container-low focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all premium-shadow disabled:opacity-50']) }}>
    {{ $slot }}
</button>
