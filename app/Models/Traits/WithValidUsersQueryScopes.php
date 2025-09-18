<?php

namespace App\Models\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait WithValidUsersQueryScopes
{
  /** @todo consider using subquery to optimize exclusion of blockers and blocklist in query */

  abstract public function blocklists(): BelongsToMany;
  abstract public function blockers(): BelongsToMany;

  public function scopeValidUsers(Builder $query): Builder
  {
    return $query
      ->excludeUser($this->id)
      ->gender($this->gender)
      ->school($this->school_id);
  }

  public function scopeValidNonBlockingUsers(Builder $query): Builder
  {
    return $query
      ->validUsers($this)
      ->whereIntegerNotInRaw('id', $this->blocklists()->pluck('blockee_id')->toArray())
      ->whereIntegerNotInRaw('id', $this->blockers()->pluck('blocker_id')->toArray());
  }

  public function scopeValidNonBlockedUsers(Builder $query): Builder
  {
    return $query->validUsers($this)->whereIntegerNotInRaw('id', $this->blocklists()->pluck('blockee_id')->toArray());
  }

  public function scopeValidNonBlockedByUsers(Builder $query): Builder
  {
    return $query->validUsers($this)->whereIntegerNotInRaw('id', $this->blockers()->pluck('blocker_id')->toArray());
  }

  /** checks */
  public function isValidUser(?User $user = null): bool
  {
    if (blank($user)) return false;

    return $this->validUsers($this)
      ->where($user->getKeyName(), $user->getKey())
      ->exists();
  }

  public function isValidNonBlockingUser(?User $user = null): bool
  {
    if (blank($user)) return false;

    return $this->validNonBlockingUsers($this)
      ->where($user->getKeyName(), $user->getKey())
      ->exists();
  }

  public function isValidNonBlockedUser(?User $user = null): bool
  {
    if (blank($user)) return false;

    return $this->validNonBlockedUsers($this)
      ->where($user->getKeyName(), $user->getKey())
      ->exists();
  }

  public function isValidNonBlockedByUser(?User $user = null): bool
  {
    if (blank($user)) return false;

    return $this->validNonBlockedByUsers($this)
      ->where($user->getKeyName(), $user->getKey())
      ->exists();
  }
}
