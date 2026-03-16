<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-ghost btn-outline btn-sm text-xs font-bold uppercase tracking-wider rounded-lg border-outline-variant/30 text-on-surface hover:bg-surface-container-low transition-all']) }}>
    {{ $slot }}
</button>
