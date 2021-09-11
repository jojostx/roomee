<?php

// major refactor needed to unify structure with other components like the Dashboard component

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class Requests extends Component
{
    public $recievedRequests;
    public $sentRequests;
    public $currentPage = 'recieved';

    protected function getListeners()
    {
        $id = auth()->id();

        return [
            'actionTakenOnUser' => 'resetUsers',
            "echo-private:request.{$id},RoommateRequestUpdated" => "handleRoommateRequestUpdatedEvent",
            "echo-private:blocking.{$id},UserBlocked" => "handleUserblockedEvent"
        ];
    }
    
    public function mount()
    {
        $this->recievedRequests = auth()->user()->recievedRequests;
        $this->sentRequests = auth()->user()->sentRequests;
    }

    public function switchPage()
    {
        $this->currentPage = ($this->currentPage === "sent") ? "recieved" : "sent";
        $this->mount();
    }

    public function handleRoommateRequestUpdatedEvent($data)
    {
        $user = User::find($data['requester_id']);

        $this->emit('refreshChildren:' . $user->id);

        if ($data['status'] == 'deleted') {
            return;
        }

        if ($data['status'] == 'accepted') {

            return;
        }

        switch ($data['status']) {
            case 'deleted':
                break;
            case 'accepted':
                $this->showRecievedRequestToastNotification($user->fullname, 'request.Accepted');
                break;
            default:
                $this->showRecievedRequestToastNotification($user->fullname);
                break;
        }
    }

    // fires a card component refresh when another user blocks the currently authenticated user
    public function handleUserblockedEvent($data)
    {
        $this->emit('refreshChildren:' . $data['blocker_id']);
    }

    public function showRecievedRequestToastNotification($name, string $status = 'request.Recieved')
    {
        $this->emit('actionTakenOnUser', $name, $status);
    }
    
    public function render()
    {
        return view('livewire.requests')->layout('layouts.guest');
    }
}
