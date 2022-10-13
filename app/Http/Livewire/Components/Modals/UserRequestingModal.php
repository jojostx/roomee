<?php

namespace App\Http\Livewire\Components\Modals;

use App\Models\User;
use App\Http\Livewire\Traits\CanRetrieveUser;
use App\Http\Livewire\Traits\WithRequesting;
use Illuminate\Support\Facades\Auth;
use LivewireUI\Modal\ModalComponent;

class UserRequestingModal extends ModalComponent
{
    use CanRetrieveUser, WithRequesting {
        acceptRoommateRequest as traitAcceptRoommateRequest;
        deleteRoommateRequest as traitDeleteRoommateRequest;
    }

    public string | User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    public function acceptRoommateRequest()
    {
        $this->traitAcceptRoommateRequest($this->user);
        $this->closeModalWithEvents($this->getListenerComponents());
    }

    public function deleteRoommateRequest()
    {
        $this->traitDeleteRoommateRequest($this->user);
        $this->emit('actionTakenOnUser');
        $this->closeModal();
    }

    public static function modalMaxWidth(): string
    {
        return 'sm';
    }

    public function render()
    {
        return view('livewire.components.modals.user-requesting-modal');
    }
}
