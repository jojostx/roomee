<?php

namespace App\Http\Livewire\Components\Cards;

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FavoriteCard extends Component
{
    public $user;

    public function mount()
    {
    }

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    public function unfavorite()
    {
        if ($this->user->id === auth()->user()->id) {
            return;
        }

        $this->getAuthModel()->favorites()->detach($this->user->id);

        Notification::make()
            ->title("You have unfavorited **{$this->user->fullname}**.")
            ->success()
            ->send();

        $this->user = null;
    }

    public function render()
    {
        return view('livewire.components.cards.favorite-card');
    }
}
