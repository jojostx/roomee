<?php

namespace App\Http\Livewire\Cards\Notifications\Requests;

use App\Models\User;
use Livewire\Component;

class Accepted extends Component
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
        return view('livewire.cards.notifications.requests.accepted');
    }
}
