<?php

namespace App\Http\Livewire\Pages;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth ;
use Livewire\Component;

class Notification extends Component
{
    public Collection $notifications;
    public $requestAcceptedNotifications;
    public $requestRecievedNotifications;

    public function mount()
    {
        $this->notifications = $this->getAuthModel()
            ->notifications()
            ->select('id', 'type', 'data', 'read_at', 'created_at')->get();

        $this->requestAcceptedNotifications = $this->notifications
            ->where('type', 'App\Notifications\RoommateRequestAccepted')
            ->unique('data')
            ->values()
            ->all();

        $this->requestRecievedNotifications = $this->notifications
            ->where('type', 'App\Notifications\RoommateRequestRecieved')
            ->unique('data')
            ->values()
            ->all();
    }

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    public function markAsRead()
    {
        $this->getAuthModel()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.notification');

        return $view->layout('layouts.guest');
    }
}
