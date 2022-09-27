<?php

namespace App\Http\Livewire\Traits;

use App\Models\User;
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
        $this->emit('actionTakenOnUser', $this->user->fullname, 'favorite');
    }

    public function unfavorite()
    {
        $this->getAuthModel()->favorites()->detach($this->user->id);
    }
}
