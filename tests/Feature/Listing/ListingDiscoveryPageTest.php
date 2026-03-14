<?php

namespace Tests\Feature\Listing;

use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Models\Listing;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListingDiscoveryPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_discovery_page_renders_only_listings_matching_advanced_preferences(): void
    {
        $school = School::query()->create([
            'name' => 'Discovery University',
            'short_name' => 'DU',
            'state' => 'Lagos',
        ]);

        $seeker = User::factory()->create([
            'role' => UserRole::USER,
            'email_verified_at' => now(),
            'profile_updated' => true,
            'verification_status' => VerificationStatus::APPROVED,
            'school_id' => $school->getKey(),
            'settings' => [
                'listing_preferences' => [
                    'budget_min' => 120000,
                    'budget_max' => 220000,
                    'move_in_date' => now()->addDays(21)->toDateString(),
                    'dealbreakers' => ['must_allow_pets'],
                ],
            ],
        ]);

        $owner = User::factory()->create([
            'role' => UserRole::USER,
            'email_verified_at' => now(),
            'profile_updated' => true,
            'verification_status' => VerificationStatus::APPROVED,
            'school_id' => $school->getKey(),
        ]);

        $matchingListing = Listing::factory()->create([
            'user_id' => $owner->getKey(),
            'title' => 'Matching Listing Alpha',
            'rent_amount' => 180000,
            'move_in_date' => now()->addDays(14)->toDateString(),
            'house_rules' => [Listing::HOUSE_RULE_NO_SMOKING],
            'is_published' => true,
        ]);

        Listing::factory()->create([
            'user_id' => $owner->getKey(),
            'title' => 'Non Matching No Pets',
            'rent_amount' => 180000,
            'move_in_date' => now()->addDays(14)->toDateString(),
            'house_rules' => [Listing::HOUSE_RULE_NO_PETS],
            'is_published' => true,
        ]);

        Listing::factory()->create([
            'user_id' => $owner->getKey(),
            'title' => 'Non Matching Budget',
            'rent_amount' => 400000,
            'move_in_date' => now()->addDays(14)->toDateString(),
            'house_rules' => [Listing::HOUSE_RULE_NO_SMOKING],
            'is_published' => true,
        ]);

        Listing::factory()->create([
            'user_id' => $owner->getKey(),
            'title' => 'Non Matching Timeline',
            'rent_amount' => 180000,
            'move_in_date' => now()->addDays(40)->toDateString(),
            'house_rules' => [Listing::HOUSE_RULE_NO_SMOKING],
            'is_published' => true,
        ]);

        Listing::factory()->create([
            'user_id' => $owner->getKey(),
            'title' => 'Unpublished Listing',
            'rent_amount' => 180000,
            'move_in_date' => now()->addDays(14)->toDateString(),
            'house_rules' => [Listing::HOUSE_RULE_NO_SMOKING],
            'is_published' => false,
        ]);

        $response = $this->actingAs($seeker)->get(route('listings.discover'));

        $response->assertOk();
        $response->assertSeeText($matchingListing->title);
        $response->assertDontSeeText('Non Matching No Pets');
        $response->assertDontSeeText('Non Matching Budget');
        $response->assertDontSeeText('Non Matching Timeline');
        $response->assertDontSeeText('Unpublished Listing');
    }
}
