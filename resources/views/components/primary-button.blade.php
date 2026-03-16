<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary btn-sm text-xs font-bold uppercase tracking-wider rounded-lg shadow-sm border-none']) }}>
    {{ $slot }}
</button>
