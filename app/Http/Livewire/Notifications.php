<?php

namespace App\Http\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;

class Notifications extends Component
{
    public Collection $notifications;
    public $requestAcceptedNotifications;
    public $requestRecievedNotifications;

    public function mount()
    {
        $this->notifications = auth()->user()->notifications()->select('id', 'type', 'data', 'read_at', 'created_at')->get();

        $this->requestAcceptedNotifications = $this->notifications->where('type', 'App\Notifications\RoommateRequestAccepted')->all();

        $this->requestRecievedNotifications = $this->notifications->where('type', 'App\Notifications\RoommateRequestRecieved')->all();

        $this->markAsRead();
    }

    public function markAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        return view('livewire.notifications')->layout('layouts.guest');
    }
}

// auth()->user()->notifications->groupBy(function ($item, $key) {
//     return collect(explode("\\", $item['type']))->last();           
// });