<?php

namespace Tests\Feature\Chat;

use App\Livewire\Pages\DashboardPage;
use App\Livewire\Pages\Profile\ViewProfilePage;
use App\Livewire\Pages\RoommateRequestsPage;
use App\Models\ChatRoom;
use App\Models\RoommateRequest;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ChatEntryPointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_chat_action_dispatches_chat_room_event(): void
    {
        [$viewer, $match, $room] = $this->createMatchedUsers();

        Livewire::actingAs($viewer)
            ->test(DashboardPage::class)
            ->callTableAction('chat-user', $match)
            ->assertDispatched('open-chat-room', roomId: $room->id);
    }

    public function test_roommate_requests_chat_action_dispatches_chat_room_event(): void
    {
        [$viewer, $match, $room] = $this->createMatchedUsers();

        Livewire::actingAs($viewer)
            ->test(RoommateRequestsPage::class)
            ->callTableAction('chat-user', $match)
            ->assertDispatched('open-chat-room', roomId: $room->id);
    }

    public function test_profile_page_open_chat_accepts_uuid_and_dispatches_chat_room_event(): void
    {
        [$viewer, $match, $room] = $this->createMatchedUsers();

        Livewire::actingAs($viewer)
            ->test(ViewProfilePage::class, ['user' => $match])
            ->call('openChat', $match->uuid)
            ->assertDispatched('open-chat-room', roomId: $room->id);
    }

    public function test_roommate_requests_unmatch_action_deletes_match_and_resets_contact_sharing(): void
    {
        [$viewer, $match, $room] = $this->createMatchedUsers();

        $room->markContactSharedBy($viewer);
        $room->markContactSharedBy($match);

        Livewire::actingAs($viewer)
            ->test(RoommateRequestsPage::class)
            ->callTableAction('unmatch-roommate', $match);

        $this->assertDatabaseMissing('roommate_requests', [
            'id' => RoommateRequest::getCompositeKey($viewer, $match),
        ]);

        $room->refresh();

        $this->assertFalse($viewer->fresh()->isRoommateWith($match));
        $this->assertFalse($room->contact_shared_by_a);
        $this->assertFalse($room->contact_shared_by_b);
    }

    public function test_chat_routes_render_fallback_drawer_copy(): void
    {
        [$viewer, $match, $room] = $this->createMatchedUsers();

        $this->withoutMiddleware()
            ->actingAs($viewer)
            ->get(route('chat.index'))
            ->assertOk()
            ->assertSee('Chat lives in the drawer')
            ->assertSee('Open Chat');

        $this->withoutMiddleware()
            ->actingAs($viewer)
            ->get(route('chat.room', $room))
            ->assertOk()
            ->assertSee('Open Chat')
            ->assertSee('Chat with ' . $match->full_name);
    }

    /**
     * @return array{0: User, 1: User, 2: ChatRoom}
     */
    private function createMatchedUsers(): array
    {
        $school = School::query()->create([
            'name' => 'Chat Test University',
            'short_name' => 'CTU',
            'state' => 'Lagos',
        ]);

        $viewer = User::factory()->create([
            'first_name' => 'Viewer',
            'last_name' => 'User',
            'gender' => 'male',
            'school_id' => $school->getKey(),
            'min_budget' => 80000,
            'max_budget' => 150000,
            'profile_updated' => true,
        ]);

        $match = User::factory()->create([
            'first_name' => 'Matched',
            'last_name' => 'User',
            'gender' => 'male',
            'school_id' => $school->getKey(),
            'min_budget' => 90000,
            'max_budget' => 160000,
            'profile_updated' => true,
        ]);

        $viewer->sendRoommateRequest($match);
        $match->acceptRoommateRequest($viewer);

        return [
            $viewer,
            $match,
            ChatRoom::findBetween($viewer, $match),
        ];
    }
}
