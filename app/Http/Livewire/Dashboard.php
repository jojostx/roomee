<?php

namespace App\Http\Livewire;

use App\Http\ModelSimilarity\UserSimilarity;
use App\Models\Blocklist;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public $blocklist;
    public $users;

    protected $listeners = ['actionTakenOnUser' => 'resetUsers'];

    public function mount()
    {
        $authUser = auth()->user();

        $this->blocklist = Blocklist::where('blocker_id', $authUser->id)->pluck('blockee_id')->toArray();
        $this->users = User::gender($authUser->gender)
            ->school($authUser->school_id)->excludeUser($authUser->id)->whereIntegerNotInRaw('id', $this->blocklist)
            ->with('course')
            ->get();
        $this->users = (new UserSimilarity($authUser))->calculateUsersSimilarityScore($this->users);
        $this->users = $this->sortBySimilarity();
    }

    public function sortBySimilarity()
    {
        return $this->users->sortByDesc(function ($user, $key) {
            return $user->similarity_score;
        });
    }

    public function resetUsers($username, $action)
    {
        if ($action === 'block') {
            $this->users = $this->users->except(
                Blocklist::where('blocker_id', auth()->user()->id)->pluck('blockee_id')->toArray()
            );
        }

        return true;
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
