<?php

namespace App\Http\Livewire\Components\Cards;

use Livewire\Component;
use App\Http\Livewire\Traits\Favoriting;
use App\Notifications\RoommateRequestRecieved;
use Illuminate\Support\Facades\DB;

class DashboardCard extends Component
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
        return view('livewire.components.cards.dashboard-card');
    }
}
