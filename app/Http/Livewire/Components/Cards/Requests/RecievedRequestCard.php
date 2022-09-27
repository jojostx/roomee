<?php

namespace App\Http\Livewire\Components\Cards\Requests;

use App\Models\User;
use App\Notifications\RoommateRequestAccepted;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RecievedRequestCard extends Component
{
    public $user;
    public $request;

    protected function getListeners()
    {
        return [
            'refreshChildren:' . $this->user->id => '$refresh',
        ];
    }

    public function mount()
    {
        $this->user = $this->request->sender;
    }

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    public function showDeleteRequestPopup()
    {
        $this->emit('showDeleteRequestPopup', $this->user->id);
    }

    public function acceptRequest()
    {
        $wasUpdated = $this->getAuthModel()->acceptRoommateRequest($this->user);

        if ($wasUpdated) {
            $this->user->notify(new RoommateRequestAccepted($this->getAuthModel()));
        }

        $this->request->refresh();
    }

    public function declineRequest()
    {
        $wasUpdated = $this->getAuthModel()->denyRoommateRequest($this->user);

        if ($wasUpdated) {
            $this->user->notify(new RoommateRequestAccepted($this->getAuthModel()));
        }

        $this->request->refresh();
    }

    public function render()
    {
        return view('livewire.components.cards.requests.recieved-request-card');
    }
}
