<?php

namespace App\Http\Livewire\Traits;

use App\Models\User;

trait CanRetrieveUser
{
  protected function getUser($user_id = null): ?User
  {
      if (is_string($user_id) || is_int($user_id)) {
          $user = User::query()->firstWhere('id', $user_id);
      } elseif ($user_id instanceof User) {
          $user = $user_id;
      } else {
          $user = (property_exists($this, 'user') && $this->user instanceof User) ? $this->user : null;
      }

      return $user;
  }
}
