<?php

namespace App\Livewire\Components\Cards;

use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Traits\CanRetrieveUser;
use App\Livewire\Traits\WithFavoriting;
use App\Livewire\Traits\WithRequesting;

class DashboardCard extends Component
{
    use
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

    public function showDeleteRequestModal()
    {
        $this->dispatch('openModal', 'components.modals.delete-roommate-request-modal', ["user" => $this->user->uuid]);
    }

    public function showReportOrBlockModal()
    {
        $this->dispatch('openModal', 'components.modals.report-or-block-modal', ["user" => $this->user->uuid]);
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
