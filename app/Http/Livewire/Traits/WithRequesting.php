<?php

namespace App\Http\Livewire\Traits;

use App\Models\User;
use App\Notifications\RoommateRequestAcceptedNotification;
use App\Notifications\RoommateRequestReceivedNotification;
use Filament\Notifications\Notification;

trait WithRequesting
{
    abstract protected function getAuthModel(): ?User;
    abstract protected function retrieveUser(): ?User;

    protected function sendRoommateRequest($user_id = null)
    {
        $sent = false;

        $user = $this->retrieveUser($user_id);

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

            $user->notify(new RoommateRequestReceivedNotification($this->getAuthModel(), $user));
        }
    }

    protected function deleteRoommateRequest($user_id = null)
    {
        $deleted = false;

        $user = $this->retrieveUser($user_id);

        if (blank($user) || !($user instanceof User)) {
            return;
        }

        if ($this->getAuthModel()->hasPendingSentRoommateRequestTo($user)) {
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

    protected function acceptRoommateRequest($user_id = null)
    {
        $accepted = false;

        $user = $this->retrieveUser($user_id);

        if (blank($user) || !($user instanceof User)) {
            return;
        }

        if ($this->getAuthModel()->hasPendingRoommateRequestFrom($user)) {
            $accepted = $this->getAuthModel()->acceptRoommateRequest($user);
        }

        if ($accepted) {
            Notification::make()
                ->title('Request accepted successfully')
                ->success()
                ->body("You can now contact **{$user->full_name}**.")
                ->send();

            $user->notify(new RoommateRequestAcceptedNotification($this->getAuthModel()));
        }
    }

    public function showDeleteRequestModal($user_id = null)
    {
        $user = $this->retrieveUser($user_id);

        if (blank($user) || !($user instanceof User)) {
            return;
        }

        $this->emit('openModal', 'components.modals.delete-roommate-request-modal', ["user" => $user->uuid]);
    }

    public function showReportOrBlockModal($user_id = null)
    {
        $user = $this->retrieveUser($user_id);

        if (blank($user) || !($user instanceof User)) {
            return;
        }

        $this->emit('openModal', 'components.modals.report-or-block-modal', ["user" => $user->uuid]);
    }
}
