<?php

namespace App\Http\Livewire\Pages\Profile;

use App\Http\Livewire\Traits\Favoriting;
use App\Models\User;
use App\Notifications\RoommateRequestRecieved;
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
        auth()->user()->block($this->user);
        
        $this->emit('actionTakenOnUser', $this->user->fullname, 'block');
    }

    public function unblock()
    {
        $blocked = auth()->user()->unblock($this->user);

        if ($blocked) {
            $this->emit('actionTakenOnUser', $this->user->fullname, 'unblock');
        }
    }

    public function sendRequest()
    {
        auth()->user()->sendRoommateRequest($this->user);

        $this->emit('actionTakenOnUser', $this->user->fullname, 'request');
                
        $this->user->notify(new RoommateRequestRecieved(auth()->user()));
    }

    public function showDeleteRequestPopup()
    {
        $this->emit('showDeleteRequestPopup', $this->user->id);
    }

    public function render()
    {
        return view('livewire.pages.profile.view-profile')->layout('layouts.guest');
    }
}