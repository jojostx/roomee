<?php

namespace App\Http\Livewire\Pages;

use App\Enums\OnUserAction;
use App\Enums\RoommateRequestStatus;
use App\Http\Livewire\Traits\CanReactToRoommateRequestUpdate;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    use CanReactToRoommateRequestUpdate;

    public Collection $users;

    public function mount()
    {
        $this->users = $this->getSimilarUsers(true);
    }

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    protected function getListeners()
    {
        $id = auth()->id();

        return [
            'actionTakenOnUser' => '$refresh',
            "echo-private:request.{$id},RoommateRequestUpdated" => "handleRoommateRequestUpdatedEvent",
            "echo-private:blocking.{$id},UserBlocked" => "handleUserblockedEvent"
        ];
    }

    public function getSimilarUsers(bool $append_similarity_score = true): Collection
    {
        $user = $this->getAuthModel();

        $similar_users = User::excludeUser($user->id)
            ->gender($user->gender)
            ->school($user->school_id)
            ->whereIntegerNotInRaw('id', $this->blockedUsers)
            ->whereIntegerNotInRaw('id', $this->blockers)
            ->with(['course:id,name', 'towns:id,name', 'hobbies:id,name', 'dislikes:id,name'])
            ->get();

        if ($append_similarity_score) {
            $similar_users = $user->calculateUsersSimilarityScore($similar_users);
        }

        return $similar_users;
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
