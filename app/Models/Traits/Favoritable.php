<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function hasBeenAddedToFavorites(Model $user)
    {
        return DB::table('favorites')
            ->where('favoriter_id', $this->id)
            ->where('favoritee_id', $user->id)
            ->exists();
    }
}
