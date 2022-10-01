<label {{$attributes }} class="flex items-center py-1 text-xs font-semibold text-secondary-500 rounded-md sm:text-sm md:text-base info-links hover:text-primary-700">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-2 sm:w-3 mr-0.5 sm:mr-1 xs:w-4 md:w-5" stroke="currentColor">
        {{  $svg_path }}
    </svg>
    {{ $slot }}
</label>