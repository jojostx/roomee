<?php

namespace App\Http\Livewire\Cards\Requests;

use App\Models\RoommateRequest;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Sent extends Component
{
    public $user;
    public $request;

    public function mount()
    {
        $this->request = RoommateRequest::firstWhere(
            [
                'requester_id' => auth()->id(),
                'requestee_id' => $this->user->id,
            ]
        );
    }

    public function render()
    {
        return view('livewire.cards.requests.sent');
    }
}
