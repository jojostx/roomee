<?php

namespace App\Http\Livewire\Components\Modals;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use LivewireUI\Modal\ModalComponent;

class DeleteRequestModal extends ModalComponent
{ 
    public int | User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    public static function modalMaxWidth(): string
    {
        return 'sm';
    }

    public function deleteRequest()
    {
        $this->getAuthModel()->deleteRoommateRequest($this->user);
            
        $this->emit("refreshChildren:{$this->user->id}");
        $this->emit("resetUsers", $this->user->id);

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.components.modals.delete-request-modal');
    }
}
