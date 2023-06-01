<?php

namespace App\Http\Livewire\Components\Modals;

use App\Http\Livewire\Traits\CanRetrieveUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use LivewireUI\Modal\ModalComponent;

class ContactUserModal extends ModalComponent
{
    use CanRetrieveUser;

    public string | User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    public function getVerifiedContactChannelsProperty()
    {
        $user = $this->retrieveUser();

        \throw_unless($this->getAuthModel()->isRoommateWith($user));

        return $user?->getVerifiedContactChannels();
    }

    public static function modalMaxWidth(): string
    {
        return 'sm';
    }

    public function render()
    {
        return view('livewire.components.modals.contact-user-modal');
    }
}
