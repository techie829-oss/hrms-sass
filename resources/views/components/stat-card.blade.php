@props([
    'title' => '',
    'value' => '',
    'description' => '',
    'icon' => null,
    'trend' => null,
    'trendUp' => true,
])

<div {{ $attributes->merge(['class' => 'stat']) }}>
    @if($icon)
        <div class="stat-figure text-primary">
            {!! $icon !!}
        </div>
    @endif

    <div class="stat-title">{{ $title }}</div>
    <div class="stat-value">{{ $value }}</div>

    @if($description || $trend)
        <div class="stat-desc {{ $trendUp ? 'text-success' : 'text-error' }}">
            @if($trend)
                {{ $trendUp ? '↑' : '↓' }} {{ $trend }}
            @endif
            {{ $description }}
        </div>
    @endif
</div>
