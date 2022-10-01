@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-block items-center py-2 text-sm font-semibold leading-5 text-secondary-100 focus:outline-none cursor-pointer focus:text-primary-200 transition duration-150 ease-in-out'
            : 'inline-block items-center py-2 text-sm font-semibold leading-5 text-secondary-400 cursor-pointer hover:text-secondary-100 focus:outline-none focus:text-secondary-200 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
