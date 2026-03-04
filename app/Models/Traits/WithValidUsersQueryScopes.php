<?php

namespace App\Models\Traits;

use App\Models\Listing;
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
    $gender = $this->gender;
    $strictGenderFilteringEnabled = $this->isGenderSpecificFilteringEnabled();

    return $query
      ->excludeUser($this->id)
      ->school($this->school_id)
      ->when(
        $strictGenderFilteringEnabled && filled($gender),
        fn (Builder $builder): Builder => $builder->gender($gender)
      )
      ->when(
        filled($gender),
        fn (Builder $builder): Builder => $builder->where(
          fn (Builder $visibilityQuery): Builder => $visibilityQuery
            ->where('gender', $gender)
            ->orWhereJsonContains('settings->matching->strict_gender_filter', false)
        )
      );
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

  /**
   * Filter listing-like queries using the seeker's budget range and preferred move-in timeline.
   * This is a cross-model helper scope and is intended for builders that target the `listings` table.
   */
  public function scopeForBudgetAndTimeline(Builder $query, ?User $seeker = null): Builder
  {
    $seeker = $seeker ?? $this;

    if (blank($seeker) || $query->getModel()->getTable() !== 'listings') {
      return $query;
    }

    $preferredMinBudget = data_get($seeker->settings ?? [], 'listing_preferences.budget_min');
    $preferredMaxBudget = data_get($seeker->settings ?? [], 'listing_preferences.budget_max');
    $preferredMoveInDate = data_get($seeker->settings ?? [], 'listing_preferences.move_in_date');
    $minimumBudget = filled($preferredMinBudget) ? (int) $preferredMinBudget : (filled($seeker->min_budget) ? (int) $seeker->min_budget : null);
    $maximumBudget = filled($preferredMaxBudget) ? (int) $preferredMaxBudget : (filled($seeker->max_budget) ? (int) $seeker->max_budget : null);

    return $query
      ->when(
        filled($minimumBudget),
        fn (Builder $builder) => $builder->where('rent_amount', '>=', (int) $minimumBudget)
      )
      ->when(
        filled($maximumBudget),
        fn (Builder $builder) => $builder->where('rent_amount', '<=', (int) $maximumBudget)
      )
      ->when(
        filled($preferredMoveInDate),
        fn (Builder $builder) => $builder->whereDate('move_in_date', '<=', $preferredMoveInDate)
      )
      ->whereDate('move_in_date', '>=', now()->toDateString());
  }

  public function scopeForDealbreakers(Builder $query, ?User $seeker = null): Builder
  {
    $seeker = $seeker ?? $this;

    if (blank($seeker) || $query->getModel()->getTable() !== 'listings') {
      return $query;
    }

    $dealbreakers = data_get($seeker->settings ?? [], 'listing_preferences.dealbreakers', []);

    if (!is_array($dealbreakers)) {
      return $query;
    }

    $dealbreakers = array_values(array_intersect(
      array_map(static fn (mixed $value): string => (string) $value, $dealbreakers),
      array_keys(Listing::DEALBREAKER_OPTIONS)
    ));

    foreach ($dealbreakers as $dealbreaker) {
      $excludedHouseRule = Listing::DEALBREAKER_EXCLUDED_HOUSE_RULES[$dealbreaker] ?? null;

      if (blank($excludedHouseRule)) {
        continue;
      }

      $query->where(
        fn (Builder $builder): Builder => $builder
          ->whereNull('house_rules')
          ->orWhereJsonDoesntContain('house_rules', $excludedHouseRule)
      );
    }

    return $query;
  }

  public function scopeForAdvancedListingPreferences(Builder $query, ?User $seeker = null): Builder
  {
    return $query
      ->forBudgetAndTimeline($seeker)
      ->forDealbreakers($seeker);
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
