<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Listing extends Model
{
    use HasFactory;

    public const RENT_PERIOD_MONTHLY = 'monthly';
    public const RENT_PERIOD_ANNUALLY = 'annually';

    public const AMENITY_OPTIONS = [
        'inverter_or_solar' => 'Inverter/Solar',
        'fiber_wifi' => 'Fiber WiFi',
        'treated_water' => 'Treated Water',
        'security' => 'Security',
        'prepaid_meter' => 'Prepaid Meter',
    ];

    public const HOUSE_RULE_OPTIONS = [
        'no_smoking' => 'No Smoking',
        'no_pets' => 'No Pets',
        'no_overnight_guests' => 'No Overnight Guests',
        'work_from_home_friendly' => 'Work-from-home friendly',
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

    public function scopeForBudgetAndTimeline(Builder $query, User $seeker): Builder
    {
        $preferredMoveInDate = data_get($seeker->settings ?? [], 'listing_preferences.move_in_date');

        return $query
            ->when(
                filled($seeker->min_budget),
                fn (Builder $builder) => $builder->where('rent_amount', '>=', (int) $seeker->min_budget)
            )
            ->when(
                filled($seeker->max_budget),
                fn (Builder $builder) => $builder->where('rent_amount', '<=', (int) $seeker->max_budget)
            )
            ->when(
                filled($preferredMoveInDate),
                fn (Builder $builder) => $builder->whereDate('move_in_date', '<=', $preferredMoveInDate)
            )
            ->whereDate('move_in_date', '>=', now()->toDateString());
    }
}
