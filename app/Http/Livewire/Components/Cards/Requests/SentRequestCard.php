<?php

namespace App\Http\Livewire\Components\Cards\Requests;

use Livewire\Component;

class SentRequestCard extends Component
{
    public $user;
    public $request;

    public function mount()
    {
        $this->user = $this->request->recipient;
    }

    protected function getListeners()
    {
        return [
            'refreshChildren:' . $this->user->id => '$refresh',
        ];
    }

    public function showDeleteRequestPopup()
    {
        $this->emit('showDeleteRequestPopup', $this->user->id);
    }

    public function render()
    {
        return view('livewire.components.cards.requests.sent-request-card');
    }
}
