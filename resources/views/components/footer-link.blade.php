@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-block items-center py-2 text-sm font-semibold leading-5 text-gray-100 focus:outline-none cursor-pointer focus:text-primary-200 transition duration-150 ease-in-out'
            : 'inline-block items-center py-2 text-sm font-semibold leading-5 text-gray-400 cursor-pointer hover:text-gray-100 focus:outline-none focus:text-gray-200 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
