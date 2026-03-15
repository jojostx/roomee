<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperListing
 */
class Listing extends Model
{
    use HasFactory;

    public const RENT_PERIOD_MONTHLY = 'monthly';
    public const RENT_PERIOD_ANNUALLY = 'annually';
    public const HOUSE_RULE_NO_SMOKING = 'no_smoking';
    public const HOUSE_RULE_NO_PETS = 'no_pets';
    public const HOUSE_RULE_NO_OVERNIGHT_GUESTS = 'no_overnight_guests';
    public const HOUSE_RULE_WORK_FROM_HOME_FRIENDLY = 'work_from_home_friendly';

    public const AMENITY_OPTIONS = [
        'inverter_or_solar' => 'Inverter/Solar',
        'fiber_wifi' => 'Fiber WiFi',
        'treated_water' => 'Treated Water',
        'security' => 'Security',
        'prepaid_meter' => 'Prepaid Meter',
    ];

    public const HOUSE_RULE_OPTIONS = [
        self::HOUSE_RULE_NO_SMOKING => 'No Smoking',
        self::HOUSE_RULE_NO_PETS => 'No Pets',
        self::HOUSE_RULE_NO_OVERNIGHT_GUESTS => 'No Overnight Guests',
        self::HOUSE_RULE_WORK_FROM_HOME_FRIENDLY => 'Work-from-home friendly',
    ];

    public const DEALBREAKER_OPTIONS = [
        'must_allow_pets' => 'Must allow pets',
        'must_allow_smoking' => 'Must allow smoking',
        'must_allow_overnight_guests' => 'Must allow overnight guests',
    ];

    public const DEALBREAKER_EXCLUDED_HOUSE_RULES = [
        'must_allow_pets' => self::HOUSE_RULE_NO_PETS,
        'must_allow_smoking' => self::HOUSE_RULE_NO_SMOKING,
        'must_allow_overnight_guests' => self::HOUSE_RULE_NO_OVERNIGHT_GUESTS,
    ];

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'address',
        'city',
        'rent_amount',
        'rent_period',
        'move_in_date',
        'amenities',
        'house_rules',
        'images',
        'is_published',
    ];

    protected $casts = [
        'rent_amount' => 'integer',
        'move_in_date' => 'date',
        'amenities' => 'array',
        'house_rules' => 'array',
        'images' => 'array',
        'is_published' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeForBudgetAndTimeline(Builder $query, User $seeker): Builder
    {
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

    public function scopeForDealbreakers(Builder $query, User $seeker): Builder
    {
        $dealbreakers = data_get($seeker->settings ?? [], 'listing_preferences.dealbreakers', []);

        if (!is_array($dealbreakers)) {
            return $query;
        }

        $dealbreakers = array_values(array_intersect(
            array_map(static fn (mixed $value): string => (string) $value, $dealbreakers),
            array_keys(self::DEALBREAKER_OPTIONS)
        ));

        foreach ($dealbreakers as $dealbreaker) {
            $excludedHouseRule = self::DEALBREAKER_EXCLUDED_HOUSE_RULES[$dealbreaker] ?? null;

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

    public function scopeForAdvancedPreferences(Builder $query, User $seeker): Builder
    {
        return $query
            ->forBudgetAndTimeline($seeker)
            ->forDealbreakers($seeker);
    }
}
