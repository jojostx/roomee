<?php

namespace Tests\Feature\Filament;

use App\Enums\UserRole;
use App\Models\User;
use App\Services\Settings\VerificationTimelineSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerificationSettingsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_verification_settings_page(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'email_verified_at' => now(),
        ]);

        $response = $this
            ->actingAs($admin)
            ->get(route('filament.admin.pages.verification-settings'));

        $response->assertOk();
        $response->assertSee('Verification Settings');
    }

    public function test_non_admin_cannot_access_verification_settings_page(): void
    {
        $member = User::factory()->create([
            'role' => UserRole::USER,
            'email_verified_at' => now(),
        ]);

        $response = $this
            ->actingAs($member)
            ->get(route('filament.admin.pages.verification-settings'));

        $response->assertForbidden();
    }

    public function test_verification_timeline_settings_service_persists_values(): void
    {
        VerificationTimelineSettings::set(30, 96);

        $this->assertSame(30, VerificationTimelineSettings::getMinHours());
        $this->assertSame(96, VerificationTimelineSettings::getMaxHours());
        $this->assertSame('30-96 hours', VerificationTimelineSettings::getDisplayText());
    }
}
