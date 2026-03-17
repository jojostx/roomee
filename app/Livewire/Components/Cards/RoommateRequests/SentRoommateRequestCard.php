<?php

namespace App\Livewire\Components\Cards\RoommateRequests;

use App\Livewire\Traits\CanRetrieveUser;
use App\Livewire\Traits\WithRequesting;
use App\Livewire\Traits\WithUserActionModals;
use App\Models\User;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SentRoommateRequestCard extends Component implements HasForms, HasActions
{
    use InteractsWithForms, InteractsWithActions, WithUserActionModals, CanRetrieveUser;

    public $user;
    public $roommateRequest;

    public function mount(): void
    {
        $this->user = $this->roommateRequest->recipient;
    }

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    protected function getListeners(): array
    {
        return [
            'refreshChildren:' . $this->user->id => '$refresh',
        ];
    }

    public function showDeleteRoommateRequestModal(): void
    {
        $this->mountAction('deleteRoommateRequest', ['user' => $this->user->uuid]);
    }

    public function render()
    {
        return view('livewire.components.cards.roommate-requests.sent-roommate-request-card');
    }
}
