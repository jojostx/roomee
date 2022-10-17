@props([
    'active' => false,
    'icon_before' => null,
    'icon_after' => 'heroicon-o-chevron-right',
])

@php
    $classes = ($active ?? false)
        ? 'responsive-nav-link active'
        : 'responsive-nav-link';
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