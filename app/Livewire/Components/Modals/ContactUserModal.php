<?php

namespace App\Livewire\Components\Modals;

use App\Livewire\Traits\CanRetrieveUser;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use LivewireUI\Modal\ModalComponent;

class ContactUserModal extends ModalComponent
{
    use CanRetrieveUser;

    public string | User $user;

    public function mount(User $user): void
    {
        $this->user = $user;
    }

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    #[Computed]
    public function verifiedContactChannels()
    {
        $authUser = $this->getAuthModel();
        $user = $this->retrieveUser();

        throw_unless($authUser->isRoommateWith($user));

        $chatRoom = ChatRoom::findBetween($authUser, $user);

        throw_unless($chatRoom && $chatRoom->hasBothSharedContacts());

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
