<?php

namespace App\Http\Livewire\Cards\Favorites;

use Livewire\Component;

class Card extends Component
{

    public $user;
    
    public function mount(){
    }

    public function unfavorite()
    {
        if ($this->user->id === auth()->user()->id) {
            return;
        }
        
        auth()->user()->favorites()->detach($this->user->id);

        $this->emit('actionTakenOnUser', $this->user->fullname, 'unfavorite');        

        $this->user = null;
    }

    public function render()
    {
        return view('livewire.cards.favorites.card');
    }
}
