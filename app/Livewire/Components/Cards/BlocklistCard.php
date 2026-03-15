<?php

namespace App\Livewire\Components\Cards;

use App\Models\User;
use Filament\Notifications\Notification;
use Livewire\Attributes\Computed;
use Livewire\Component;

class BlocklistCard extends Component
{
    public ?User $user;

    public function unblockUser()
    {
        $unblocked = $this->authUser->unblock($this->user);

        if ($unblocked) {
            Notification::make()
                ->title('User unblocked successfully')
                ->success()
                ->body("You have succesfully unblocked **{$this->user->full_name}**")
                ->send();

            $this->dispatch('actionTakenOnUser', $this->user->full_name);
        }

        $this->user = null;
    }

    #[Computed]
    public function authUser(): User
    {
        return User::find(auth()->id());
    }

    public function render()
    {
        return view('livewire.components.cards.blocklist-card');
    }
}
