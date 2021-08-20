<?php

namespace App\Http\Livewire\Cards\Dashboard;

use App\Events\RoommateRequestUpdated;
use Livewire\Component;
use App\Http\Livewire\Traits\Favoriting;
use Illuminate\Support\Facades\DB;

class Card extends Component
{
    use Favoriting;

    public $user;
    public $course;
    public $requestId = NULL;

    protected function getListeners()
    {
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
        auth()->user()->sentRequests()->attach($this->user->id);

        $this->emit('actionTakenOnUser', $this->user->fullname, 'request');
        
        RoommateRequestUpdated::dispatch(auth()->id(), $this->user->id);      
    }

    public function getIsBlockerProperty()
    {
        return $this->user->blocklists->contains(auth()->user());
    }

    public function showDeleteRequestPopup()
    {
        $this->emit('showDeleteRequestPopup', $this->user->id, $this->user->fullname);
    }

    public function render()
    {
        return view('livewire.cards.dashboard.card');
    }
}
