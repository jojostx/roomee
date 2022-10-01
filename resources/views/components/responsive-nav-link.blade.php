@props([
    'active' => false,
    'icon_before' => null,
    'icon_after' => 'heroicon-o-chevron-right',
])

@php
    $classes = ($active ?? false)
        ? 'cursor-pointer flex items-center pl-3 pr-4 py-2 border-l-4 border-primary-400 text-base font-medium text-primary-700 bg-primary-50 focus:outline-none focus:text-primary-800 focus:bg-primary-100 focus:border-primary-700 transition duration-150 ease-in-out'
        : 'cursor-pointer flex items-center pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-secondary-600 hover:text-secondary-800 hover:bg-secondary-50 hover:border-secondary-300 focus:outline-none focus:text-secondary-800 focus:bg-secondary-50 focus:border-secondary-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    @if (filled($icon_before)) 
    <span
        @class([
            'inline p-2 mr-4 rounded-full w-9 h-9', 
            'bg-secondary-100' => !$active, 
            'bg-primary-100' => $active
        ]) >
        <x-dynamic-component :component="$icon_before"/>
    </span>
    @endif

    {{ $slot }}

    @if (filled($icon_after))        
    <span @class(['inline w-5 h-5 ml-auto'])>
        <x-dynamic-component :component="$icon_after"/>
    </span>
    @endif
</a>