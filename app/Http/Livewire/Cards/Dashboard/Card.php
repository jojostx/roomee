<?php

namespace App\Http\Livewire\Cards\Dashboard;

use App\Events\RoommateRequestUpdated;
use Livewire\Component;
use App\Http\Livewire\Traits\Favoriting;
use App\Notifications\RoommateRequestRecieved;

class Card extends Component
{
    use Favoriting;

    public $user;
    public $course;
    public $requestId = NULL;

    protected function getListeners()
    {
        $id = auth()->id();

        return [
            'refreshChildren:' . $this->user->id => '$refresh',
        ];
    }

    public function mount()
    {
        $this->course = $this->user->course;
    }

    public function sendRequest()
    {
        auth()->user()->sendRoommateRequest($this->user);

        $this->emit('actionTakenOnUser', $this->user->fullname, 'request');
        
        RoommateRequestUpdated::dispatch(auth()->id(), $this->user->id, 'pending');  
        
        $this->user->notify(new RoommateRequestRecieved(auth()->user()));
    }

    public function showDeleteRequestPopup()
    {
        $this->emit('showDeleteRequestPopup', $this->user->id, $this->user->fullname);
    }

    public function getIsBlockerProperty()
    {
        return $this->user->blocklists->contains(auth()->user());
    }

    public function render()
    {
        return view('livewire.cards.dashboard.card');
    }
}
