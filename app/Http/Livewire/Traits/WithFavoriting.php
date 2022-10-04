<?php

namespace App\Http\Livewire\Traits;

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

trait WithFavoriting
{
    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    public function favorite()
    {
        $this->getAuthModel()
            ->addToFavorites($this->user);
        
        Notification::make()
            ->title("You have succesfully added **{$this->user->fullname}** to your Favorites.")
            ->success()
            ->send();
    }

    public function unfavorite()
    {
        $this->getAuthModel()
            ->removeFromFavorites($this->user);

        Notification::make()
            ->title("You have unfavorited **{$this->user->fullname}**.")
            ->success()
            ->send();
    }
}
