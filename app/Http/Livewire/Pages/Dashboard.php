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
        $users = $this->getAuthModel()
            ->validNonBlockingUsers()
            ->with(['course:id,name', 'towns:id,name', 'hobbies:id,name', 'dislikes:id,name'])
            ->get();

        return $this->getAuthModel()->calculateUsersSimilarityScore($users);
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
