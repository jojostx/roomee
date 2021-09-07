<?php

namespace App\Http\Livewire\Popups;

use App\Events\RoommateRequestUpdated;
use App\Models\User;
use Livewire\Component;

class DeleteRequest extends Component
{ 
    public bool $show = false;
    public User $user;

    protected $listeners = ['showDeleteRequestPopup' => 'AssignVariables'];

    public function AssignVariables(User $id): void
    {
        $this->user = $id;
        $this->show = true;
    }
    
    public function reset_(): void
    {
        $this->show = false;
    }

    public function deleteRequest()
    {
        if ((int) auth()->id() === (int) $this->user->id) {
            return;
        }

        auth()->user()->deleteRoommateRequest($this->user);
            
        $this->emit("refreshChildren:{$this->user->id}");

        $this->reset_();
    }

    public function render()
    {
        return view('livewire.popups.delete-request');
    }
}
