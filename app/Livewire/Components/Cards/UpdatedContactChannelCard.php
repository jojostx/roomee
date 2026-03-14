<?php

namespace App\Livewire\Components\Cards;

use App\Enums\ContactChannelType;
use App\Models\ContactChannel;
use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Filament\Forms;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class UpdatedContactChannelCard extends Component implements HasForms
{
    use InteractsWithForms, WithRateLimiting;

    public ContactChannel $contactChannel;

    public bool $is_enabled;
    public bool $show;

    protected $listeners = ['deleteContactChannel'];

    public function mount(ContactChannel $contactChannel, bool $show): void
    {
        $this->contactChannel = $contactChannel;
        $this->show = $show;

        $this->form->fill([
            'is_enabled' => $this->contactChannel?->is_enabled ?? false,
        ]);
    }

    protected function getListeners()
    {
        return ['deleteContactChannel:' . $this->contactChannel->uuid => 'deleteContactChannel'];
    }

    protected function onValidationError(ValidationException $exception): void
    {
        Notification::make()
            ->title($exception->getMessage())
            ->danger()
            ->send();
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Toggle::make('is_enabled')
                ->hiddenLabel()
                ->inline()
                ->afterStateUpdated(fn () => $this->submit())
                ->live(),
        ];
    }

    protected function getAuthUser(): User
    {
        return \auth()->user();
    }

    protected function submit()
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $exception = ValidationException::withMessages([
                'is_enabled' => "Slow down! Please wait another {$exception->minutesUntilAvailable} minutes to toggle contact channel.",
            ]);
            $this->onValidationError($exception);
            return;
        }

        $data = $this->form->getState();

        if ($this->contactChannel->markAs($data['is_enabled'])) {
            Notification::make()
                ->title(function () use ($data) {
                    $state = $data['is_enabled'] ? 'enabled' : 'disabled';
                    $channel_type = \ucfirst($this->contactChannel->type);
                    return "Your **$channel_type** Contact channel has been $state";
                })
                ->success()
                ->send();
        }
    }

    public function showDeleteConfirmationPrompt()
    {
        Notification::make()
            ->title('Delete Contact channel')
            ->body(function () {
                $channel_type = \ucfirst($this->contactChannel->type);
                return "Are you sure you want to delete Your **$channel_type** Contact channel?";
            })
            ->actions([
                Action::make('Delete')
                    ->button()
                    ->color('danger')
                    ->dispatch("deleteContactChannel:" . $this->contactChannel->uuid)
                    ->close(),
                Action::make('Cancel')
                    ->color('gray')
                    ->close(),
            ])
            ->persistent()
            ->danger()
            ->send();
    }

    public function deleteContactChannel()
    {
        if ($this->contactChannel->delete()) {
            Notification::make()
                ->title(function () {
                    $channel_type = \ucfirst($this->contactChannel->type);
                    return "Your **$channel_type** Contact channel has been deleted";
                })
                ->success()
                ->send();
        }

        $this->dispatch('reload-page');
    }

    public function render()
    {
        return view('livewire.components.cards.updated-contact-channel-card');
    }
}
