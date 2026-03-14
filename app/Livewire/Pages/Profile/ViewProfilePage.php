<?php

namespace App\Livewire\Pages\Profile;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Traits\WithBlocking;
use App\Livewire\Traits\WithFavoriting;
use App\Livewire\Traits\WithRequesting;
use App\Livewire\Traits\CanRetrieveUser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ViewProfilePage extends Component
{
    use WithFavoriting,
        WithBlocking,
        CanRetrieveUser,
        AuthorizesRequests,
        WithRequesting {
        sendRoommateRequest as traitSendRoommateRequest;
        acceptRoommateRequest as traitAcceptRoommateRequest;
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

    public function acceptRoommateRequest()
    {
        $this->traitAcceptRoommateRequest($this->user);
        $this->dispatch('actionTakenOnUser');
    }

    public function sendRoommateRequest()
    {
        $this->traitSendRoommateRequest($this->user);
        $this->dispatchSelf('actionTakenOnUser');
    }

    public function showContactUserModal()
    {
        $this->dispatch('openModal', 'components.modals.contact-user-modal', ["user" => $this->user->uuid]);
    }

    public function showDeleteRequestModal()
    {
        $this->dispatch('openModal', 'components.modals.delete-roommate-request-modal', ["user" => $this->user->uuid]);
    }

    public function showReportOrBlockModal()
    {
        $this->dispatch('openModal', 'components.modals.report-or-block-modal', ["user" => $this->user->uuid]);
    }

    public function showUserBlockingModal()
    {
        $this->dispatch('openModal', 'components.modals.user-blocking-modal', ["user" => $this->user->uuid]);
    }

    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.profile.view-profile-page');

        return $view->layout('layouts.guest');
    }
}
