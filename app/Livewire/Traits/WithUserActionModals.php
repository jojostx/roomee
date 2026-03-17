<?php

namespace App\Livewire\Traits;

use App\Models\ChatRoom;
use App\Models\Report;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Notifications\Notification;

trait WithUserActionModals
{
    abstract protected function getAuthModel(): ?User;

    protected function resolveUserFromArguments(array $arguments): ?User
    {
        $uuid = $arguments['user'] ?? null;

        if (blank($uuid)) {
            return null;
        }

        return User::query()->firstWhere('uuid', $uuid);
    }

    public function contactUserAction(): Action
    {
        return Action::make('contactUser')
            ->label('Contact User')
            ->modalHeading(fn (array $arguments): string => 'Contact ' . ($this->resolveUserFromArguments($arguments)?->full_name ?? 'User'))
            ->modalSubmitAction(false)
            ->modalCancelAction(fn (Action $action) => $action->label('Close'))
            ->modalWidth('sm')
            ->modalContent(function (array $arguments) {
                $authUser = $this->getAuthModel();
                $user = $this->resolveUserFromArguments($arguments);

                throw_unless($user && $authUser->isRoommateWith($user));

                $chatRoom = ChatRoom::findBetween($authUser, $user);

                throw_unless($chatRoom && $chatRoom->hasBothSharedContacts());

                $channels = $user->getVerifiedContactChannels();

                return view('livewire.partials.contact-user-modal-content', [
                    'channels' => $channels,
                ]);
            });
    }

    public function deleteRoommateRequestAction(): Action
    {
        return Action::make('deleteRoommateRequest')
            ->label('Delete Request')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Delete Request')
            ->modalDescription(fn (array $arguments): string => 'Are you sure you want to delete the request to ' . ($this->resolveUserFromArguments($arguments)?->full_name ?? 'this user') . '?')
            ->modalSubmitActionLabel('Delete')
            ->modalWidth('sm')
            ->action(function (array $arguments) {
                $user = $this->resolveUserFromArguments($arguments);

                if (blank($user)) {
                    return;
                }

                $authUser = $this->getAuthModel();

                if ($authUser->hasPendingSentRoommateRequestTo($user)) {
                    $authUser->deleteRoommateRequest($user);

                    Notification::make()
                        ->title('Request deleted successfully')
                        ->success()
                        ->body("Your roommate request to **{$user->full_name}** has been deleted.")
                        ->send();
                }

                $this->dispatch('actionTakenOnUser');
                $this->dispatch("refreshChildren:{$user->id}");
                $this->dispatch('resetUsers', $user->id);
            });
    }

    public function reportUserAction(): Action
    {
        return Action::make('reportUser')
            ->label('Report User')
            ->icon('heroicon-o-flag')
            ->color('warning')
            ->requiresConfirmation()
            ->modalHeading(fn (array $arguments): string => 'Report ' . ($this->resolveUserFromArguments($arguments)?->full_name ?? 'User'))
            ->modalDescription('Select the relevant issues to submit a report.')
            ->modalSubmitActionLabel('Submit')
            ->modalWidth('sm')
            ->schema([
                CheckboxList::make('report_ids')
                    ->label('Reports')
                    ->options(Report::pluck('description', 'id')->transform(fn ($val) => ucfirst($val)))
                    ->required()
                    ->exists('reports', 'id'),
            ])
            ->action(function (array $arguments, array $data) {
                $user = $this->resolveUserFromArguments($arguments);

                if (blank($user)) {
                    return;
                }

                if ($this->getAuthModel()->reportUser($user, $data['report_ids'])) {
                    Notification::make()
                        ->title('Report submitted successfully')
                        ->body("Your report has been submitted. Our team will review your report ASAP. Thanks!")
                        ->success()
                        ->send();
                }

                $this->dispatch('actionTakenOnUser');
            });
    }

    public function blockUserAction(): Action
    {
        return Action::make('blockUser')
            ->label('Block User')
            ->icon('heroicon-o-no-symbol')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading(function (array $arguments): string {
                $user = $this->resolveUserFromArguments($arguments);

                if ($user && $this->getAuthModel()->hasBlocked($user)) {
                    return 'Unblock ' . $user->full_name . '?';
                }

                return 'Block ' . ($user?->full_name ?? 'User') . '?';
            })
            ->modalDescription(function (array $arguments): string {
                $user = $this->resolveUserFromArguments($arguments);

                if ($user && $this->getAuthModel()->hasBlocked($user)) {
                    return $user->full_name . ' will be able to view your profile or send you roommate request.';
                }

                return ($user?->full_name ?? 'This user') . ' will no longer be able to view your profile or send you a roommate request.';
            })
            ->modalSubmitActionLabel(function (array $arguments): string {
                $user = $this->resolveUserFromArguments($arguments);

                if ($user && $this->getAuthModel()->hasBlocked($user)) {
                    return 'Unblock';
                }

                return 'Block';
            })
            ->modalWidth('sm')
            ->action(function (array $arguments) {
                $user = $this->resolveUserFromArguments($arguments);

                if (blank($user)) {
                    return;
                }

                if ($this->getAuthModel()->hasBlocked($user)) {
                    $this->unblockUser($user);
                } else {
                    $this->blockUser($user);
                }

                $this->dispatch('actionTakenOnUser');
            });
    }

    public function onboardingStepAction(): Action
    {
        return Action::make('onboardingStep')
            ->modalSubmitAction(false)
            ->modalCancelAction(false)
            ->modalWidth('sm')
            ->modalContent(function (array $arguments) {
                $stepData = $arguments['step_data'] ?? [];

                return view('livewire.partials.onboarding-step-modal-content', [
                    'step_title' => $stepData['title'] ?? '',
                    'step_body' => $stepData['body'] ?? '',
                    'step_cta' => $stepData['cta'] ?? '',
                    'step_link' => $stepData['link'] ?? '#',
                ]);
            });
    }
}
