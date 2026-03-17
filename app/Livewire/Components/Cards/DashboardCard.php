<?php

namespace App\Livewire\Components\Cards;

use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Traits\CanRetrieveUser;
use App\Livewire\Traits\WithBlocking;
use App\Livewire\Traits\WithFavoriting;
use App\Livewire\Traits\WithRequesting;
use App\Livewire\Traits\WithUserActionModals;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class DashboardCard extends Component implements HasForms, HasActions
{
    use
        InteractsWithForms,
        InteractsWithActions,
        WithUserActionModals,
        WithBlocking,
        WithFavoriting,
        CanRetrieveUser,
        WithRequesting {
        sendRoommateRequest as traitSendRoommateRequest;
        acceptRoommateRequest as traitAcceptRoommateRequest;
    }

    public $user;
    public $course;
    public $roommateRequestId = NULL;

    protected function getListeners()
    {
        return [
            'refreshChildren:' . $this->user->id => '$refresh',
            'actionTakenOnUser' => '$refresh',
        ];
    }

    public function mount()
    {
        $this->course = $this->user->course;
    }

    protected function getAuthModel(): ?User
    {
        return Auth::user();
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

    public function showDeleteRequestModal(): void
    {
        $this->mountAction('deleteRoommateRequest', ['user' => $this->user->uuid]);
    }

    #[Computed]
    public function isBlocker()
    {
        $blocking = DB::table('blocklists')->where([
            'blocker_id' => $this->user->id,
            'blockee_id' => auth()->id()
        ])->exists();

        return $blocking;
    }

    public function render()
    {
        return view('livewire.components.cards.dashboard-card');
    }
}
