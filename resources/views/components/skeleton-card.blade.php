@props(['rows' => 3])

<div {{ $attributes->merge(['class' => 'skeleton-card']) }}>
    <div class="skeleton-header">
        <div class="skeleton-circle"></div>
        <div class="flex-1 space-y-2">
            <div class="skeleton-text"></div>
            <div class="skeleton-text skeleton-text-short"></div>
        </div>
    </div>
    <div class="space-y-3">
        @for ($i = 0; $i < $rows; $i++)
            <div class="skeleton-box h-20"></div>
        @endfor
    </div>
</div>

