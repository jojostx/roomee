@props(['user'])

<div class="flex items-center w-full gap-2">
    <!-- favoriting section -->
    @if (auth()->user()->hasBeenAddedToFavorites($user))
    <x-filament-support::button class="inline-flex items-center justify-center w-full px-5" wire:click="unfavorite({{ $user->id }})" size="sm" color="primary" icon="heroicon-s-heart" aria-label="unfavorite user" title="unfavorite user">
        Unfavorite
    </x-filament-support::button>
    @else
    <x-filament-support::button class="inline-flex items-center justify-center w-full px-5" wire:click="favorite({{ $user->id }})" color="secondary" size="sm" icon="heroicon-s-heart" aria-label="add to favorites" title="add to favorites">
        Favorite
    </x-filament-support::button>
    @endif
    <!-- end of favoriting section -->
</div>