@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'disabled' => false,
])

@php
    $classes = match($variant) {
        'primary' => 'btn btn-primary',
        'secondary' => 'btn btn-secondary',
        'accent' => 'btn btn-accent',
        'ghost' => 'btn btn-ghost',
        'error' => 'btn btn-error',
        'success' => 'btn btn-success',
        'warning' => 'btn btn-warning',
        'info' => 'btn btn-info',
        'outline' => 'btn btn-outline',
        default => 'btn btn-primary',
    };

    $sizeClass = match($size) {
        'xs' => 'btn-xs',
        'sm' => 'btn-sm',
        'md' => '',
        'lg' => 'btn-lg',
        default => '',
    };
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "$classes $sizeClass"]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => "$classes $sizeClass"]) }} @disabled($disabled)>
        {{ $slot }}
    </button>
@endif
