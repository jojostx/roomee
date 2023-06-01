@props([
    'active' => false,
    'icon_before' => null,
    'icon_after' => 'heroicon-o-chevron-right',
])

@php
    $classes = ($active ?? false)
        ? ' active '
        : '  ';
@endphp

<a 
    {{ $attributes->merge(['class' => 'responsive-nav-link flex items-center px-2 py-4'. $classes]) }}>
    @if (filled($icon_before)) 
    <span
        @class([
            'inline p-2 mr-4 rounded-full', 
            'bg-secondary-100' => !$active, 
            'bg-primary-100' => $active
        ]) >
        <x-dynamic-component :component="$icon_before" class="w-5 h-5"/>
    </span>
    @endif

    {{ $slot }}

    @if (filled($icon_after))        
    <span @class(['inline ml-auto'])>
        <x-dynamic-component :component="$icon_after" class="w-5 h-5"/>
    </span>
    @endif
</a>