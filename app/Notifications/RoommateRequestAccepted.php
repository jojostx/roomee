<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;

class RoommateRequestAccepted extends Notification implements ShouldQueue
{
    use Queueable;

    public $recipient;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Roommate Request Accepted.')
            ->line("{$this->recipient->fullname} accepted your Roommate Request.")
            ->action('View profile', route('profile.view', ['user' => $this->recipient], true))
            ->line('Thank you for using our application!');
    }

    public function toDatabase(User $notifiable): array
    {
        return array_merge(
            FilamentNotification::make()
                ->title('Roommate Request Accepted')
                ->body("{$this->recipient->fullname} accepted your Roommate Request.")
                ->actions([
                    Action::make('view')
                        ->button()
                        ->url(route('profile.view', ['user' => $this->recipient]), shouldOpenInNewTab: true)
                ])
                ->getDatabaseMessage(),
            [
                'recipient_id' => $this->recipient->id,
            ]
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'recipient_id' => $this->recipient->id,
        ];
    }
}
