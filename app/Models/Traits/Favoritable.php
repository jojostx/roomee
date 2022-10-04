<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;

trait Favoritable
{
    use Blockable;

    public function addToFavorites(Model $user)
    {
        if ($this->isBlockedBy($user) || $this->hasBlocked($user)) {
            return;
        }

        $this->favorites()->attach($user->id);
    }

    public function removeFromFavorites(Model $user)
    {
        if ($this->hasBlocked($user)) {
            return;
        }

        $this->favorites()->detach($user->id);
    }
}
