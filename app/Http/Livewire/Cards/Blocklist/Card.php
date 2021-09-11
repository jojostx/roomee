<?php

namespace App\Http\Livewire\Cards\Blocklist;

use Livewire\Component;

class Card extends Component
{
    public $user;
    
    public function mount(){
    }

    public function unblockUser()
    {
        $blocked = auth()->user()->unblock($this->user);

        if ($blocked) {
            $this->emit('actionTakenOnUser', $this->user->fullname, 'unblock');
        }

        $this->user = null;

    }

    public function render()
    {
        return view('livewire.cards.blocklist.card');
    }
}
