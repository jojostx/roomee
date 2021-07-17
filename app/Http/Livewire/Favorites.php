<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Favorites extends Component
{
    protected $listeners = ['actionTakenOnUser' => '$refresh'];

    public function getFavoritedUsersProperty()
    {
        return auth()->user()->favorites;
    }
    
    public function render()
    {
        return view('livewire.favorites')->layout('layouts.guest');
    }
}
