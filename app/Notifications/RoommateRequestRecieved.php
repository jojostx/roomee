<?php

namespace App\Notifications;

use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RoommateRequestRecieved extends Notification implements ShouldQueue
{
    use Queueable;

    public $sender;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $sender)
    {
        $this->sender = $sender;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // return ['mail', 'database'];
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
            ->line('Roommate Request Recieved.')
            ->line("{$this->sender->full_name} sent you a Roommate Request.")
            ->action('View profile', route('profile.view', ['user' => $this->sender], true))
            ->line('Thank you for using our application!');
    }

    public function toDatabase(User $notifiable): array
    {
        return array_merge(
            FilamentNotification::make()
                ->title('Roommate Request Recieved')
                ->body("{$this->sender->full_name} sent you a Roommate Request.")
                ->actions([
                    Action::make('view')
                        ->button()
                        ->url(route('profile.view', ['user' => $this->sender]), shouldOpenInNewTab: true) 
                ])
                ->getDatabaseMessage(),
            [
                'sender_id' => $this->sender->id,
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
            'sender_id' => $this->sender->id,
        ];
    }

    public function broadcastType()
    {
        return 'roommate.request.recieved';
    }
}
