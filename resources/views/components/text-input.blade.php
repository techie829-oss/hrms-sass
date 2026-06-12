@props(['disabled' => false])

@php
    $classes = saas_tenant('id')
        ? 'bg-white border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 placeholder-slate-400/75 transition-all shadow-sm'
        : 'bg-surface-container-low border-transparent focus:border-primary focus:ring-0 rounded-xl p-3 text-on-surface placeholder-outline/50 transition-all font-medium';
@endphp

<input @disabled($disabled) {{ $attributes->merge(['class' => $classes]) }}>
