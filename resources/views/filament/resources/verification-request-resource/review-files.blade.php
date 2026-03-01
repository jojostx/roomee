<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
    <div class="space-y-2">
        <p class="text-sm font-semibold text-gray-700">Identity Document</p>
        @if (filled($identityUrl))
            <iframe
                src="{{ $identityUrl }}"
                class="w-full h-80 border rounded-lg bg-gray-50"
                title="Identity document preview"
            ></iframe>
        @else
            <p class="text-sm text-gray-500">No identity document uploaded.</p>
        @endif
    </div>

    <div class="space-y-2">
        <p class="text-sm font-semibold text-gray-700">Selfie</p>
        @if (filled($selfieUrl))
            <iframe
                src="{{ $selfieUrl }}"
                class="w-full h-80 border rounded-lg bg-gray-50"
                title="Selfie preview"
            ></iframe>
        @else
            <p class="text-sm text-gray-500">No selfie uploaded.</p>
        @endif
    </div>
</div>

