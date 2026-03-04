<?php

namespace Tests\Feature\Smoke;

use App\Enums\ContactChannelType;
use App\Enums\RoommateRequestStatus;
use App\Enums\UserRole;
use App\Models\Blocklist;
use App\Models\ContactChannel;
use App\Models\Favorite;
use App\Models\Listing;
use App\Models\RoommateRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PanelLivewireRouteSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $member;
    protected Blocklist $blocklist;
    protected Favorite $favorite;
    protected ContactChannel $contactChannel;
    protected RoommateRequest $roommateRequest;
    protected Listing $listing;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'profile_updated' => true,
            'email_verified_at' => now(),
        ]);

        $this->member = User::factory()->create([
            'role' => UserRole::USER,
            'profile_updated' => true,
            'email_verified_at' => now(),
        ]);

        $this->blocklist = Blocklist::create([
            'blocker_id' => $this->admin->getKey(),
            'blockee_id' => $this->member->getKey(),
        ]);

        $this->favorite = Favorite::create([
            'favoriter_id' => $this->admin->getKey(),
            'favoritee_id' => $this->member->getKey(),
        ]);

        $this->contactChannel = ContactChannel::create([
            'user_id' => $this->member->getKey(),
            'type' => ContactChannelType::EMAIL->value,
            'link' => $this->member->email,
            'is_enabled' => true,
            'verified_at' => now(),
        ]);

        $this->roommateRequest = RoommateRequest::create([
            'id' => RoommateRequest::getCompositeKey($this->admin, $this->member),
            'sender_id' => $this->admin->getKey(),
            'recipient_id' => $this->member->getKey(),
            'status' => RoommateRequestStatus::PENDING->value,
        ]);

        $this->listing = Listing::factory()->create([
            'user_id' => $this->member->getKey(),
        ]);
    }

    public function test_filament_admin_routes_return_success_for_admin(): void
    {
        $routes = [
            'filament.admin.resources.blocklists.index' => [],
            'filament.admin.resources.blocklists.create' => [],
            'filament.admin.resources.blocklists.edit' => ['record' => $this->blocklist],

            'filament.admin.resources.contact-channels.index' => [],
            'filament.admin.resources.contact-channels.create' => [],
            'filament.admin.resources.contact-channels.edit' => ['record' => $this->contactChannel],

            'filament.admin.resources.favorites.index' => [],
            'filament.admin.resources.favorites.create' => [],
            'filament.admin.resources.favorites.edit' => ['record' => $this->favorite],

            'filament.admin.resources.roommate-requests.index' => [],
            'filament.admin.resources.roommate-requests.create' => [],
            'filament.admin.resources.roommate-requests.edit' => ['record' => $this->roommateRequest],

            'filament.admin.resources.verification-requests.index' => [],

            'filament.admin.resources.listings.index' => [],
            'filament.admin.resources.listings.create' => [],
            'filament.admin.resources.listings.edit' => ['record' => $this->listing],

            'filament.admin.resources.users.index' => [],
            'filament.admin.resources.users.create' => [],
            'filament.admin.resources.users.edit' => ['record' => $this->member],

            'filament.admin.pages.message-users' => [],
            'filament.admin.pages.verification-settings' => [],
        ];

        foreach ($routes as $routeName => $params) {
            $response = $this
                ->actingAs($this->admin)
                ->get(route($routeName, $params));

            $this->assertSame(
                200,
                $response->getStatusCode(),
                "Filament route [{$routeName}] did not return HTTP 200."
            );
        }
    }

    public function test_livewire_page_routes_return_success_for_verified_user(): void
    {
        $routes = [
            'dashboard' => [],
            'favorites' => [],
            'roommate-requests' => [],
            'blocklist' => [],
            'verification.pending' => [],
            'profile.update' => [],
            'profile.view' => ['user' => $this->member],
            'settings.account' => [],
            'settings.contact-channels' => [],
            'settings.notifications' => [],
        ];

        foreach ($routes as $routeName => $params) {
            $response = $this
                ->actingAs($this->admin)
                ->get(route($routeName, $params));

            $this->assertSame(
                200,
                $response->getStatusCode(),
                "Livewire route [{$routeName}] did not return HTTP 200."
            );
        }
    }
}
