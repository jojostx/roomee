<?php

namespace App\Http\Livewire\Components\Popups;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DeleteRequestPopup extends Component
{ 
    public bool $show = false;
    public User $user;

    protected $listeners = ['showDeleteRequestPopup' => 'AssignVariables'];

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

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
        $this->getAuthModel()->deleteRoommateRequest($this->user);
            
        $this->emit("refreshChildren:{$this->user->id}");
        $this->emit("resetUsers", $this->user->id);

        $this->reset_();
    }

    public function render()
    {
        return view('livewire.components.popups.delete-request-popup');
    }
}
