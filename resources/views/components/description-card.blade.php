@props(['content' => null, 'maxWidth' => null])

<section class="mt-4 md:mt-6 mb-6 md:mb-8">
    <div class="card p-6 sm:p-8 {{ $maxWidth ? $maxWidth : 'w-full' }}">
        <div class="prose prose-invert max-w-none prose-a:text-primary-300 hover:prose-a:text-primary-200 prose-code:text-warning-300">
            @if(!is_null($content))
                {!! $content !!}
            @else
                {{ $slot }}
            @endif
        </div>
    </div>
    {{-- Expects sanitized HTML in `content`; fallback to slot for plain content. --}}
</section>


