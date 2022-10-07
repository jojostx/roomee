<div class="w-full h-full mx-auto bg-secondary-100 max-w-7xl">
    <x-page-header>
        <div class="flex items-center justify-between w-full">
            <p>Requests</p>
            <div class="flex items-center overflow-hidden text-xs border rounded-md text-primary-800 border-primary-800 sm:text-sm">
                <button wire:click="switchPage('recieved')" class="px-2 py-1 @if ($currentPage == App\Enums\RequestType::RECIEVED) bg-primary-200 @endif border-r border-primary-800 focus:outline-none focus:bg-primary-200">Recieved requests</button>
                <button wire:click="switchPage('sent')" class="px-2 py-1 @if ($currentPage == App\Enums\RequestType::SENT) bg-primary-200 @endif hover:text-primary-600 focus:outline-none focus:bg-primary-200">Sent requests</button>
            </div>
        </div>
    </x-page-header>

    <div class="flex flex-wrap px-3 mx-auto max-w-7xl sm:px-6 lg:px-8">
    @if ($currentPage == App\Enums\RequestType::SENT)
        @forelse ($sentRequests as $request)
        <div class="grid gap-4 lg:gap-6 grid-col-1 sm:grid-cols-2 lg:grid-cols-3">
            @livewire('components.cards.requests.sent-request-card', ['request' => $request], key($request->id))
        </div>
        @empty
        <div class="flex items-center justify-center w-full my-2">
            <div class="p-3 my-4 bg-white border rounded-md shadow">
                You have not sent any roommate Requests.
            </div>
        </div>
        @endforelse
    @else
        @forelse ($recievedRequests as $request)
        <div class="grid gap-4 lg:gap-6 grid-col-1 sm:grid-cols-2 lg:grid-cols-3">
            @livewire('components.cards.requests.recieved-request-card', ['request' => $request], key($request->id))
        </div>
        @empty
        <div class="flex items-center justify-center w-full my-2">
            <div class="p-3 my-4 bg-white border rounded-md shadow">
                You have not recieved any roommate Requests.
            </div>
        </div>
        @endforelse
    @endif
    </div>
</div>