@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'block w-full pl-4 pr-4 py-3 border-l-2 border-primary-500 text-start text-base font-medium text-white bg-dark-700/60 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/30 transition-colors duration-200'
            : 'block w-full pl-4 pr-4 py-3 border-l-2 border-transparent text-start text-base font-medium text-slate-300 hover:text-white hover:bg-dark-700/50 hover:border-dark-600/60 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 transition-colors duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
