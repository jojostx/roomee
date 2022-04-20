<?php

namespace App\Http\Livewire\Components\Cards\Notifications;

use App\Models\User;
use Livewire\Component;

class RequestAcceptedCard extends Component
{
    public $notification;
    public $user;

    public function mount($notification, User $user)
    {
        $this->notification = $notification;        
        $this->user = $user;        
    }

    public function render()
    {
        return view('livewire.components.cards.notifications.request-accepted-card');
    }
}
