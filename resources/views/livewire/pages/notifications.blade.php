<div class="w-full h-full mx-auto bg-secondary-50 max-w-7xl">
    <x-page-header>
        <div class="flex items-center justify-between w-full">
            <p>Notifications</p>
        </div>
    </x-page-header>

    <div class="flex flex-wrap {{ ($notifications->isEmpty())?'justify-center':'justify-start' }} gap-2 w-full px-3 mx-auto my-2 max-w-7xl sm:px-6 lg:px-8">
        @if ($notifications->isEmpty())
            <div class="p-3 my-4 bg-white border rounded-md shadow">
                Your have no new notifications.
            </div> 
        @else
            @foreach ($roommateRequestAcceptedNotifications as $notification)
                <livewire:components.cards.notifications.roommate-request-accepted-card :notification="$notification" :user="$notification->data['recipient_id']" :wire:key="$notification->id">
            @endforeach
            
            @foreach ($roommateRequestRecievedNotifications as $notification)
                <livewire:components.cards.notifications.roommate-request-recieved-card :notification="$notification" :user="$notification->data['sender_id']" :wire:key="$notification->id">
            @endforeach
        @endif       
    </div>
</div>