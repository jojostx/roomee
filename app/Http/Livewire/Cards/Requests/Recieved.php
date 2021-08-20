<?php

namespace App\Http\Livewire\Cards\Requests;

use Livewire\Component;

class Recieved extends Component
{
    public $user;
    
    public function render()
    {
        return view('livewire.cards.requests.recieved');
    }
}
