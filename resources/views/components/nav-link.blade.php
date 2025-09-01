@props(['active' => false])

<a
    {{ $attributes->class([
        'inline-flex items-center px-3 py-2 rounded-lg transition-colors duration-200 focus:outline-none',
    ]) }}>
    {{ $slot }}
</a>
