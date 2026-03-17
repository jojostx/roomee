@props(['user'])

<div class="flex items-center w-full gap-2">
    <!-- requesting and contacting section -->
    @if (auth()->user()->isRoommateWith($user))
    <x-filament::button class="inline-flex items-center justify-center grow px-3" wire:click="openChat('{{ $user->uuid }}')" color="success" size="sm" icon="heroicon-s-chat-bubble-left-right" aria-label="chat with {{ $user->full_name }}" title="chat with {{ $user->full_name }}">
        Chat
    </x-filament::button>
    <x-filament::button class="inline-flex shrink-0 items-center justify-center px-3" wire:click="mountAction('unmatchRoommate', { user: '{{ $user->uuid }}' })" color="danger" size="sm" icon="heroicon-o-user-minus" aria-label="unmatch {{ $user->full_name }}" title="unmatch {{ $user->full_name }}">
        Unmatch
    </x-filament::button>
    @elseif (auth()->user()->hasPendingSentRoommateRequestTo($user))
    <x-filament::button class="inline-flex items-center justify-center w-full px-5" wire:click="showDeleteRequestModal('{{ $user->uuid }}')" color="danger" size="sm" icon="heroicon-s-user-minus" aria-label="delete roommate request" title="delete roommate request">
        Delete Request
    </x-filament::button>
    @elseif (auth()->user()->hasPendingRoommateRequestFrom($user))
    <x-filament::button class="inline-flex items-center justify-center w-full px-5" wire:click="acceptRoommateRequest('{{ $user->uuid }}')" size="sm" icon="heroicon-s-check-circle" aria-label="accept roommate request" title="accept roommate request">
        Accept Request
    </x-filament::button>
    @else
    <x-filament::button class="inline-flex items-center justify-center w-full px-5" wire:click="sendRoommateRequest('{{ $user->uuid }}')" :outlined="true" color="gray" size="sm" icon="heroicon-s-user-plus" aria-label="send roommate request" title="send roommate request">
        Send Request
    </x-filament::button>
    @endif
    <!-- end of requesting and contacting section -->
</div>

