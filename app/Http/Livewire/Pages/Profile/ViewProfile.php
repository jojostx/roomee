<?php

namespace App\Http\Livewire\Pages\Profile;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\Traits\WithBlocking;
use App\Http\Livewire\Traits\WithFavoriting;
use App\Http\Livewire\Traits\WithRequesting;
use App\Http\Livewire\Traits\CanRetrieveUser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ViewProfile extends Component
{
    use WithFavoriting,
        WithBlocking,
        CanRetrieveUser,
        AuthorizesRequests,
        WithRequesting {
        sendRequest as traitSendRequest;
        acceptRequest as traitAcceptRequest;
    }

    public $user;

    public function mount(User $user)
    {
        $this->authorize('view', $user);
        $this->user = $user;
    }

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    protected function getListeners()
    {
        return [
            'actionTakenOnUser' => '$refresh',
        ];
    }

    public function sendRequest()
    {
        $this->traitSendRequest($this->user);
        $this->emitSelf('actionTakenOnUser');
    }

    public function showDeleteRequestModal()
    {
        $this->emit('openModal', 'components.modals.delete-request-modal', ["user" => $this->user->uuid]);
    }

    public function showReportOrBlockModal()
    {
        $this->emit('openModal', 'components.modals.report-or-block-modal', ["user" => $this->user->uuid]);
    }

    public function showUserBlockingModal()
    {
        $this->emit('openModal', 'components.modals.user-blocking-modal', ["user" => $this->user->uuid]);
    }

    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.profile.view-profile');

        return $view->layout('layouts.guest');
    }
}
