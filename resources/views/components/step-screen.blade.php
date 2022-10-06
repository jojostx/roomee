<div class="mr-6 bg-white border-0 rounded-2xl carousel-cell">
    <div class="w-10 mb-6 text-primary-500">
        <svg viewbox="0 0 64 54" xmlns="http://www.w3.org/2000/svg" fill="currentColor" fill-rule="evenodd" stroke="none">
            <path d="M0.156898 39.6036C-1.62926 31.0267 12.1574 2.01891 25.0962 0C16.9804 12.4695 14.667 16.2255 14.1229 25.8371C14.0914 27.1179 14.6399 27.1329 15.7794 27.1642C17.1977 27.2031 19.5314 27.267 22.8018 29.8274C27.8053 33.7546 28.0889 37.9215 28.0889 39.6036C28.0889 47.3168 21.8361 53.5696 14.1229 53.5696C6.40969 53.5696 0.156898 47.3168 0.156898 39.6036L0.156898 39.6036ZM36.0671 39.6036C34.2809 31.0267 48.0675 2.01891 61.0063 0C52.8905 12.4695 50.5772 16.2255 50.033 25.8371C50.0015 27.1179 50.55 27.1329 51.6896 27.1642C53.1078 27.2031 55.4415 27.267 58.7119 29.8274C63.7155 33.7546 63.999 37.9215 63.999 39.6036C63.999 47.3168 57.7463 53.5696 50.033 53.5696C42.3198 53.5696 36.0671 47.3168 36.0671 39.6036L36.0671 39.6036Z" />
        </svg>
    </div>
    <div class="mb-8">
        {{ $slot }}
    </div>
    <div class="flex flex-col items-center not-italic">
        <div class="w-12 mb-2 overflow-hidden bg-secondary-300 rounded-full">
            {{ $avatar }}
        </div>
        <p class="text-secondary-500"> {{ $full_name }} </p>
        <p class="text-xs text-primary-500"> {{ $username }} </p>
    </div>
</div>