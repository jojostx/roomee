<?php

namespace App\Livewire\Components\Modals;

use App\Models\User;
use App\Livewire\Traits\CanRetrieveUser;
use App\Livewire\Traits\WithBlocking;
use Illuminate\Support\Facades\Auth;
use LivewireUI\Modal\ModalComponent;

class UserBlockingModal extends ModalComponent
{
    use CanRetrieveUser, WithBlocking;

    public string | User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    public function submit()
    {
        $hasBeenBlocked = $this->getAuthModel()->hasBlocked($this->user);

        if (!$hasBeenBlocked) {
            $this->blockUser();
        } else {
            $this->unblockUser();
        };

        $this->dispatch('actionTakenOnUser');
        $this->closeModal();
    }

    public static function modalMaxWidth(): string
    {
        return 'sm';
    }

    public function render()
    {
        return view('livewire.components.modals.user-blocking-modal');
    }
}
