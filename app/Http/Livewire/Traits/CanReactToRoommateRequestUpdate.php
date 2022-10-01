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
        $requestee = User::query()->find($data['requestedUser_id']);
        $requester = User::query()->find($data['requester_id']);
        $status = RoommateRequestStatus::tryFrom($data['status']);

        if (filled($status) && filled($requestee) && filled($requester)) {
            $this->emit('refreshChildren:' . $requestee->id);
            $this->emit('refreshChildren:' . $requester->id);
            $this->showRequestUpdatedNotification($requestee, $requester, $status);
        }
    }

    public function showRequestUpdatedNotification(User $requestee, User $requester, RoommateRequestStatus $status)
    {
        switch ($status) {
            case RoommateRequestStatus::PENDING:
                Notification::make()
                    ->success()
                    ->title('Roommate Request recieved!')
                    ->body("**{$requester->fullname}** sent you a roommate request. Kindly attend to it.")->actions([
                        Action::make('view')
                            ->button()
                            ->url(route('profile.view', ['user' => $requester]), shouldOpenInNewTab: true)
                    ])
                    ->send();

                break;
            case RoommateRequestStatus::ACCEPTED:
                Notification::make()
                    ->success()
                    ->title('Roommate Request accepted!')
                    ->body("**{$requestee->fullname}** accepted your roommate request. Kindly contact them ASAP.")->actions([
                        Action::make('view')
                            ->button()
                            ->url(route('profile.view', ['user' => $requestee]), shouldOpenInNewTab: true)
                    ])
                    ->send();

                break;
            default:
                break;
        }
    }
}
