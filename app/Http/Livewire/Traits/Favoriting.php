<?php

namespace App\Http\Livewire\Traits;

use App\Http\ModelSimilarity\UserSimilarity;

trait Favoriting
{
    public function favorite()
    {
        auth()->user()->favorites()->attach($this->user->id);
        $this->emit('actionTakenOnUser', $this->user->fullname, 'favorite');
        $this->user->similarity_score = $this->recalculateUserSimilarity();

    }
    
    public function unfavorite()
    {
        auth()->user()->favorites()->detach($this->user->id);
        $this->user->similarity_score = $this->recalculateUserSimilarity();
    }

    public function recalculateUserSimilarity()
    {
        return (new UserSimilarity(auth()->user()))->calculateUserSimilarityScore($this->user);
    }

}
