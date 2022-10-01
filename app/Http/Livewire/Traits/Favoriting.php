<?php

namespace App\Http\Livewire\Traits;

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

trait Favoriting
{
    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    public function favorite()
    {
        if ($this->getAuthModel()->isBlockedBy($this->user)) {
            return;
        }

        $this->getAuthModel()->favorites()->attach($this->user->id);

        Notification::make()
            ->title("You have succesfully added **{$this->user->fullname}** to your Favorites.")
            ->success()
            ->send();
    }

    public function unfavorite()
    {
        $this->getAuthModel()->favorites()->detach($this->user->id);

        Notification::make()
            ->title("You have unfavorited **{$this->user->fullname}**.")
            ->success()
            ->send();
    }
}
