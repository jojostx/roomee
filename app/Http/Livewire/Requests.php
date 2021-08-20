<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Requests extends Component
{
    public $recievedRequests;
    public $sentRequests;
    public $currentPage = 'recieved';

    public function switchPage()
    {
        $this->currentPage = ($this->currentPage === "sent") ? "recieved" : "sent";
        $this->mount();
    }

    public function mount()
    {
        $this->recievedRequests = auth()->user()->recievedRequests;
        $this->sentRequests = auth()->user()->sentRequests;
    }
    public function render()
    {
        return view('livewire.requests')->layout('layouts.guest');
    }
}
