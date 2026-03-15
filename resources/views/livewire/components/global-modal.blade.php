<div>
    @if ($componentName)
        <div
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-modal="true"
            role="dialog"
        >
            {{-- Backdrop --}}
            <div
                class="fixed inset-0 bg-gray-500/75 transition-opacity"
                wire:click="handleCloseModal"
            ></div>

            {{-- Modal panel --}}
            <div class="flex min-h-full items-center justify-center p-4 sm:p-0">
                <div class="relative w-full sm:max-w-sm bg-white rounded-lg shadow-xl overflow-hidden">
                    @livewire($componentName, $componentArgs, key($componentName . '.' . $renderKey))
                </div>
            </div>
        </div>
    @endif
</div>
