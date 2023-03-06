<?php

namespace App\Http\Livewire\Traits;

use App\Enums\RoommateRequestStatus;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

trait CanReactToRoommateRequestUpdate
{
    public function handleRoommateRequestUpdatedEvent($data)
    {
        $recipient = User::query()->find($data['recipient_id']);
        $sender = User::query()->find($data['sender_id']);
        $status = RoommateRequestStatus::tryFrom($data['status']);

        if (filled($status) && filled($recipient) && filled($sender)) {
            $this->emit('refreshChildren:' . $recipient->id);
            $this->emit('refreshChildren:' . $sender->id);
            $this->showRequestUpdatedNotification($recipient, $sender, $status);
        }
    }

    public function showRequestUpdatedNotification(User $recipient, User $sender, RoommateRequestStatus $status)
    {
        switch ($status) {
            case RoommateRequestStatus::PENDING:
                Notification::make()
                    ->success()
                    ->title('Roommate Request received!')
                    ->body("**{$sender->full_name}** sent you a roommate request. Kindly attend to it.")->actions([
                        Action::make('view')
                            ->button()
                            ->url(route('profile.view', ['user' => $sender]), shouldOpenInNewTab: true)
                    ])
                    ->send();

                break;
            case RoommateRequestStatus::ACCEPTED:
                Notification::make()
                    ->success()
                    ->title('Roommate Request accepted!')
                    ->body("**{$recipient->full_name}** accepted your roommate request. Kindly contact them ASAP.")->actions([
                        Action::make('view')
                            ->button()
                            ->url(route('profile.view', ['user' => $recipient]), shouldOpenInNewTab: true)
                    ])
                    ->send();

                break;
            default:
                break;
        }
    }
}
