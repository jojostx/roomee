<?php

namespace App\Http\Livewire\Components\Cards\Notifications;

use App\Models\User;
use Livewire\Component;

class RoommateRequestRecievedCard extends Component
{
    public $notification;
    
    //sender (the user who sent the roommate request)
    public $user;

    public function mount($notification, User $user)
    {
        $this->notification = $notification;        
        $this->user = $user;        
    }

    public function render()
    {
        return view('livewire.components.cards.notifications.roommate-request-recieved-card');
    }
}
