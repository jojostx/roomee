<?php

namespace App\Http\Livewire\Pages;

use Livewire\Component;

class Favorite extends Component
{
    protected $listeners = ['actionTakenOnUser' => '$refresh'];

    public function getFavoritedUsersProperty()
    {
        return auth()->user()->favorites;
    }
    
    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.favorite');

        return $view->layout('layouts.guest');
    }
}
