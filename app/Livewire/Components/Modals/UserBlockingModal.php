<?php

namespace App\Livewire\Components\Modals;

use App\Livewire\Traits\CanRetrieveUser;
use App\Livewire\Traits\ClosesModal;
use App\Livewire\Traits\WithBlocking;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserBlockingModal extends Component
{
    use CanRetrieveUser, ClosesModal, WithBlocking;

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

    public function render()
    {
        return view('livewire.components.modals.user-blocking-modal');
    }
}
