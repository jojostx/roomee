<div class="w-full h-screen mx-auto bg-gray-100 max-w-7xl">
    <x-page-header>
        <div class="flex items-center justify-between w-full">
            <p>Requests</p>
            <div class="flex items-center overflow-hidden text-xs text-blue-800 border border-blue-800 rounded-md sm:text-sm">
                <button wire:click="switchPage" class="px-1 sm:px-2 py-1 @if ($currentPage=='recieved') bg-blue-200 @endif border-r border-blue-800 focus:outline-none focus:bg-blue-200">Recieved requests</button>
                <button wire:click="switchPage" class="px-1 sm:px-2 py-1 @if ($currentPage=='sent') bg-blue-200 @endif hover:text-blue-600 focus:outline-none focus:bg-blue-200">Sent requests</button>
            </div>
        </div>
    </x-page-header>
    @if ($currentPage == 'sent')
        @if (!$sentRequests->count())
            <div class="flex items-center justify-center w-full my-2">
                <div class="p-3 my-4 bg-white border rounded-md shadow">
                    Your have not sent any roommate Requests.
                </div>
            </div>
        @else
            <div class="flex flex-wrap px-2 mx-auto max-w-7xl sm:px-6 lg:px-8">
                @foreach ($sentRequests as $request)
                    @livewire('cards.requests.sent', ['request' => $request], key($request->id))
                @endforeach
            </div>
        @endif
    @else
        @if (!$recievedRequests->count())
            <div class="flex items-center justify-center w-full my-2">
                <div class="p-3 my-4 bg-white border rounded-md shadow">
                    Your have not recieved any roommate Requests.
                </div>
            </div>
        @else
            <div class="flex flex-wrap px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                @foreach ($recievedRequests as $request)
                    @livewire('cards.requests.recieved', ['request' => $request], key($request->id))
                @endforeach
            </div>
        @endif
    @endif
    
    <x-livewire.toast-notif></x-livewire.toast-notif>
    @livewire('popups.delete-request')
</div>