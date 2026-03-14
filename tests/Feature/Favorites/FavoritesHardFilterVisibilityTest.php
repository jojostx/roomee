<?php

namespace Tests\Feature\Favorites;

use App\Enums\VerificationStatus;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoritesHardFilterVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_favorites_page_masks_users_that_fail_hard_matching_filters(): void
    {
        $school = School::query()->create([
            'name' => 'Test University',
            'short_name' => 'TU',
            'state' => 'Lagos',
        ]);

        $viewer = User::factory()->create([
            'gender' => 'male',
            'school_id' => $school->getKey(),
            'min_budget' => 80000,
            'max_budget' => 160000,
            'profile_updated' => true,
            'verification_status' => VerificationStatus::APPROVED,
            'email_verified_at' => now(),
            'settings' => [
                'matching' => [
                    'strict_gender_filter' => true,
                ],
            ],
        ]);

        $stillMatchingFavorite = User::factory()->create([
            'first_name' => 'Visible',
            'last_name' => 'Favorite',
            'gender' => 'male',
            'school_id' => $school->getKey(),
            'min_budget' => 100000,
            'max_budget' => 180000,
        ]);

        $failsGenderFavorite = User::factory()->create([
            'first_name' => 'Hidden',
            'last_name' => 'Gender',
            'gender' => 'female',
            'school_id' => $school->getKey(),
            'min_budget' => 100000,
            'max_budget' => 180000,
            'settings' => [
                'matching' => [
                    'strict_gender_filter' => false,
                ],
            ],
        ]);

        $failsBudgetFavorite = User::factory()->create([
            'first_name' => 'Hidden',
            'last_name' => 'Budget',
            'gender' => 'male',
            'school_id' => $school->getKey(),
            'min_budget' => 220000,
            'max_budget' => 280000,
        ]);

        $viewer->favorites()->attach([
            $stillMatchingFavorite->getKey(),
            $failsGenderFavorite->getKey(),
            $failsBudgetFavorite->getKey(),
        ]);

        $response = $this
            ->actingAs($viewer)
            ->get(route('favorites'));

        $response->assertOk();
        $response->assertSee($stillMatchingFavorite->full_name);
        $response->assertSee($failsGenderFavorite->full_name);
        $response->assertSee($failsBudgetFavorite->full_name);
        $response->assertSee('Profile hidden due to your updated hard matching filters.');
        $response->assertSee('Restricted');
    }
}
