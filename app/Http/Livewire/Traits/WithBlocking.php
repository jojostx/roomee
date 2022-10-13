<?php

namespace App\Http\Livewire\Traits;

use App\Models\Blocklist;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

trait WithBlocking
{
  abstract protected function getAuthModel(): ?User;
  abstract protected function retrieveUser(): ?User;

  public function blockUser($user_id = null)
  {
    $user = $this->retrieveUser($user_id);

    if (blank($user) || !($user instanceof User)) {
      return;
    }

    try {      
      $blocked = DB::transaction(function ()  use ($user) {
        // delete any existing roommate request
        $this->getAuthModel()->deleteRoommateRequest($user);
  
        // remove user from favorites, delete sent and recieved roommate requests
        $this->getAuthModel()->favorites()->detach($user->getKey());
  
        return $this->getAuthModel()->block($user, true);
      });

      $blocked && Notification::make()
        ->title("You have succesfully blocked **{$user->full_name}**.")
        ->success()
        ->send();
    } catch (\Throwable $th) {
      Notification::make()
        ->title("An error occurred while blocking **{$user->full_name}** please try again later.")
        ->success()
        ->send();
    }
  }

  public function unblockUser($user_id = null)
  {
    $user = $this->retrieveUser($user_id);

    if (blank($user) || !($user instanceof User)) {
      return;
    }

    $this->getAuthModel()
      ->unblock($user) &&
      Notification::make()
      ->title("**{$user->full_name}** has been unblocked.")
      ->success()
      ->send();
  }

  protected function canBeRemovedFromBlocklist(User $user): bool
  {
    return Blocklist::query()
      ->where([
        ['blocker_id', '=', $this->getAuthModel()->getKey()],
        ['blocker_id', '=', $user->getKey()]
      ])
      ->exists();
  }
}
