@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center w-full px-4 py-2 text-sm font-semibold leading-5 text-secondary-200 hover:text-primary-200 bg-secondary-700 hover:bg-secondary-700 focus:outline-none focus:bg-secondary-700 focus:text-primary-200 transition duration-150 ease-in-out'
            : 'flex items-center w-full px-4 py-2 text-sm font-semibold leading-5 text-secondary-200 hover:text-primary-200 hover:bg-secondary-700 focus:outline-none focus:bg-secondary-700 focus:text-primary-200 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

