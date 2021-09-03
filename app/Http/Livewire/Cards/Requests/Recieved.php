<?php

namespace App\Http\Livewire\Cards\Requests;

use App\Models\User;
use App\Notifications\RoommateRequestAccepted;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Recieved extends Component
{
    public $user;

    public function mount(User $user)
    {     
        $this->user = $user;        
    }

    public function acceptRequest()
    {        
        $wasUpdated = DB::table('roommate_requests')->where([
            'requester_id'=> $this->user->id,
            'requestee_id'=> auth()->id(),
        ])->update([
            'status' => 'accepted', 
            'seen' => true
        ]);
        
        if (boolval($wasUpdated)) {
            $this->user->notify(new RoommateRequestAccepted(auth()->user()));   
        }

        //remember to emit toast notification event!!!
    }
    
    public function render()
    {
        return view('livewire.cards.requests.recieved');
    }
}
