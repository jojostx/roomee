<?php

namespace App\Livewire\Pages\Profile;

use Illuminate\View\View;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Traits\WithBlocking;
use App\Livewire\Traits\WithFavoriting;
use App\Livewire\Traits\WithRequesting;
use App\Livewire\Traits\WithUserActionModals;
use App\Livewire\Traits\CanRetrieveUser;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ViewProfilePage extends Component implements HasForms, HasActions
{
    use InteractsWithForms,
        InteractsWithActions,
        WithUserActionModals,
        WithFavoriting,
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

    public function showContactUserModal(): void
    {
        $this->mountAction('contactUser', ['user' => $this->user->uuid]);
    }

    public function showDeleteRequestModal(): void
    {
        $this->mountAction('deleteRoommateRequest', ['user' => $this->user->uuid]);
    }

    public function showReportOrBlockModal(): void
    {
        $this->mountAction('reportUser', ['user' => $this->user->uuid]);
    }

    public function showUserBlockingModal(): void
    {
        $this->mountAction('blockUser', ['user' => $this->user->uuid]);
    }

    public function render()
    {
        /** @var View */
        $view = view('livewire.pages.profile.view-profile-page');

        return $view->layout('layouts.guest');
    }
}
