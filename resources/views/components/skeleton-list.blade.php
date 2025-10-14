@props(['items' => 5])

<div {{ $attributes->merge(['class' => 'space-y-3']) }}>
    @for ($i = 0; $i < $items; $i++)
        <div class="flex items-center gap-4 p-4 card">
            <div class="skeleton-circle"></div>
            <div class="flex-1 space-y-2">
                <div class="skeleton-text" style="width: {{ rand(60, 90) }}%;"></div>
                <div class="skeleton-text skeleton-text-short"></div>
            </div>
            <div class="skeleton-box" style="width: 80px; height: 32px;"></div>
        </div>
    @endfor
</div>

