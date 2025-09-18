<?php

namespace App\Http\Livewire\Components\Cards\RoommateRequests;

use Livewire\Component;

class SentRoommateRequestCard extends Component
{
    public $user;
    public $roommateRequest;

    public function mount()
    {
        $this->user = $this->roommateRequest->recipient;
    }

    protected function getListeners()
    {
        return [
            'refreshChildren:' . $this->user->id => '$refresh',
        ];
    }

    public function showDeleteRoommateRequestModal()
    {
        $this->emit('openModal', 'components.modals.delete-roommate-request-modal', ["user" => $this->user->uuid]);
    }

    public function render()
    {
        return view('livewire.components.cards.roommate-requests.sent-roommate-request-card');
    }
}
