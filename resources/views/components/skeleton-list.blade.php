@props(['items' => 5])

@php
    // Predictable width variations for visual interest without randomness
    $widths = ['w-3/4', 'w-4/5', 'w-5/6', 'w-3/4', 'w-4/5'];
@endphp

<div {{ $attributes->merge(['class' => 'space-y-3']) }}>
    @for ($i = 0; $i < $items; $i++)
        <div class="flex items-center gap-4 p-4 card">
            <div class="skeleton-circle"></div>
            <div class="flex-1 space-y-2">
                <div class="skeleton-text {{ $widths[$i % count($widths)] }}"></div>
                <div class="skeleton-text skeleton-text-short"></div>
            </div>
            <div class="skeleton-box w-20 h-8"></div>
        </div>
    @endfor
</div>

