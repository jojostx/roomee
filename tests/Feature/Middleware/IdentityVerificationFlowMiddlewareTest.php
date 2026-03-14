<?php

namespace Tests\Feature\Middleware;

use App\Enums\VerificationStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IdentityVerificationFlowMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_unverified_user_is_redirected_from_settings_pages_to_profile_update(): void
    {
        $user = User::factory()->create([
            'profile_updated' => false,
            'verification_status' => VerificationStatus::UNVERIFIED,
        ]);

        $this->actingAs($user);

        $this->get(route('settings.contact-channels'))
            ->assertRedirect(route('profile.update'));

        $this->get(route('settings.notifications'))
            ->assertRedirect(route('profile.update'));
    }

    public function test_pending_verification_user_is_redirected_to_pending_page_from_other_pages(): void
    {
        $user = User::factory()->create([
            'profile_updated' => true,
            'verification_status' => VerificationStatus::PENDING,
        ]);

        $this->actingAs($user);

        $this->get(route('settings.contact-channels'))
            ->assertRedirect(route('verification.pending'));

        $this->get(route('settings.notifications'))
            ->assertRedirect(route('verification.pending'));

        $this->get(route('dashboard'))
            ->assertRedirect(route('verification.pending'));
    }

    public function test_pending_verification_user_can_access_pending_page(): void
    {
        $user = User::factory()->create([
            'profile_updated' => true,
            'verification_status' => VerificationStatus::PENDING,
        ]);

        $this->actingAs($user);

        $this->get(route('verification.pending'))
            ->assertOk();
    }
}

