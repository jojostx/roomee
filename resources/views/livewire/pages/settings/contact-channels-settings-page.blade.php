<div x-data @reload-page.window="location.reload()" class="w-full pt-12 pb-6">
    <div class="w-11/12 m-auto">
        <div class="grid grid-cols-7 gap-4">
            <div class="flex justify-between col-span-full md:col-span-3">
                <div class="mb-3 space-y-2 md:mb-6">
                    <p class="text-2xl font-semibold">Contact Channel Settings</p>
                    <p class="font-semibold text-secondary-700">Hello, {{ auth()->user()->full_name }}.</p>
                    <div class="max-w-md p-4 text-sm border rounded-lg shadow text-secondary-300 bg-secondary-900">
                        <p class="text-secondary-400">To add a new contact channel, follow the steps below:</p>
                        <ul class="mt-2 space-y-1 list-decimal list-inside">
                            <li>
                                Fill the input with your profile link/phone number and click the <span class="font-semibold text-secondary-200">[+]</span> button,
                            </li>
                            <li>
                                A verification code will be generated and displayed to you.
                            </li>
                            <li>
                                Copy the code and send it to our account from the same account you wish to verify.
                            </li>
                            <li>
                                After sending it, click on the <span class="font-semibold text-secondary-200">[submit for verification]</span> button and your account will be verified within 48 hours
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="max-w-3xl pb-6 space-y-4 sm:grid-cols-3 col-span-full md:col-span-4">
                {{-- <div>
                    @if(blank($this->getChannel('whatsapp')))
                        <form
                            wire:submit.prevent="updateChannel('whatsapp')"
                        >
                            {{ $this->whatsappForm }}
                        </form>
                    @else
                        @php
                            $channel_name = 'whatsapp';
                            $channel = $this->getChannel($channel_name)
                        @endphp

                        <livewire:components.cards.updated-contact-channel-card 
                            :contactChannel="$channel"
                            :show="true"
                        />

                        <form
                            x-data="{ show : false }"
                            x-show="show"
                            x-on:close-update-form.window="if ($event.detail.id == 'whatsapp') { show = false; }"
                            x-on:show-update-form.window="if ($event.detail.id == 'whatsapp') { show = true; }"
                            wire:submit.prevent="updateChannel('whatsapp')"
                        >
                            {{ $this->whatsappForm }}
                        </form>
                    @endif
                </div> --}}

                {{-- <div>
                    @if(blank($this->getChannel('facebook')))
                        <form
                            wire:submit.prevent="updateChannel('facebook')"
                        >
                            {{ $this->facebookForm }}
                        </form>
                    @else
                        @php
                            $channel_name = 'facebook';
                            $channel = $this->getChannel($channel_name)
                        @endphp

                        <livewire:components.cards.updated-contact-channel-card 
                            :contactChannel="$channel"
                            :show="true"
                        />

                        <form
                            x-data="{ show : false }"
                            x-show="show"
                            x-on:close-update-form.window="if ($event.detail.id == 'facebook') { show = false; }"
                            x-on:show-update-form.window="if ($event.detail.id == 'facebook') { show = true; }"
                            wire:submit.prevent="updateChannel('facebook')"
                        >
                            {{ $this->facebookForm }}
                        </form>
                    @endif
                </div> --}}

                {{-- <div>
                    @if(blank($this->getChannel('instagram')))
                        <form
                            wire:submit.prevent="updateChannel('instagram')"
                        >
                            {{ $this->instagramForm }}
                        </form>
                    @else
                        @php
                            $channel_name = 'instagram';
                            $channel = $this->getChannel($channel_name)
                        @endphp

                        <livewire:components.cards.updated-contact-channel-card 
                            :contactChannel="$channel"
                            :show="true"
                        />

                        <form
                            x-data="{ show : false }"
                            x-show="show"
                            x-on:close-update-form.window="if ($event.detail.id == 'instagram') { show = false; }"
                            x-on:show-update-form.window="if ($event.detail.id == 'instagram') { show = true; }"
                            wire:submit.prevent="updateChannel('instagram')"
                        >
                            {{ $this->instagramForm }}
                        </form>
                    @endif
                </div> --}}

                {{-- <div>
                    @if(blank($this->getChannel('twitter')))
                        <form
                            wire:submit.prevent="updateChannel('twitter')"
                        >
                            {{ $this->twitterForm }}
                        </form>
                    @else
                        @php
                            $channel_name = 'twitter';
                            $channel = $this->getChannel($channel_name)
                        @endphp

                        <livewire:components.cards.updated-contact-channel-card 
                            :contactChannel="$channel"
                            :show="true"
                        />

                        <form
                            x-data="{ show : false }"
                            x-show="show"
                            x-cloak
                            x-on:close-update-form.window="if ($event.detail.id == 'twitter') { show = false; }"
                            x-on:show-update-form.window="if ($event.detail.id == 'twitter') { show = true; }"
                            wire:submit.prevent="updateChannel('twitter')"
                        >
                            {{ $this->twitterForm }}
                        </form>
                    @endif
                </div> --}}

                @foreach ($this->channelNames as $channel_name)
                    <x-contact-channel-card
                        :channel="$this->getChannel($channel_name)"
                        :channel_name="$channel_name"
                        form_action="updateChannel('{{ $channel_name }}')"
                    />
                @endforeach
            </div>
        </div>
    </div>
</div>
