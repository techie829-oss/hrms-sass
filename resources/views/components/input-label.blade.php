@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-xs font-bold text-on-surface uppercase tracking-widest font-label']) }}>
    {{ $value ?? $slot }}
</label>
