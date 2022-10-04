<?php

namespace App\Http\Livewire\Pages\Profile;

use App\Http\Livewire\Traits\WithFavoriting;
use App\Models\User;
use App\Notifications\RoommateRequestRecieved;
use Filament\Notifications\Notification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ViewProfile extends Component
{
    use WithFavoriting, AuthorizesRequests;

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

    public function block()
    {
        $this->getAuthModel()->block($this->user);

        Notification::make()
            ->title("User blocked succesfully")
            ->title("You have succesfully blocked **{$this->user->fullname}**. They will be unable to view your profile or send you roommate request.")
            ->success()
            ->send();
    }

    public function unblock()
    {
        $blocked = $this->getAuthModel()->unblock($this->user);

        if ($blocked) {
            Notification::make()
                ->title('User unblocked successfully')
                ->success()
                ->body("You have succesfully unblocked **{$this->user->fullname}**")
                ->send();
        }
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

    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.profile.view-profile');

        return $view->layout('layouts.guest');
    }
}
