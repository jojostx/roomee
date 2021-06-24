<?php

namespace App\Http\Livewire;

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
        return view('livewire.user-card');
    }
}
