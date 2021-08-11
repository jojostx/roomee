@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center mx-2 px-1 pt-1 text-sm md:text-base font-semibold leading-5 text-blue-50 focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center mx-2 px-1 pt-1 text-sm md:text-base font-semibold leading-5 text-blue-200 hover:text-blue-300 focus:outline-none focus:text-blue-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
