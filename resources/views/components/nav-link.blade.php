@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-4 border-primary text-sm font-black leading-5 text-on-surface focus:outline-none transition duration-150 ease-in-out uppercase tracking-[0.2em] font-label'
            : 'inline-flex items-center px-1 pt-1 border-b-4 border-transparent text-sm font-bold leading-5 text-on-surface-variant hover:text-on-surface hover:border-outline-variant focus:outline-none focus:text-on-surface focus:border-outline-variant transition duration-150 ease-in-out uppercase tracking-[0.2em] font-label';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
