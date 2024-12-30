@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-2 border-b-2 border-primary text-sm font-medium leading-5 text-primary focus:outline-none focus:border-primary-dark transition-colors duration-300'
            : 'inline-flex items-center px-3 py-2 border-b-2 border-transparent text-sm font-medium leading-5 text-text hover:text-primary hover:border-primary focus:outline-none focus:text-primary focus:border-primary transition-colors duration-300';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

