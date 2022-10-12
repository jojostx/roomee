<?php

namespace App\Http\Livewire\Components\Modals;

use App\Http\Livewire\Traits\CanRetrieveUser;
use App\Http\Livewire\Traits\WithRequesting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use LivewireUI\Modal\ModalComponent;

class DeleteRequestModal extends ModalComponent
{
    use CanRetrieveUser;
    use WithRequesting{
        deleteRequest as traitDeleteRequest;
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

    public function deleteRequest()
    {
        $this->traitDeleteRequest($this->user);
            
        $this->emit("actionTakenOnUser");
        $this->emit("refreshChildren:{$this->user->id}");
        $this->emit("resetUsers", $this->user->id);

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.components.modals.delete-request-modal');
    }
}
