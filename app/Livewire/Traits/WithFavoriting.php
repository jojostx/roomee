<?php

namespace App\Livewire\Traits;

use App\Models\Favorite;
use App\Models\User;
use Filament\Notifications\Notification;

trait WithFavoriting
{
    abstract protected function getAuthModel(): ?User;
    abstract protected function retrieveUser(string|int|User|null $user): ?User;

    public function favorite(User|string|int|null $user_id = null)
    {
        $user = $this->retrieveUser($user_id);

        if (blank($user) || !($user instanceof User)) {
            return;
        }

        if (!$this->isFavoritable($user)) {
            return;
        }

        $this->getAuthModel()
            ->addToFavorites($user);

        Notification::make()
            ->title("You have succesfully added **{$user->full_name}** to your Favorites.")
            ->success()
            ->send();
    }

    public function unfavorite($user_id = null)
    {
        $user = $this->retrieveUser($user_id);

        if (blank($user) || !($user instanceof User)) {
            return;
        }

        if (!$this->canBeRemovedFromFavorites($user)) {
            return;
        }

        $this->getAuthModel()
            ->removeFromFavorites($user);

        Notification::make()
            ->title("**{$user->full_name}** has been removed from your Favorites.")
            ->success()
            ->send();
    }

    protected function isFavoritable(User $user): bool
    {
        $auth_user = $this->getAuthModel();

        return $auth_user->isValidUser($user) && !$this->canBeRemovedFromFavorites($user);
    }

    protected function canBeRemovedFromFavorites(User $user): bool
    {
        $auth_user = $this->getAuthModel();

        return Favorite::query()
                ->where([
                    ['favoriter_id', '=', $auth_user->id],
                    ['favoritee_id', '=', $user->id]
                ])
                ->exists();
    }
}
