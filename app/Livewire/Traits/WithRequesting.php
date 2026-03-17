<?php

namespace App\Livewire\Traits;

use App\Models\ChatRoom;
use App\Models\User;
use App\Notifications\RoommateRequestAcceptedNotification;
use App\Notifications\RoommateRequestReceivedNotification;
use Filament\Notifications\Notification;

trait WithRequesting
{
    abstract protected function getAuthModel(): ?User;
    abstract protected function retrieveUser(string|int|User $user): ?User;

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
                ->body("You can now chat with **{$user->full_name}**. Contact details stay hidden until both of you share them from chat.")
                ->send();

            $user->notify(new RoommateRequestAcceptedNotification($this->getAuthModel()));
        }
    }

    protected function unmatchRoommate($user_id = null): bool
    {
        $user = $this->retrieveUser($user_id);

        if (blank($user) || !($user instanceof User)) {
            return false;
        }

        if (!$this->getAuthModel()->isRoommateWith($user)) {
            return false;
        }

        $unmatched = $this->getAuthModel()->unmatchRoommate($user);

        if ($unmatched) {
            Notification::make()
                ->title('Roommate match removed')
                ->warning()
                ->body("You are no longer matched with **{$user->full_name}**. Existing chat history stays available, but contact sharing has been reset.")
                ->send();
        }

        return $unmatched;
    }

    public function openChat($user_id = null): void
    {
        $user = $this->retrieveUser($user_id);

        if (blank($user) || !($user instanceof User)) {
            return;
        }

        if (!$this->getAuthModel()->isRoommateWith($user)) {
            return;
        }

        $room = ChatRoom::firstOrCreateBetween($this->getAuthModel(), $user);

        $this->dispatch('open-chat-room', roomId: $room->id);
    }

    protected function denyRoommateRequest($user_id = null)
    {
        $denied = false;

        $user = $this->retrieveUser($user_id);

        if (blank($user) || !($user instanceof User)) {
            return;
        }

        if ($this->getAuthModel()->hasPendingRoommateRequestFrom($user)) {
            $denied = $this->getAuthModel()->denyRoommateRequest($user);
        }

        if ($denied) {
            Notification::make()
                ->title('Request denied successfully')
                ->success()
                ->body("**{$user->full_name}** Cannot send you another roommate request until you accept this one.")
                ->send();
        }
    }

    public function showDeleteRequestModal($user_id = null): void
    {
        $user = $this->retrieveUser($user_id);

        if (blank($user) || !($user instanceof User)) {
            return;
        }

        $this->mountAction('deleteRoommateRequest', ['user' => $user->uuid]);
    }

    public function showReportOrBlockModal($user_id = null): void
    {
        $user = $this->retrieveUser($user_id);

        if (blank($user) || !($user instanceof User)) {
            return;
        }

        $this->mountAction('reportUser', ['user' => $user->uuid]);
    }
}
