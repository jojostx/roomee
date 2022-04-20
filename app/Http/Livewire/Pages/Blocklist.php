<?php

namespace App\Http\Livewire\Pages;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Blocklist extends Component
{
    protected $listeners = ['actionTakenOnUser' => '$refresh'];

    public function getBlockedUsersProperty(): Collection
    {
        return auth()->user()->blocklists;
    }
    
    public function render()
    {
        return view('livewire.pages.blocklist')->layout('layouts.guest');
    }
}
