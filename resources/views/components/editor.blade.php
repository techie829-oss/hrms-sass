@props(['id' => 'editor-' . uniqid(), 'name', 'value' => '', 'placeholder' => ''])

<div class="w-full">
    <input id="{{ $id }}" type="hidden" name="{{ $name }}" value="{{ $value }}">
    <trix-editor input="{{ $id }}" class="trix-content bg-white min-h-[150px] focus:outline-none" placeholder="{{ $placeholder }}"></trix-editor>
</div>

@pushonce('styles')
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<style>
    /* Premium Editor Styling */
    trix-toolbar .trix-button-group--file-tools { display: none !important; }
    
    trix-editor { 
        border: 1px solid #e5e7eb !important; /* light gray border */
        border-radius: 0.75rem !important;
        padding: 1.25rem !important;
        background-color: white !important;
        color: #1f2937 !important;
        font-family: 'Inter', sans-serif !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
    }

    trix-editor:focus {
        border-color: #3b82f6 !important; /* blue-500 */
        outline: none !important;
        ring: 2px !important;
        ring-color: #3b82f6 !important;
    }

    /* Trix Toolbar Customization */
    trix-toolbar {
        margin-bottom: 0.5rem !important;
    }

    trix-toolbar .trix-button-group {
        border: 1px solid #e5e7eb !important;
        border-radius: 0.5rem !important;
        background: #f9fafb !important;
        margin-bottom: 0px !important;
    }

    trix-toolbar .trix-button {
        border-bottom: none !important;
    }

    trix-toolbar .trix-button--active {
        background: #eff6ff !important;
        color: #1d4ed8 !important;
    }
</style>
@endpushonce

@pushonce('scripts')
<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
@endpushonce
