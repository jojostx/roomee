<?php

namespace App\Http\Livewire\Traits;

trait Favoriting
{
    public function favorite()
    {
        if (auth()->user()->isBlockedBy($this->user)) {
            return;
        }
        
        auth()->user()->favorites()->attach($this->user->id);
        $this->emit('actionTakenOnUser', $this->user->fullname, 'favorite');
    }
    
    public function unfavorite()
    {
        auth()->user()->favorites()->detach($this->user->id);
    }
}