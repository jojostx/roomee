<?php

namespace App\Http\Livewire\Components\Cards\Notifications;

use App\Models\User;
use Livewire\Component;

class RequestRecievedCard extends Component
{
    public $notification;
    
    //requester (the user who sent the request)
    public $user;

    public function mount($notification, User $user)
    {
        $this->notification = $notification;        
        $this->user = $user;        
    }

    public function render()
    {
        return view('livewire.components.cards.notifications.request-recieved-card');
    }
}
