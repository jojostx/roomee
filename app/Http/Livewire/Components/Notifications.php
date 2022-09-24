<?php

namespace App\Http\Livewire\Components;

use App\Models\User;
use Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;

class Notifications extends Component
{
    public $requestNotifs;

    protected static string $view = 'livewire.components.notifications';
  
    public function getAuthModelProperty(): ?User
    {
      return auth()->user();
    }
  
    public function getHasUnreadNotificationsProperty(): bool
    {
      $hasUnread = $this->authModel->unreadNotifications()->exists();
  
      return $hasUnread;
    }

    public function getUnreadNotificationsCountProperty()
    {
        return DB::table('notifications')->where([
            'notifiable_id' => auth()->id(),
            'notifiable_type' => User::class,
            'read_at' => null
        ])->count();
    }
  
    public function getReadNotificationsProperty(): ?array
    {
      return $this->authModel->readNotifications->pluck('id')->toArray();
    }
  
    public function getNotificationsProperty(): ?Collection
    {
      return $this->authModel->notifications;
    }
  
    public function markNotificationAsRead($key)
    {
      $this->notifications->find($key)?->markAsRead();
    }
  
    public function markNotificationAsUnread($key)
    {
      $notification = $this->notifications->find($key);
  
      if (blank($notification)) {
        return;
      }
  
      $notification->read_at = null;
  
      $notification->save();
    }
  
    public function markAllNotificationsAsRead(): ?array
    {
      $this->notifications->markAsRead();
  
      return $this->notifications->pluck('id')->toArray();
    }
  
    public function getViewData(): array
    {
      return [
        'readNotifications' => $this->readNotifications,
        'notifications' => $this->notifications,
      ];
    }
  
    public function render(): View
    {
      return view(static::$view, $this->getViewData());
    }
}
