<?php

namespace App\Http\Livewire\Pages\Profile;

use App\Http\Livewire\Traits\Favoriting;
use App\Models\User;
use App\Notifications\RoommateRequestRecieved;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
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

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    public function block()
    {
        $this->getAuthModel()->block($this->user);
        
        $this->emit('actionTakenOnUser', $this->user->fullname, 'block');
    }

    public function unblock()
    {
        $blocked = $this->getAuthModel()->unblock($this->user);

        if ($blocked) {
            $this->emit('actionTakenOnUser', $this->user->fullname, 'unblock');
        }
    }

    public function sendRequest()
    {
        $this->getAuthModel()->sendRoommateRequest($this->user);

        $this->emit('actionTakenOnUser', $this->user->fullname, 'request');
                
        $this->user->notify(new RoommateRequestRecieved($this->getAuthModel()));
    }

    public function showDeleteRequestPopup()
    {
        $this->emit('showDeleteRequestPopup', $this->user->id);
    }

    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.profile.view-profile');

        return $view->layout('layouts.guest');
    }
}
