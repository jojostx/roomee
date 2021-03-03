@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center mx-2 px-1 pt-1 border-b-2 border-blue-400 text-sm font-semibold leading-5 text-gray-200 focus:outline-none focus:border-blue-500 transition duration-150 ease-in-out'
            : 'inline-flex items-center mx-2 px-1 pt-1 border-b-2 border-transparent text-sm font-semibold leading-5 text-gray-300 hover:text-gray-200 hover:border-gray-300 focus:outline-none focus:text-gray-200 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
