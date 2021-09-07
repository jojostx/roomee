<?php

namespace App\Http\Livewire\Cards\Requests;

use App\Models\User;
use Livewire\Component;

class Sent extends Component
{
    public $user;
    public $request;

    public function mount()
    {
        $this->request = auth()->user()->getRoommateRequest($this->user);
    }

    public function showDeleteRequestPopup()
    {
        $this->emit('showDeleteRequestPopup', $this->user->id);
    }

    public function render()
    {
        return view('livewire.cards.requests.sent');
    }
}


