<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Traits\Favoriting;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class ViewProfile extends Component
{
    use Favoriting, AuthorizesRequests;

    public $user;

    public function mount(User $user)
    {
        $this->authorize('view', $user);
        $this->user = $user;
    }

    public function block()
    {
        $this->authorize('block', $this->user);
        auth()->user()->blocklists()->attach($this->user->id);
        $this->emit('actionTakenOnUser', $this->user->fullname, 'block');
    }

    public function unblock()
    {
        auth()->user()->blocklists()->detach($this->user->id);
        $this->emit('actionTakenOnUser', $this->user->fullname, 'unblock');

    }

    public function render()
    {
        return view('livewire.view-profile')->layout('layouts.guest');
    }
}
