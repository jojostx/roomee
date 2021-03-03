<div class="min-h-screen flex flex-col sm:justify-center items-center pt-4 sm:pt-6 pb-6 bg-gray-100">
    <div>
        {{ $logo }}
    </div>

    <div class="w-80 sm:max-w-xs mt-6 px-6 py-4 bg-white shadow-md overflow-hidden rounded-md sm:rounded-lg">
        {{ $slot }}
    </div>
</div>
