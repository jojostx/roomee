<?php

namespace App\Http\Livewire\Cards\Dashboard;

use Livewire\Component;

class UserCard extends Component
{
    public $user;
    public $course;

    public function mount(){
        $this->course = $this->user->course;
    }

    public function render()
    {
        return view('livewire.cards.dashboard.user-card');
    }
}
