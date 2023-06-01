@props([
'label',
'active_at',
'icon_after',
])

<div class="grid grid-cols-1 gap-6 filament-forms-component-container">
    <div class="col-span-full">
        <div class="bg-white border border-gray-300 filament-forms-section-component rounded-xl">
            <div class="flex rtl:space-x-reverse overflow-hidden rounded-t-xl min-h-[56px] px-4 py-2 items-center bg-gray-100">
                <div class="flex-1 space-y-1">
                    <h3 class="flex flex-row items-center text-xl font-bold tracking-tight capitalize pointer-events-none">
                        {{ $label }}
                    </h3>
                </div>
            </div>

            <div class="p-6">
                <p>You have previously submitted an <span class="capitalize">{{ $label }}</span> profile link for verification.
                    You will be allowed to resubmit a new profile link for verification on
                    <span class="font-medium text-primary-800">
                        {{ $active_at }}.
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>
