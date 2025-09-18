@props(['user'])

<div class="flex items-center gap-2">
    <!-- favoriting section -->
    @if (auth()->user()->hasBeenAddedToFavorites($user))
    <button 
        x-tooltip.raw="Remove to Favorites" 
        x-data="{}" 
        wire:click="unfavorite({{ $user->id }})" 
        id="action-unfavorite" 
        type="button" 
        title="Remove from Favorites" 
        style="border-radius: .5rem" 
        aria-label="unfavorite user" 
        class="relative flex items-center justify-center w-8 h-8 -my-2 border rounded-full filament-icon-button hover:bg-gray-500/5 focus:outline-none text-primary-500 focus:bg-primary-500/10 border-secondary-300 filament-tables-icon-button-action"
    >
        <span class="sr-only">
            Remove from Favorites
        </span>

        <svg wire:loading.remove.delay="1" wire:target="unfavorite({{ $user->id }})" class="w-4 h-4 filament-icon-button-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
        </svg>
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 animate-spin filament-icon-button-icon" wire:loading.delay="wire:loading.delay" wire:target="unfavorite({{ $user->id }})">
            <path opacity="0.2" fill-rule="evenodd" clip-rule="evenodd" d="M12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" fill="currentColor"></path>
            <path d="M2 12C2 6.47715 6.47715 2 12 2V5C8.13401 5 5 8.13401 5 12H2Z" fill="currentColor"></path>
        </svg>
    </button>
    @else
    <button 
        x-tooltip.raw="Add to Favorites" 
        x-data="{}" 
        wire:click="favorite({{ $user->id }})" 
        id="action-favorite" 
        type="button" 
        title="Add to Favorites" 
        style="border-radius: .5rem" 
        aria-label="favorite user" 
        class="relative inline-flex items-center justify-center w-8 h-8 -my-2 text-gray-500 border rounded-full filament-icon-button hover:bg-gray-500/5 focus:outline-none focus:bg-gray-500/10 border-secondary-300 filament-tables-icon-button-action"
    >
        <span class="sr-only">
            favorite
        </span>

        <svg wire:loading.remove.delay="1" wire:target="favorite({{ $user->id }})" class="w-4 h-4 filament-icon-button-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
        </svg>
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 animate-spin filament-icon-button-icon" wire:loading.delay="wire:loading.delay" wire:target="favorite({{ $user->id }})">
            <path opacity="0.2" fill-rule="evenodd" clip-rule="evenodd" d="M12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" fill="currentColor"></path>
            <path d="M2 12C2 6.47715 6.47715 2 12 2V5C8.13401 5 5 8.13401 5 12H2Z" fill="currentColor"></path>
        </svg>
    </button>
    @endif
    <!-- end of favoriting section -->
</div>