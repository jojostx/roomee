@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

@php
switch ($align) {
    case 'left':
        $alignmentClasses = 'origin-top-left left-0 top-0';
        break;
    case 'top':
        $alignmentClasses = 'origin-top top-0';
        break;
    case 'right':
    default:
        $alignmentClasses = 'origin-top-right right-0 top-0';
        break;
}

switch ($width) {
    case '48':
        $width = 'w-48';
        break;
    case '56':
        $width = 'w-56';
        break;
    case '64':
        $width = 'w-64';
        break;
}
@endphp

<div {{ 
    $attributes->merge([
        'class' => 'relative flex flex-col items-center justify-center'
        ]) 
    }} 
    x-data="{ open: false }" 
    x-on:click.outside="open = false" 
    x-on:close.stop="open = false"
    >
    <button 
        x-on:click="$refs.dropdown_panel.toggle" 
        x-bind:class="open && 'text-primary-600 outline-none'"
        aria-label="menu trigger button" 
        title="menu trigger button" 
        class="flex items-center justify-center text-sm font-medium text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300"
     >
        {{ $trigger }}
    </button>

    <!-- Dropdown menu -->
    <div 
        style="position: fixed;"
        x-cloak
        x-ref="dropdown_panel"
        x-float.placement.bottom-end.offset.shift="{ offset: 10 }" 
        class="absolute z-20 w-full max-w-sm overflow-hidden bg-white rounded-md shadow-xl filament-action-group-dropdown ring-1 ring-gray-900/10 dark:bg-gray-700" aria-modal="true" role="dialog">
        {{ $content }}
    </div>
</div>
