<?php

namespace App\Livewire\Components\Cards\RoommateRequests;

use App\Livewire\Traits\CanRetrieveUser;
use App\Livewire\Traits\WithUserActionModals;
use App\Models\User;
use App\Notifications\RoommateRequestAcceptedNotification;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ReceivedRoommateRequestCard extends Component implements HasForms, HasActions
{
    use InteractsWithForms, InteractsWithActions, WithUserActionModals, CanRetrieveUser;

    public $user;
    public $roommateRequest;

    protected function getListeners(): array
    {
        return [
            'refreshChildren:' . $this->user->id => '$refresh',
        ];
    }

    public function mount(): void
    {
        $this->user = $this->roommateRequest->sender;
    }

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    public function showDeleteRoommateRequestModal(): void
    {
        $this->mountAction('deleteRoommateRequest', ['user' => $this->user->uuid]);
    }

    public function acceptRoommateRequest()
    {
        $wasUpdated = $this->getAuthModel()->acceptRoommateRequest($this->user);

        if ($wasUpdated) {
            $this->user->notify(new RoommateRequestAcceptedNotification($this->getAuthModel()));
        }

        $this->roommateRequest->refresh();
    }

    public function declineRoommateRequest()
    {
        $wasUpdated = $this->getAuthModel()->denyRoommateRequest($this->user);

        if ($wasUpdated) {
            $this->user->notify(new RoommateRequestAcceptedNotification($this->getAuthModel()));
        }

        $this->roommateRequest->refresh();
    }

    public function render()
    {
        return view('livewire.components.cards.roommate-requests.received-roommate-request-card');
    }
}
