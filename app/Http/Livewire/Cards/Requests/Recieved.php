<?php

namespace App\Http\Livewire\Cards\Requests;

use App\Models\User;
use App\Notifications\RoommateRequestAccepted;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Recieved extends Component
{
    public $user;
    public $request;

    public function showDeleteRequestPopup()
    {
        $this->emit('showDeleteRequestPopup', $this->user->id);
    }

    public function mount()
    {     
        $this->request = $this->user->getRoommateRequest(auth()->user());        
    }

    public function acceptRequest()
    {        
        $wasUpdated = auth()->user()->acceptRoommateRequest($this->user);
        
        if ($wasUpdated) {
            $this->user->notify(new RoommateRequestAccepted(auth()->user()));   
        }

        $this->request->refresh();
      
        //remember to emit websocket event!!!
        //remember to emit toast notification event!!!
    }
    
    public function render()
    {
        return view('livewire.cards.requests.recieved');
    }
}
