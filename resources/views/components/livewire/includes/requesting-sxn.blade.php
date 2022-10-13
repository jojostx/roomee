@props(['user'])

<div class="flex items-center w-full gap-2">
    <!-- requesting and contacting section -->
    @if (auth()->user()->isRoommateWith($user))
    <x-filament-support::button class="inline-flex items-center justify-center w-full px-3" color="success" size="sm" icon="heroicon-s-phone-outgoing" aria-label="contact {{ $user->full_name }}" title="contact {{ $user->full_name }}">
        Contact User
    </x-filament-support::button>
    @elseif (auth()->user()->hasPendingSentRoommateRequestTo($user))
    <x-filament-support::button class="inline-flex items-center justify-center w-full px-5" wire:click="showDeleteRequestModal({{ $user->id }})" color="danger" size="sm" icon="heroicon-s-user-remove" aria-label="delete roommate request" title="delete roommate request">
        Delete Request
    </x-filament-support::button>
    @elseif (auth()->user()->hasPendingRoommateRequestFrom($user))
    <x-filament-support::button class="inline-flex items-center justify-center w-full px-5" wire:click="acceptRoommateRequest({{ $user->id }})" size="sm" icon="heroicon-s-check-circle" aria-label="accept roommate request" title="accept roommate request">
        Accept Request
    </x-filament-support::button>
    @else
    <x-filament-support::button class="inline-flex items-center justify-center w-full px-5" wire:click="sendRoommateRequest({{ $user->id }})" :outlined="true" color="secondary" size="sm" icon="heroicon-s-user-add" aria-label="send roommate request" title="send roommate request">
        Send Request
    </x-filament-support::button>
    @endif
    <!-- end of requesting and contacting section -->
</div>