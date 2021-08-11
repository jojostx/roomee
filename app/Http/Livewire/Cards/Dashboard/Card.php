<?php

namespace App\Http\Livewire\Cards\Dashboard;

use App\Events\RoommateRequestUpdated;
use Livewire\Component;
use App\Http\Livewire\Traits\Favoriting;

class Card extends Component
{
    use Favoriting;

    public $user;
    public $course;

    protected function getListeners(){
       return [
           'refreshChildren:'.$this->user->id => '$refresh',
        ];
    } 

    public function mount(){
        $this->course = $this->user->course;
    }

    public function updateRequest()
    {
        // event(new RoommateRequestUpdated(auth()->user()->id, $this->user->id));
        broadcast(new RoommateRequestUpdated(auth()->user()->id, $this->user->id))->toOthers();
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
