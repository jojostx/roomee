<?php

namespace App\Livewire\Components\Modals;

use App\Livewire\Traits\CanRetrieveUser;
use App\Livewire\Traits\ClosesModal;
use App\Livewire\Traits\WithRequesting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserRequestingModal extends Component
{
    use CanRetrieveUser, ClosesModal, WithRequesting {
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
        $this->dispatch('actionTakenOnUser');
        $this->closeModal();
    }

    public function deleteRoommateRequest()
    {
        $this->traitDeleteRoommateRequest($this->user);
        $this->dispatch('actionTakenOnUser');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.components.modals.user-requesting-modal');
    }
}
