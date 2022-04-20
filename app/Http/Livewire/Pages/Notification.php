<?php

namespace App\Http\Livewire\Pages;

use Illuminate\Support\Collection;
use Livewire\Component;

class Notification extends Component
{
    public Collection $notifications;
    public $requestAcceptedNotifications;
    public $requestRecievedNotifications;

    public function mount()
    {
        $this->notifications = auth()->user()->notifications()->select('id', 'type', 'data', 'read_at', 'created_at')->get();

        $this->requestAcceptedNotifications = $this->notifications->where('type', 'App\Notifications\RoommateRequestAccepted')->unique('data')->values()->all();

        $this->requestRecievedNotifications = $this->notifications->where('type', 'App\Notifications\RoommateRequestRecieved')->unique('data')->values()->all();

        $this->markAsRead();
    }

    public function markAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        return view('livewire.pages.notification')->layout('layouts.guest');
    }
}
