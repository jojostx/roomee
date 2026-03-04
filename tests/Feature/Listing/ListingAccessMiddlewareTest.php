<?php

namespace Tests\Feature\Listing;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListingAccessMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<int, string>
     */
    protected function listingRoutes(): array
    {
        return ['listings', 'listings.discover'];
    }

    public function test_eligible_user_can_open_listings_page(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::USER,
            'email_verified_at' => now(),
            'profile_updated' => true,
            'verification_status' => User::VERIFICATION_STATUS_APPROVED,
        ]);

        foreach ($this->listingRoutes() as $routeName) {
            $response = $this->actingAs($user)->get(route($routeName));
            $response->assertOk();
        }
    }

    public function test_pending_verification_user_is_redirected_to_pending_page(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::USER,
            'email_verified_at' => now(),
            'profile_updated' => true,
            'verification_status' => User::VERIFICATION_STATUS_PENDING,
        ]);

        foreach ($this->listingRoutes() as $routeName) {
            $response = $this->actingAs($user)->get(route($routeName));
            $response->assertRedirect(route('verification.pending'));
        }
    }

    public function test_incomplete_profile_user_is_redirected_to_profile_update_page(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::USER,
            'email_verified_at' => now(),
            'profile_updated' => false,
            'verification_status' => User::VERIFICATION_STATUS_APPROVED,
        ]);

        foreach ($this->listingRoutes() as $routeName) {
            $response = $this->actingAs($user)->get(route($routeName));
            $response->assertRedirect(route('profile.update'));
        }
    }

    public function test_admin_cannot_access_user_listings_page(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'email_verified_at' => now(),
            'profile_updated' => true,
        ]);

        foreach ($this->listingRoutes() as $routeName) {
            $response = $this->actingAs($admin)->get(route($routeName));
            $response->assertForbidden();
        }
    }
}
