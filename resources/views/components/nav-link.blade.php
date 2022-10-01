@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center mx-2 px-1 pt-1 border-b-2 border-primary-400 text-sm font-semibold leading-5 text-secondary-200 focus:outline-none focus:border-primary-500 transition duration-150 ease-in-out'
            : 'inline-flex items-center mx-2 px-1 pt-1 border-b-2 border-transparent text-sm font-semibold leading-5 text-primary-400 hover:text-primary-500 hover:border-primary-500 focus:outline-none focus:text-secondary-200 focus:border-secondary-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
