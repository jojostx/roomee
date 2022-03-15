<?php

namespace App\Http\Livewire\Cards\Blocklist;

use App\Models\User;
use Livewire\Component;

class Card extends Component
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
        return view('livewire.cards.blocklist.card');
    }
}
