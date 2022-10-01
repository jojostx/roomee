<?php

namespace App\Http\Livewire\Components\Cards;

use App\Models\User;
use Filament\Notifications\Notification;
use Livewire\Component;

class BlocklistCard extends Component
{
    public User $user;

    public function unblockUser()
    {
        $unblocked = $this->authUser->unblock($this->user);

        if ($unblocked) {
            Notification::make()
                ->title('User unblocked successfully')
                ->success()
                ->body("You have succesfully unblocked **{$this->user->fullname}**")
                ->send();

            $this->emit('actionTakenOnUser', $this->user->fullname);
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
