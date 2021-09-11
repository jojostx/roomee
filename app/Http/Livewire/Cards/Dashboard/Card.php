<?php

namespace App\Http\Livewire\Cards\Dashboard;

use App\Events\RoommateRequestUpdated;
use Livewire\Component;
use App\Http\Livewire\Traits\Favoriting;
use App\Notifications\RoommateRequestRecieved;
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
        auth()->user()->sendRoommateRequest($this->user);

        $this->emit('actionTakenOnUser', $this->user->fullname, 'request');
                
        $this->user->notify(new RoommateRequestRecieved(auth()->user()));
    }

    public function showDeleteRequestPopup()
    {
        $this->emit('showDeleteRequestPopup', $this->user->id);
    }

    public function getIsBlockerProperty()
    {
        $blocking = DB::table('blocklists')->where([
            'blocker_id' => $this->user->id,
            'blockee_id' => auth()->id()
        ])->get();

        return $blocking->count();
    }

    public function render()
    {
        return view('livewire.cards.dashboard.card');
    }
}
