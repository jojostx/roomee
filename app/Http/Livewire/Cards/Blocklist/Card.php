<?php

namespace App\Http\Livewire\Cards\Blocklist;

use App\Models\Blocklist;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Card extends Component
{
    public $user;
    
    public function mount(){
    }

    public function unblockUser()
    {
        if ($this->user->id === auth()->user()->id) {
            return;
        }
        
        $blocklist_id = Blocklist::where('blocker_id', auth()->user()->id)->where('blockee_id', $this->user->id)->pluck('id');

        $id = DB::table('blocklists')->delete($blocklist_id);

        if ($id) {
            $this->emit('actionTakenOnUser', $this->user->fullname, 'unblock');
        }

        $this->user = null;

    }

    public function render()
    {
        return view('livewire.cards.blocklist.card');
    }
}
