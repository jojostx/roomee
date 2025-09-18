@php
    $classes = "inline-flex items-center px-1 xs:px-2 py-1 font-semibold text-xs sm:text-sm text-primary-100 transition duration-150 ease-in-out bg-primary-600 rounded-md hover:text-primary-100 hover:bg-primary-800 focus:outline-none focus:bg-primary-800 focus:text-primary-100";
@endphp

<button {{ $attributes->merge(['class' => $classes]) }}>
    <span class="pr-1">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="w-3 h-3 sm:w-4 sm:h-4" viewBox="0 0 24 24" stroke="currentColor">
            <!-- <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /> -->
            {{ $svgPath }}
        </svg>
    </span>
    {{ $slot }}
</button>
