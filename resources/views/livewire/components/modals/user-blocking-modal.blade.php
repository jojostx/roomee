<div>
    @if (auth()->user()->hasBlocked($user))
    <!-- unblock user modal -->
    <div class="flex items-center justify-center py-3 border-b border-secondary-300">
        <p class="font-semibold text-secondary-700">
            Unblock <span class="user-name">{{ $user->full_name }}</span>?
        </p>
    </div>
    <div class="px-4 py-2">
        <p class="text-secondary-600">
            <span class="font-medium text-secondary-900">{{ $user->full_name }}</span> will be able to view your profile or send you roommate request.
        </p>
        <div class="flex justify-end pt-2">
            <button wire:click="$emit('closeModal')" class="px-1 py-2 mr-4 font-semibold hover:text-primary-500 focus:text-primary-600 focus:outline-none">Cancel</button>
            <button wire:click="submit" class="px-1 py-2 font-semibold text-danger-500 hover:text-danger-700 focus:text-danger-700 focus:outline-none">Unblock</button>
        </div>
    </div>
    @else
    <!-- block user modal -->
    <div class="flex items-center justify-center py-3 border-b border-secondary-300">
        <p class="font-semibold text-secondary-700">
            Block <span class="user-name">{{ $user->full_name }}</span>?
        </p>
    </div>
    <div class="px-4 py-2">
        <p class="text-secondary-600">
            <span class="font-medium text-secondary-900">{{ $user->full_name }}</span> will no longer be able to view your profile or send you a roommate request.
        </p>
        <div class="flex justify-end pt-2">
            <button wire:click="$emit('closeModal')" class="px-1 py-2 mr-4 font-semibold hover:text-primary-500 focus:text-primary-600 focus:outline-none">Cancel</button>
            <button wire:click="submit" class="px-1 py-2 font-semibold text-danger-500 hover:text-danger-700 focus:text-danger-700 focus:outline-none">Block</button>
        </div>
    </div>
    @endif
</div>