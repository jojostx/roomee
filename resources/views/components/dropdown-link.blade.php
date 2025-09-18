@props(['active' => false])

@php
$classes = ($active)
            ? 'block px-4 py-3 leading-5 text-secondary-700 bg-secondary-100 border-l-4 border-l-secondary-400 focus:outline-none'
            : 'block px-4 py-3 leading-5 text-secondary-700 hover:bg-secondary-100 focus:outline-none focus:bg-secondary-100 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
   {{ $slot }}
</a>
