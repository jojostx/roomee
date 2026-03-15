<?php

namespace App\Livewire\Components\Modals;

use App\Livewire\Traits\CanRetrieveUser;
use App\Livewire\Traits\ClosesModal;
use App\Livewire\Traits\WithRequesting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DeleteRoommateRequestModal extends Component
{
    use CanRetrieveUser, ClosesModal;
    use WithRequesting{
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

    public function deleteRoommateRequest()
    {
        $this->traitDeleteRoommateRequest($this->user);
            
        $this->dispatch("actionTakenOnUser");
        $this->dispatch("refreshChildren:{$this->user->id}");
        $this->dispatch("resetUsers", $this->user->id);

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.components.modals.delete-roommate-request-modal');
    }
}
