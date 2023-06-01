@props([
    'channel',
    'channel_name',
    'form_action',
])

<div>
    @php
        $form =  $this->{$channel_name.'Form'};
    @endphp
    @if(blank($channel))
        <form
            wire:submit.prevent="{{ $form_action }}"
        >
            {{ $form }}
        </form>
    @else
        <livewire:components.cards.updated-contact-channel-card 
            :contactChannel="$channel"
            :show="true"
        />

        <form
            x-data="{ show : false }"
            x-show="show"
            x-on:close-update-form.window="if ($event.detail.id == '{{ $channel_name }}') { show = false; }"
            x-on:show-update-form.window="if ($event.detail.id == '{{ $channel_name }}') { show = true; }"
            wire:submit.prevent="{{ $form_action }}"
        >
            {{ $form }}
        </form>
    @endif
</div>