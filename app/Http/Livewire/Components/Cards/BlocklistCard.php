<?php

namespace App\Http\Livewire\Components\Cards;

use App\Models\User;
use Livewire\Component;

class BlocklistCard extends Component
{
    public User $user;

    public function mount()
    {
    }

    public function unblockUser()
    {
        $blocked = $this->authUser->unblock($this->user);

        if ($blocked) {
            $this->emit('actionTakenOnUser', $this->user->fullname, 'unblock');
        }

        $this->user = null;
    }

    public function getAuthUserProperty(): User
    {
        return User::find(auth()->id());
    }

    public function render()
    {
        return view('livewire.components.cards.blocklist-card');
    }
}
