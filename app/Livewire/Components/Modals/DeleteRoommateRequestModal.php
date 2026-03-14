<?php

namespace App\Livewire\Components\Modals;

use App\Livewire\Traits\CanRetrieveUser;
use App\Livewire\Traits\WithRequesting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use LivewireUI\Modal\ModalComponent;

class DeleteRoommateRequestModal extends ModalComponent
{
    use CanRetrieveUser;
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

    public static function modalMaxWidth(): string
    {
        return 'sm';
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
