<?php

namespace App\Notifications;

use Filament\Actions\Action;
use App\Enums\VerificationStatus;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IdentityVerificationStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected VerificationStatus $status,
        protected ?string $rejectionReason = null,
    ) {
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        if ($this->status === VerificationStatus::APPROVED) {
            return (new MailMessage)
                ->subject('Identity Verification Approved')
                ->line('Your identity verification request has been approved.')
                ->action('Go to Dashboard', route('dashboard'))
                ->line('You can now continue using all core features.');
        }

        return (new MailMessage)
            ->subject('Identity Verification Rejected')
            ->line('Your identity verification request was rejected.')
            ->line('Reason: ' . ($this->rejectionReason ?: 'No reason provided.'))
            ->action('Update Your Profile', route('profile.update'))
            ->line('Please resubmit your verification documents.');
    }

    public function toDatabase($notifiable): array
    {
        if ($this->status === VerificationStatus::APPROVED) {
            return FilamentNotification::make()
                ->title('Identity Verification Approved')
                ->body('Your identity verification has been approved.')
                ->actions([
                    Action::make('dashboard')
                        ->button()
                        ->url(route('dashboard'), shouldOpenInNewTab: true),
                ])
                ->getDatabaseMessage();
        }

        return FilamentNotification::make()
            ->title('Identity Verification Rejected')
            ->body('Your identity verification was rejected. Please review the reason and resubmit.')
            ->actions([
                Action::make('update-profile')
                    ->button()
                    ->url(route('profile.update'), shouldOpenInNewTab: true),
            ])
            ->getDatabaseMessage();
    }

    public function toArray($notifiable): array
    {
        return [
            'status' => $this->status,
            'rejection_reason' => $this->rejectionReason,
        ];
    }
}

