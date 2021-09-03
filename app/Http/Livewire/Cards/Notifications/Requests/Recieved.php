<?php

namespace App\Http\Livewire\Cards\Notifications\Requests;

use App\Models\User;
use Livewire\Component;

class Recieved extends Component
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
        return view('livewire.cards.notifications.requests.recieved');
    }
}
