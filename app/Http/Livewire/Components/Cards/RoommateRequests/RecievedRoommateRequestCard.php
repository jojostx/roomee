<?php

namespace App\Http\Livewire\Components\Cards\RoommateRequests;

use App\Models\User;
use App\Notifications\RoommateRequestAcceptedNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ReceivedRoommateRequestCard extends Component
{
    public $user;
    public $roommateRequest;

    protected function getListeners()
    {
        return [
            'refreshChildren:' . $this->user->id => '$refresh',
        ];
    }

    public function mount()
    {
        $this->user = $this->roommateRequest->sender;
    }

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    public function showDeleteRoommateRequestModal()
    {
        $this->emit('openModal', 'components.modals.delete-roommate-request-modal', ["user" => $this->user->uuid]);
    }

    public function acceptRoommateRequest()
    {
        $wasUpdated = $this->getAuthModel()->acceptRoommateRequest($this->user);

        if ($wasUpdated) {
            $this->user->notify(new RoommateRequestAcceptedNotification($this->getAuthModel()));
        }

        $this->roommateRequest->refresh();
    }

    public function declineRoommateRequest()
    {
        $wasUpdated = $this->getAuthModel()->denyRoommateRequest($this->user);

        if ($wasUpdated) {
            $this->user->notify(new RoommateRequestAcceptedNotification($this->getAuthModel()));
        }

        $this->roommateRequest->refresh();
    }

    public function render()
    {
        return view('livewire.components.cards.roommate-requests.received-roommate-request-card');
    }
}
