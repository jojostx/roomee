<div class="flex flex-col items-center min-h-screen pt-4 pb-6 bg-gray-100 sm:justify-center sm:pt-6">
    <div>
        {{ $logo }}
    </div>

    <div class="w-full px-6 py-4 mt-6 overflow-hidden bg-white rounded-md shadow-md sm:max-w-sm sm:rounded-lg">
        {{ $slot }}
    </div>
</div>
