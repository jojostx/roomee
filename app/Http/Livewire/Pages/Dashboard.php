<?php

namespace App\Http\Livewire\Pages;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public $blocklist;
    public $blockers;
    public $users;

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
        $authUser = auth()->user();

        $this->blockers = DB::table('blocklists')->where([
            'blockee_id' => auth()->id()
        ])->get('id');

        $this->blocklist =  $authUser->blocklists()->pluck('blockee_id');

        $this->users = User::excludeUser($authUser->id)
            ->gender($authUser->gender)
            ->school($authUser->school_id)
            ->whereIntegerNotInRaw('id', $this->blocklist)
            ->with(['course:id,name', 'towns:id,name', 'hobbies:id,name', 'dislikes:id,name', 'towns:id,name'])
            ->get()->sortByDesc('similarity_score');
    }

    // fires when the authenticated user blocks another user
    public function resetUsers($username, $action)
    {
        if ($action === 'block') {
            $this->users = $this->users->except(
                auth()->user()->blocklists()->pluck('blockee_id')->toArray(),
            );
        }

        return true;
    }

    public function handleRoommateRequestUpdatedEvent($data)
    {
        $user = $this->users->find($data['requester_id']);

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
        return view('livewire.pages.dashboard');
    }
}
