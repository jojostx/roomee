<?php

namespace App\Http\Livewire;

use App\Models\User;
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
            "echo-private:request.{$id},RoommateRequestUpdated" => "showRequestToastNotification"
        ];
    }

    public function mount()
    {
        $authUser = auth()->user();
        $this->blockers = $authUser->blockers;
        $this->blocklist =  $authUser->blocklists()->pluck('blockee_id');
        $this->users = User::gender($authUser->gender)
            ->school($authUser->school_id)->excludeUser($authUser->id)->whereIntegerNotInRaw('id', $this->blocklist)
            ->with('course')
            ->get()->sortByDesc('similarity_score');
    }

    //polling function that polls the database for updates to the users blocking the auth user
    //it finds the blockers by using the collection intersect function and fires/emits an event
    //to all the blockers card to refresh itself
    public function refreshChildren()
    {
        $blockers = $this->users->intersect(auth()->user()->blockers);

        if ($blockers->isEmpty()) {
            return;
        }

        foreach ($blockers->modelKeys() as $key => $value) {
            $this->emit('refreshChildren:' . $value);
        }
    }

    public function showRequestToastNotification($data)
    {
        dd($data);
    }

    public function resetUsers($username, $action)
    {
        if ($action === 'block') {
            $this->users = $this->users->except(
                auth()->user()->blocklists()->pluck('blockee_id')->toArray()
            );
        }

        return true;
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
