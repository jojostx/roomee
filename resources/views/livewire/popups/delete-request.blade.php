<div id="toast" class="fixed top-0 left-0 z-50 items-center justify-center w-full h-full @if($show) flex @else hidden @endif">
    @if ($show)
    <div class="absolute top-0 z-10 w-full h-full bg-gray-900 opacity-40" wire:click="reset_"></div>
    <div class="z-20 w-64 px-3 pt-3 pb-1 overflow-hidden text-gray-600 bg-white rounded-md lg:w-72">
        Are you sure you want to delete the request to <span class="font-medium text-gray-900">{{ $name }}?</span> 
        <div class="flex justify-end pt-2">
            <button wire:click="reset_" class="px-1 py-2 mr-3 font-semibold hover:text-blue-500 focus:text-blue-600 focus:outline-none">Cancel</button>
            <button wire:click="deleteRequest()" class="px-1 py-2 font-semibold text-red-500 hover:text-red-700 focus:text-red-700 focus:outline-none">Delete</button>
        </div>
    </div>
    @endif
</div>