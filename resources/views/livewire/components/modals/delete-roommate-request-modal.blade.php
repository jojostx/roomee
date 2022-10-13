<div>
    <div class="flex items-center justify-center py-3 border-b border-secondary-300">
        <p class="font-semibold text-secondary-700">
            Delete Request
        </p>
    </div>
    <div class="px-4 py-2">
        <p class="text-secondary-600">
            Are you sure you want to delete the request to <span class="font-medium text-secondary-900">{{ $user->full_name }}?</span> 
        </p>
        <div class="flex justify-end pt-2">
            <button wire:click="$emit('closeModal')" class="px-1 py-2 mr-3 font-semibold hover:text-primary-500 focus:text-primary-600 focus:outline-none">Cancel</button>
            <button wire:click="deleteRoommateRequest()" class="px-1 py-2 font-semibold text-danger-500 hover:text-danger-700 focus:text-danger-700 focus:outline-none">Delete</button>
        </div>
    </div>
</div>