<div>
    @if ($contactChannel)
        @php
            $type = $contactChannel->type;
            $logo = 'logos.' . $type;
            $link = $contactChannel->link;
            $is_verified = $contactChannel->isverified();
        @endphp
        <div 
            x-data="{ show : {{ $show ? 'true' : 'false' }} }" 
            x-show="show"
            x-cloak
            x-on:close-update-form.window="if ($event.detail.id == '{{ $type }}') { show = true; }" 
            class="grid grid-cols-1 gap-6 filament-forms-component-container">
            <div class="col-span-full">
                <div class="bg-white border border-gray-300 filament-forms-section-component rounded-xl">
                    <div class="flex items-center px-4 py-2 overflow-hidden bg-gray-100 rtl:space-x-reverse rounded-t-xl">
                        <h3 class="font-bold tracking-tight capitalize pointer-events-none">
                            {{ $type }}
                        </h3>

                        @unless ($is_verified)
                        <div class="inline-flex ml-2 text-danger-700 bg-danger-500/10 items-center justify-center rtl:space-x-reverse min-h-6 px-2 py-0.5 text-sm font-medium tracking-tight rounded-xl whitespace-nowrap">
                            <span>
                                Unverified
                            </span>
                        </div>
                        @endunless

                        <div class="ml-auto">
                            <x-filament-support::icon-button 
                                size="sm"
                                color="danger"
                                icon="heroicon-s-trash" 
                                style="border-radius: 0.5rem;"
                                class="border rounded-lg border-secondary-300 disabled:cursor-not-allowed disabled:pointer-events-none shrink-0 " 
                                aria-label="delete contact channel" 
                                title="delete contact channel"
                                tooltip="Delete contact channel"
                                wire:click="showDeleteConfirmationPrompt"
                            />
                        </div>
                    </div>

                    <div class="p-4">
                        <div class="flex items-center justify-between p-4 border rounded-xl">
                            <div class="flex items-center gap-2 mr-2">
                                <span>
                                    <x-dynamic-component :component="$logo" class="w-5 h-5 mr-1" />
                                </span>
                                <span class="text-sm">
                                    {{ $link }}
                                </span>
                            </div>
                            {{ $this->form }}
                        </div>
                        <div class="mt-4">
                            <x-filament::button x-on:click="show = false; $dispatch('show-update-form', { id : '{{ $type }}' });" size='sm' type='button' style='font-weight: 600;'>
                                {{ __('Update Contact Channel') }}
                            </x-filament::button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

