<?php

namespace App\Http\Livewire\Traits;

use App\Models\RoommateRequest;
use App\Models\User;
use App\Notifications\RoommateRequestRecieved;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

trait WithRequesting
{
    use CanRetrieveUser;

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    public function sendRequest($user_id = null)
    {
        $user = $this->getUser($user_id);

        if (blank($user) || !($user instanceof User)) {
            return;
        }

        $this->getAuthModel()->sendRoommateRequest($user);

        Notification::make()
            ->title('Request sent successfully')
            ->success()
            ->body("Your roommate request have been sent to **{$user->full_name}**. You will be notified when they accept.")
            ->send();

        $user->notify(new RoommateRequestRecieved($this->getAuthModel()));
    }

    public function showDeleteRequestModal($user_id = null)
    {
        $user = $this->getUser($user_id);

        if (blank($user) || !($user instanceof User)) {
            return;
        }

        $this->emit('openModal', 'components.modals.delete-request-modal', ["user" => $user->uuid]);
    }

    public function showReportOrBlockModal($user_id = null)
    {
        $user = $this->getUser($user_id);

        if (blank($user) || !($user instanceof User)) {
            return;
        }

        $this->emit('openModal', 'components.modals.report-or-block-modal', ["user" => $user->uuid]);
    }
}
