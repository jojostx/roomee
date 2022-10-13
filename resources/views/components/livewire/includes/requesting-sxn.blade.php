@props(['user'])

<div class="flex items-center w-full gap-2">
    <!-- requesting and contacting section -->
    @if (auth()->user()->isRoommateWith($user))
    <x-filament-support::button labelSrOnly="true" tag="a" size="sm" icon="heroicon-s-phone-outgoing" aria-label="contact {{ $user->full_name }}" title="contact {{ $user->full_name }}">
        Contact
    </x-filament-support::button>
    @elseif (auth()->user()->hasSentRoommateRequestTo($user))
    <x-filament-support::button class="inline-flex items-center justify-center w-full px-5" wire:click="showDeleteRequestModal({{ $user->id }})" size="sm" icon="heroicon-s-user-remove" aria-label="delete request" title="delete request">
        Requested
    </x-filament-support::button>
    @elseif (auth()->user()->hasRoommateRequestFrom($user))
    <x-filament-support::button class="inline-flex items-center justify-center w-full px-5" href="{{ route('roommate-requests') }}#requests-recieved__{{ $user->id }}" tag="a" :outlined="true" size="sm" icon="heroicon-s-external-link" aria-label="view request" title="view request">
        View request
    </x-filament-support::button>
    @else
    <x-filament-support::button class="inline-flex items-center justify-center w-full px-5" wire:click="sendRoommateRequest({{ $user->id }})" :outlined="true" color="secondary" size="sm" icon="heroicon-s-user-add" aria-label="send roommate request" title="send request">
        Request
    </x-filament-support::button>
    @endif
    <!-- end of requesting and contacting section -->
</div>