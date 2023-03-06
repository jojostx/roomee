<?php

namespace App\Http\Livewire\Pages;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth ;
use Livewire\Component;

class Notifications extends Component
{
    public Collection $notifications;
    public $roommateRequestAcceptedNotifications;
    public $roommateRequestReceivedNotifications;

    public function mount()
    {
        $this->notifications = $this->getAuthModel()
            ->notifications()
            ->select('id', 'type', 'data', 'read_at', 'created_at')->get();

        $this->roommateRequestAcceptedNotifications = $this->notifications
            ->where('type', 'App\Notifications\RoommateRequestAcceptedNotification')
            ->unique('data')
            ->values()
            ->all();

        $this->roommateRequestReceivedNotifications = $this->notifications
            ->where('type', 'App\Notifications\RoommateRequestReceived')
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
