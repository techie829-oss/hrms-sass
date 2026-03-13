@props([
    'id' => 'modal-' . uniqid(),
    'title' => '',
    'size' => 'md',
])

@php
    $modalClass = match($size) {
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
        default => 'max-w-md',
    };
@endphp

<dialog id="{{ $id }}" {{ $attributes->merge(['class' => 'modal']) }}>
    <div class="modal-box {{ $modalClass }}">
        @if($title)
            <h3 class="font-bold text-lg">{{ $title }}</h3>
        @endif

        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                onclick="document.getElementById('{{ $id }}').close()">✕</button>

        <div class="py-4">
            {{ $slot }}
        </div>

        @isset($actions)
            <div class="modal-action">
                {{ $actions }}
            </div>
        @endisset
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
