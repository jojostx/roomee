<?php

namespace App\Http\Livewire;

use App\Models\Blocklist;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public $blocklist;
    public $users;

    protected $listeners = ['userBlocked' => 'pong'];

    public function mount()
    {
        $authUser = auth()->user();

        $this->blocklist = Blocklist::where('blocker_id', $authUser->id)->pluck('blockee_id')->toArray();
        $this->users = User::gender($authUser->gender)
            ->school($authUser->school_id)->excludeUser($authUser->id)->whereIntegerNotInRaw('id', $this->blocklist)
            ->with('course')
            ->get();
    }

    public function pong($username)
    {
        $this->users = $this->users->except(
            Blocklist::where('blocker_id', auth()->user()->id)->pluck('blockee_id')->toArray()
        );
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}