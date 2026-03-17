<?php

namespace App\Livewire\Traits;

use App\Models\User;

trait CanRetrieveUser
{
    protected function retrieveUser($user_id = null): ?User
    {
        if (is_int($user_id) || (is_string($user_id) && ctype_digit($user_id))) {
            return User::query()->find((int) $user_id);
        }

        if (is_string($user_id)) {
            return User::query()->firstWhere('uuid', $user_id);
        }

        if ($user_id instanceof User) {
            return $user_id;
        }

        return (property_exists($this, 'user') && $this->user instanceof User) ? $this->user : null;
    }
}
