<?php

namespace App\Http\Livewire\Traits;

use App\Models\User;
use App\Notifications\RoommateRequestAccepted;
use App\Notifications\RoommateRequestRecieved;
use Filament\Notifications\Notification;

trait WithRequesting
{
    abstract protected function getAuthModel(): ?User;
    abstract protected function retrieveUser(): ?User;

    protected function sendRequest($user_id = null)
    {
        $sent = false;

        $user = $this->getUser($user_id);

        if (blank($user) || !($user instanceof User)) {
            return;
        }

        $sent = $this->getAuthModel()->sendRoommateRequest($user);

        if ($sent) {
            Notification::make()
                ->title('Request sent successfully')
                ->success()
                ->body("Your roommate request have been sent to **{$user->full_name}**. You will be notified when they accept.")
                ->send();

            $user->notify(new RoommateRequestRecieved($this->getAuthModel()));
        }
    }

    protected function deleteRequest($user_id = null)
    {
        $deleted = false;

        $user = $this->getUser($user_id);

        if (blank($user) || !($user instanceof User)) {
            return;
        }

        if ($this->getAuthModel()->hasSentRoommateRequestTo($user)) {
            $deleted = $this->getAuthModel()->deleteRoommateRequest($user);
        }

        if ($deleted) {
            Notification::make()
                ->title('Request deleted successfully')
                ->success()
                ->body("Your roommate request to **{$user->full_name}** has been deleted.")
                ->send();
        }
    }

    protected function acceptRequest($user_id = null)
    {
        $accepted = false;

        $user = $this->getUser($user_id);

        if (blank($user) || !($user instanceof User)) {
            return;
        }

        if ($this->getAuthModel()->hasRoommateRequestFrom($user)) {
            $accepted = $this->getAuthModel()->acceptRoommateRequest($user);
        }

        if ($accepted) {
            Notification::make()
                ->title('Request accepted successfully')
                ->success()
                ->body("You can now contact **{$user->full_name}**.")
                ->send();

            $user->notify(new RoommateRequestAccepted($this->getAuthModel()));
        }
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
