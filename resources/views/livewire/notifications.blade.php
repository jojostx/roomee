<div class="w-full h-screen mx-auto bg-gray-100 max-w-7xl">
    <x-page-header>
        <div class="flex items-center justify-between w-full">
            <p>Notifications</p>
        </div>
    </x-page-header>

    <div class="flex flex-wrap w-full px-3 mx-auto my-2 max-w-7xl sm:px-6 lg:px-8">
        @if ($notifications->isEmpty())
            <div class="p-3 my-4 bg-white border rounded-md shadow justify-self-center">
                Your have not sent any roommate requests.
            </div> 
        @else
            @foreach ($requestAcceptedNotifications as $notification)
                <livewire:cards.notifications.requests.accepted :notification="$notification" :user="$notification->data['requestee_id']" :wire:key="$notification->id">
            @endforeach
            
            @foreach ($requestRecievedNotifications as $notification)
                <livewire:cards.notifications.requests.recieved :notification="$notification" :user="$notification->data['requester_id']" :wire:key="$notification->id">
            @endforeach
        @endif       
    </div>

    <x-livewire.toast-notif></x-livewire.toast-notif>
</div>