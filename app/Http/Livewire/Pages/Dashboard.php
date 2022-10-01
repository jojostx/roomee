<?php

namespace App\Http\Livewire\Pages;

use App\Http\Livewire\Traits\CanReactToRoommateRequestUpdate;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    use CanReactToRoommateRequestUpdate;

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    protected function getListeners()
    {
        $id = $this->getAuthModel()->id;

        return [
            'actionTakenOnUser' => '$refresh',
            "echo-private:request.{$id},RoommateRequestUpdated" => "handleRoommateRequestUpdatedEvent",
            "echo-private:blocking.{$id},UserBlocked" => "handleUserblockedEvent"
        ];
    }

    public function getUsersProperty(): Collection
    {
        $user = $this->getAuthModel();

        $users = User::excludeUser($user->id)
            ->whereIntegerNotInRaw('id', $this->blockedUsers)
            ->whereIntegerNotInRaw('id', $this->blockers)
            ->gender($user->gender)
            ->school($user->school_id)
            ->with(['course:id,name', 'towns:id,name', 'hobbies:id,name', 'dislikes:id,name'])
            ->get();

        return $user->calculateUsersSimilarityScore($users);
    }

    public function getBlockedUsersProperty()
    {
        return DB::table('blocklists')->where(['blocker_id' => $this->getAuthModel()->id])->get('blockee_id');
    }

    public function getBlockersProperty()
    {
        return DB::table('blocklists')->where(['blockee_id' => $this->getAuthModel()->id])->get('blocker_id');
    }

    // fires a card component refresh when another user blocks the currently authenticated user
    public function handleUserblockedEvent($data)
    {
        $this->emit('refreshChildren:' . $data['blocker_id']);
    }

    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.dashboard');

        return $view->layout('layouts.guest');
    }
}
