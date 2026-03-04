<?php

namespace Tests\Feature\Listing;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListingAdvancedFilteringTest extends TestCase
{
    use RefreshDatabase;

    public function test_budget_and_timeline_filter_uses_settings_overrides(): void
    {
        $seeker = User::factory()->create([
            'min_budget' => 80000,
            'max_budget' => 300000,
            'settings' => [
                'listing_preferences' => [
                    'budget_min' => 120000,
                    'budget_max' => 180000,
                    'move_in_date' => now()->addDays(30)->toDateString(),
                ],
            ],
        ]);

        $match = Listing::factory()->create([
            'rent_amount' => 150000,
            'move_in_date' => now()->addDays(20)->toDateString(),
        ]);

        Listing::factory()->create([
            'rent_amount' => 100000,
            'move_in_date' => now()->addDays(20)->toDateString(),
        ]);

        Listing::factory()->create([
            'rent_amount' => 170000,
            'move_in_date' => now()->addDays(40)->toDateString(),
        ]);

        Listing::factory()->create([
            'rent_amount' => 160000,
            'move_in_date' => now()->subDay()->toDateString(),
        ]);

        $ids = Listing::query()
            ->forBudgetAndTimeline($seeker)
            ->pluck('id')
            ->all();

        $this->assertSame([$match->getKey()], $ids);
    }

    public function test_must_allow_pets_dealbreaker_excludes_no_pets_listings(): void
    {
        $seeker = User::factory()->create([
            'settings' => [
                'listing_preferences' => [
                    'dealbreakers' => ['must_allow_pets'],
                ],
            ],
        ]);

        $allowsPets = Listing::factory()->create([
            'house_rules' => [Listing::HOUSE_RULE_NO_SMOKING],
        ]);

        Listing::factory()->create([
            'house_rules' => [Listing::HOUSE_RULE_NO_PETS],
        ]);

        $noRulesSet = Listing::factory()->create([
            'house_rules' => null,
        ]);

        $ids = Listing::query()
            ->forDealbreakers($seeker)
            ->pluck('id')
            ->all();

        $this->assertContains($allowsPets->getKey(), $ids);
        $this->assertContains($noRulesSet->getKey(), $ids);
        $this->assertCount(2, $ids);
    }

    public function test_advanced_preferences_combines_budget_timeline_and_dealbreakers(): void
    {
        $seeker = User::factory()->create([
            'settings' => [
                'listing_preferences' => [
                    'budget_min' => 120000,
                    'budget_max' => 220000,
                    'move_in_date' => now()->addDays(21)->toDateString(),
                    'dealbreakers' => ['must_allow_pets'],
                ],
            ],
        ]);

        $validListing = Listing::factory()->create([
            'rent_amount' => 180000,
            'move_in_date' => now()->addDays(14)->toDateString(),
            'house_rules' => [Listing::HOUSE_RULE_NO_SMOKING],
        ]);

        Listing::factory()->create([
            'rent_amount' => 180000,
            'move_in_date' => now()->addDays(14)->toDateString(),
            'house_rules' => [Listing::HOUSE_RULE_NO_PETS],
        ]);

        Listing::factory()->create([
            'rent_amount' => 240000,
            'move_in_date' => now()->addDays(14)->toDateString(),
            'house_rules' => [Listing::HOUSE_RULE_NO_SMOKING],
        ]);

        $ids = Listing::query()
            ->forAdvancedPreferences($seeker)
            ->pluck('id')
            ->all();

        $this->assertSame([$validListing->getKey()], $ids);
    }
}
