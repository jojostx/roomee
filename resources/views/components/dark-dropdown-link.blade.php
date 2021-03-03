@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center w-full px-4 py-2 text-sm font-semibold leading-5 text-gray-200 hover:text-blue-200 bg-gray-700 hover:bg-gray-700 focus:outline-none focus:bg-gray-200 focus:text-blue-200 transition duration-150 ease-in-out'
            : 'flex items-center w-full px-4 py-2 text-sm font-semibold leading-5 text-gray-200 hover:text-blue-200 hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-blue-200 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

