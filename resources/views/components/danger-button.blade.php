<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-error btn-sm text-xs font-bold uppercase tracking-wider rounded-lg shadow-sm border-none text-white']) }}>
    {{ $slot }}
</button>
