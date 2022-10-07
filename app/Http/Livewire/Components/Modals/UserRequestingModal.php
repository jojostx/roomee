<?php

namespace App\Http\Livewire\Components\Modals;

use App\Http\Livewire\Pages\Blocklist;
use App\Http\Livewire\Pages\Dashboard;
use App\Http\Livewire\Pages\Favorite;
use App\Http\Livewire\Pages\Profile\ViewProfile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use LivewireUI\Modal\ModalComponent;

class UserRequestingModal extends ModalComponent
{
    public string | User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    public function acceptRequest()
    {
        if ($this->getAuthModel()->hasRoommateRequestFrom($this->user)) {
            $this->getAuthModel()->acceptRoommateRequest($this->user);
        }

        $this->closeModal();
    }

    public function deleteRequest()
    {
        if ($this->getAuthModel()->hasSentRoommateRequestTo($this->user)) {
            $this->getAuthModel()->deleteRoommateRequest($this->user);
        }

        $this->closeModalWithEvents($this->getListenerComponents());
    }

    public static function getListenerComponents()
    {
        return [
            ViewProfile::getName() => 'actionTakenOnUser',
            Dashboard::getName() => 'actionTakenOnUser',
            Blocklist::getName() => 'actionTakenOnUser',
            Favorite::getName() => 'actionTakenOnUser',
        ];
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
