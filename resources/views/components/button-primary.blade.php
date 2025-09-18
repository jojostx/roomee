@php
    $classes = "inline-flex items-center px-1 xs:px-2 py-1 text-xs sm:text-sm font-semibold text-primary-600 transition duration-150 ease-in-out bg-primary-100 rounded-md hover:text-primary-700 hover:bg-primary-200 focus:outline-none focus:bg-primary-200 focus:text-primary-700";
@endphp

<button {{ $attributes->merge(['class' => $classes]) }}>
    <span class="pr-1">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="w-3 h-3 sm:w-4 sm:h-4" viewBox="0 0 24 24" stroke="currentColor">
            {{ $svgPath }}
        </svg>
    </span>
    {{ $slot }}
</button>
