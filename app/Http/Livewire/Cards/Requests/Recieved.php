<?php

namespace App\Http\Livewire\Cards\Requests;

use App\Notifications\RoommateRequestAccepted;
use Livewire\Component;

class Recieved extends Component
{
    public $user;
    public $request;

    protected function getListeners()
    {
        return [
            'refreshChildren:' . $this->user->id => '$refresh',
        ];
    }

    public function showDeleteRequestPopup()
    {
        $this->emit('showDeleteRequestPopup', $this->user->id);
    }

    public function mount()
    {     
        $this->user = $this->request->sender;        
    }

    public function acceptRequest()
    {        
        $wasUpdated = auth()->user()->acceptRoommateRequest($this->user);
        
        if ($wasUpdated) {
            $this->user->notify(new RoommateRequestAccepted(auth()->user()));   
        }

        $this->request->refresh();
    }
    
    public function declineRequest()
    {        
        $wasUpdated = auth()->user()->denyRoommateRequest($this->user);
        
        if ($wasUpdated) {
            $this->user->notify(new RoommateRequestAccepted(auth()->user()));   
        }

        $this->request->refresh();
    }
    
    public function render()
    {
        return view('livewire.cards.requests.recieved');
    }
}
