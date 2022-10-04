<?php

namespace App\Http\Livewire\Components\Cards;

use Livewire\Component;
use App\Http\Livewire\Traits\Favoriting;
use App\Models\User;
use App\Notifications\RoommateRequestRecieved;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardCard extends Component
{
    use Favoriting;

    public $user;
    public $course;
    public $requestId = NULL;

    protected function getListeners()
    {
        return [
            'refreshChildren:' . $this->user->id => '$refresh',
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

    public function sendRequest()
    {
        $this->getAuthModel()->sendRoommateRequest($this->user);

        Notification::make()
            ->title('Request sent successfully')
            ->success()
            ->body("Your roommate request have been sent to **{$this->user->fullname}**. You will be notified when they accept.")
            ->send();

        $this->user->notify(new RoommateRequestRecieved($this->getAuthModel()));
    }

    public function showDeleteRequestModal()
    {
        $this->emit('openModal', 'components.modals.delete-request-modal', ["user" => $this->user->uuid]);
    }

    public function showReportOrBlockModal()
    {
        $this->emit('openModal', 'components.modals.report-or-block-modal', ["user" => $this->user->uuid]);
    }

    public function getIsBlockerProperty()
    {
        $blocking = DB::table('blocklists')->where([
            'blocker_id' => $this->user->id,
            'blockee_id' => auth()->id()
        ])->get();

        return $blocking->count();
    }

    public function render()
    {
        return view('livewire.components.cards.dashboard-card');
    }
}
