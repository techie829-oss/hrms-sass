@props(['id' => 'select-' . uniqid(), 'name', 'value' => '', 'placeholder' => 'Select an option...', 'options' => [], 'multiple' => false])

<div class="w-full rich-select-container">
    <select id="{{ $id }}" name="{{ $name }}{{ $multiple ? '[]' : '' }}" {{ $multiple ? 'multiple' : '' }} {{ $attributes->merge(['class' => 'w-full']) }} placeholder="{{ $placeholder }}" autocomplete="off">
        @if(!$multiple)
            <option value="">{{ $placeholder }}</option>
        @endif
        {{ $slot }}
    </select>
</div>

@pushonce('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<style>
    /* Premium Tom Select Styling to match DaisyUI/Inter */
    .rich-select-container .ts-control {
        border: 1px solid #e5e7eb !important;
        border-radius: 0.75rem !important;
        padding: 0.5rem 0.75rem !important;
        font-family: 'Inter', sans-serif !important;
        font-size: 0.875rem !important;
        background-color: white !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        min-height: 2.5rem !important;
        display: flex !important;
        align-items: center !important;
    }

    .rich-select-container .ts-wrapper.focus .ts-control {
        border-color: #3b82f6 !important; /* blue-500 */
        outline: none !important;
        ring: 2px !important;
        ring-color: #3b82f6 !important;
    }

    .rich-select-container .ts-dropdown {
        border: 1px solid #e5e7eb !important;
        border-radius: 0.75rem !important;
        margin-top: 0.25rem !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        z-index: 1000 !important;
    }

    .rich-select-container .ts-dropdown .active {
        background-color: #eff6ff !important;
        color: #1d4ed8 !important;
    }

    .rich-select-container .ts-dropdown .option {
        padding: 0.5rem 0.75rem !important;
        font-size: 0.875rem !important;
    }

    /* Support for DaisyUI dark mode tokens if needed */
    [data-theme="dark"] .rich-select-container .ts-control {
        background-color: #1f2937 !important;
        border-color: #374151 !important;
        color: #f3f4f6 !important;
    }
</style>
@endpushonce

@pushonce('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all rich selects that aren't already initialized
        document.querySelectorAll('select[id^="select-"]').forEach(el => {
            if (!el.tomselect) {
                new TomSelect(el, {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    },
                    placeholder: el.getAttribute('placeholder') || 'Select an option...',
                    plugins: ['remove_button'],
                });
            }
        });
    });
</script>
@endpushonce
