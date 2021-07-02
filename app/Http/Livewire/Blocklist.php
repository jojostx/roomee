<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Blocklist extends Component
{
    public $blockedUsers;

    protected $listeners = ['actionTakenOnUser' => 'resetBlocklist'];

    public function mount()
    {
        $user = auth()->user();
        $this->blockedUsers = $user->blocklists;
    }

    public function resetBlocklist()
    {
        $this->blockedUsers = auth()->user()->blocklists;
    }
    
    public function render()
    {
        return view('livewire.blocklist')->layout('layouts.guest');
    }
}
