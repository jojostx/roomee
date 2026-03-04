<?php

namespace Tests\Feature\Filament;

use App\Enums\UserRole;
use App\Filament\Resources\ListingResource;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ListingResourceRulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_premium_user_with_active_listing_cannot_create_another_listing(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::USER,
            'is_premium' => false,
            'email_verified_at' => now(),
            'profile_updated' => true,
            'verification_status' => User::VERIFICATION_STATUS_APPROVED,
        ]);

        Listing::factory()->create([
            'user_id' => $user->getKey(),
            'is_published' => true,
        ]);

        $this->expectException(ValidationException::class);

        ListingResource::ensureOwnerCanCreateListing($user);
    }

    public function test_unverified_user_cannot_publish_listing(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::USER,
            'email_verified_at' => now(),
            'profile_updated' => true,
            'verification_status' => User::VERIFICATION_STATUS_UNVERIFIED,
        ]);

        $this->expectException(ValidationException::class);

        ListingResource::ensureOwnerCanPublishListing($user, true, null);
    }

    public function test_verified_premium_user_can_publish_multiple_listings(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::USER,
            'is_premium' => true,
            'email_verified_at' => now(),
            'profile_updated' => true,
            'verification_status' => User::VERIFICATION_STATUS_APPROVED,
        ]);

        Listing::factory()->create([
            'user_id' => $user->getKey(),
            'is_published' => true,
        ]);

        $this->assertTrue(ListingResource::canPublishForOwner($user->getKey()));
        ListingResource::ensureOwnerCanPublishListing($user, true, null);
        $this->assertTrue(true);
    }

    public function test_user_without_verified_email_cannot_manage_listing(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::USER,
            'profile_updated' => true,
            'verification_status' => User::VERIFICATION_STATUS_APPROVED,
            'email_verified_at' => null,
        ]);

        $this->expectException(ValidationException::class);

        ListingResource::ensureOwnerCanDraftListing($user);
    }

    public function test_user_with_incomplete_profile_cannot_manage_listing(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::USER,
            'profile_updated' => false,
            'verification_status' => User::VERIFICATION_STATUS_APPROVED,
            'email_verified_at' => now(),
        ]);

        $this->expectException(ValidationException::class);

        ListingResource::ensureOwnerCanDraftListing($user);
    }
}
