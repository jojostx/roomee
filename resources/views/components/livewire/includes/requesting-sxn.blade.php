<div>
    <!-- requesting and contacting section -->
    @if (auth()->user()->isRoommateWith($user))
    <x-button-primary class="ml-auto">
        <x-slot name="svgPath">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </x-slot>
        Contact
    </x-button-primary>
    @elseif (auth()->user()->sentRequests->contains($user))
    <x-button-sec wire:click="showDeleteRequestPopup()" aria-label="delete request" title="delete request">
        <x-slot name="svgPath">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6" />
        </x-slot>
        Requested
    </x-button-sec>
    @elseif (auth()->user()->recievedRequests->contains($user))
    <x-button-primary wire:click="" onclick="console.log('ok');">
        <x-slot name="svgPath">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
        </x-slot>
        View request
    </x-button-primary>
    @else
    <x-button-primary wire:click="sendRequest()" aria-label="send request" title="send request">
        <x-slot name="svgPath">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
        </x-slot>
        Request
    </x-button-primary>
    @endif
    <!-- end of requesting and contacting section -->
</div>