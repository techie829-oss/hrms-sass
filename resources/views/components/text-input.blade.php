@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-surface-container-low border-transparent focus:border-primary focus:ring-0 rounded-xl p-3 text-on-surface placeholder-outline/50 transition-all font-medium']) }}>
