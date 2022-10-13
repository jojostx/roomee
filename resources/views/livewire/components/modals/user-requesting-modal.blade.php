<div>
    <!-- contact modal -->
    @if (auth()->user()->isRoommateWith($user))
    <div class="flex items-center justify-center py-3 border-b border-secondary-300">
        <p class="font-semibold text-secondary-700">
            Contact <span class="user-name">{{ $user->full_name }}</span>?
        </p>
    </div>
    <ul class="py-2 divide-y">
        <x-responsive-nav-link icon_before="heroicon-o-phone-outgoing">
            <p>
                {{ __('Call') }}&nbsp;<span class="font-semibold user-name">{{ $user->full_name }}</span>
            </p>
        </x-responsive-nav-link>
        <x-responsive-nav-link icon_before="heroicon-o-mall">
            <p>
                {{ __('Email') }}&nbsp;<span class="font-semibold user-name">{{ $user->full_name }}</span>
            </p>
        </x-responsive-nav-link>
    </ul>
    <div class="flex justify-end">
        <button wire:click="$emit('closeModal')" class="px-1 py-2 font-semibold hover:text-primary-500 focus:text-primary-600 focus:outline-none">Cancel</button>
    </div>
    @endif

    <!-- delete roommate request modal -->
    @if (auth()->user()->hasSentRoommateRequestTo($user))
    <div class="flex items-center justify-center py-3 border-b border-secondary-300">
        <p class="font-semibold text-secondary-700">
            Delete Roommate Request?
        </p>
    </div>
    <div class="px-4 py-2">
        <p class="text-secondary-600">
            This will delete the request you sent to <span class="font-medium text-secondary-900">{{ $user->full_name }}</span>.
        </p>
        <div class="flex justify-end pt-2">
            <button wire:click="$emit('closeModal')" class="px-1 py-2 mr-4 font-semibold hover:text-primary-500 focus:text-primary-600 focus:outline-none">Cancel</button>
            <button wire:click="deleteRoommateRequest" class="px-1 py-2 font-semibold text-danger-500 hover:text-danger-700 focus:text-danger-700 focus:outline-none">Delete</button>
        </div>
    </div>
    @endif

    <!-- accept roommate request modal -->
    @if (auth()->user()->hasRoommateRequestFrom($user))
    <div class="flex items-center justify-center py-3 border-b border-secondary-300">
        <p class="font-semibold text-secondary-700">
            Accept Roommate Request?
        </p>
    </div>
    <div class="px-4 py-2">
        <p class="text-secondary-600">
            Accepting the request will allow <span class="font-medium text-secondary-900">{{ $user->full_name }}</span> to be able to contact you via your configured Contact methods.
        </p>
        <div class="flex justify-end pt-2">
            <button wire:click="$emit('closeModal')" class="px-1 py-2 mr-4 font-semibold hover:text-primary-500 focus:text-primary-600 focus:outline-none">Cancel</button>
            <button wire:click="acceptRoommateRequest" class="px-1 py-2 font-semibold text-danger-500 hover:text-danger-700 focus:text-danger-700 focus:outline-none">Accept</button>
        </div>
    </div>
    @endif
</div>