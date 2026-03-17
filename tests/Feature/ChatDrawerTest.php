<?php

namespace Tests\Feature;

use App\Enums\ContactChannelType;
use App\Livewire\Components\Chat\ChatDrawer;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\ContactChannel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ChatDrawerTest extends TestCase
{
    use RefreshDatabase;

    // ----------------------------------------------------------------
    // Helpers
    // ----------------------------------------------------------------

    private function makeChatRoom(User $userA, User $userB): ChatRoom
    {
        return ChatRoom::factory()->create([
            'user_a_id' => min($userA->getKey(), $userB->getKey()),
            'user_b_id' => max($userA->getKey(), $userB->getKey()),
        ]);
    }

    // ----------------------------------------------------------------
    // Mount / initial state
    // ----------------------------------------------------------------

    public function test_mounts_with_no_active_room_by_default(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(ChatDrawer::class)
            ->assertSet('activeChatRoomId', null);
    }

    public function test_mounts_with_initial_room_auto_selected(): void
    {
        $user  = User::factory()->create();
        $other = User::factory()->create();
        $room  = $this->makeChatRoom($user, $other);

        Livewire::actingAs($user)
            ->test(ChatDrawer::class, ['initialRoomId' => $room->id])
            ->assertSet('activeChatRoomId', $room->id);
    }

    public function test_initial_room_is_rejected_if_user_is_not_participant(): void
    {
        $user      = User::factory()->create();
        $stranger1 = User::factory()->create();
        $stranger2 = User::factory()->create();
        $room      = $this->makeChatRoom($stranger1, $stranger2);

        Livewire::actingAs($user)
            ->test(ChatDrawer::class, ['initialRoomId' => $room->id])
            ->assertSet('activeChatRoomId', null);
    }

    // ----------------------------------------------------------------
    // selectRoom
    // ----------------------------------------------------------------

    public function test_select_room_sets_active_room(): void
    {
        $user  = User::factory()->create();
        $other = User::factory()->create();
        $room  = $this->makeChatRoom($user, $other);

        Livewire::actingAs($user)
            ->test(ChatDrawer::class)
            ->call('selectRoom', $room->id)
            ->assertSet('activeChatRoomId', $room->id);
    }

    public function test_select_room_rejects_rooms_user_does_not_belong_to(): void
    {
        $user      = User::factory()->create();
        $stranger1 = User::factory()->create();
        $stranger2 = User::factory()->create();
        $room      = $this->makeChatRoom($stranger1, $stranger2);

        Livewire::actingAs($user)
            ->test(ChatDrawer::class)
            ->call('selectRoom', $room->id)
            ->assertSet('activeChatRoomId', null);
    }

    public function test_select_room_marks_incoming_messages_as_read(): void
    {
        $user  = User::factory()->create();
        $other = User::factory()->create();
        $room  = $this->makeChatRoom($user, $other);

        ChatMessage::factory()->create([
            'chat_room_id' => $room->id,
            'sender_id'    => $other->id,
            'read_at'      => null,
        ]);

        Livewire::actingAs($user)
            ->test(ChatDrawer::class)
            ->call('selectRoom', $room->id);

        $this->assertDatabaseMissing('chat_messages', [
            'chat_room_id' => $room->id,
            'sender_id'    => $other->id,
            'read_at'      => null,
        ]);
    }

    // ----------------------------------------------------------------
    // sendMessage
    // ----------------------------------------------------------------

    public function test_send_message_creates_message_in_active_room(): void
    {
        $user  = User::factory()->create();
        $other = User::factory()->create();
        $room  = $this->makeChatRoom($user, $other);

        Livewire::actingAs($user)
            ->test(ChatDrawer::class)
            ->call('selectRoom', $room->id)
            ->set('newMessage', 'Hello there!')
            ->call('sendMessage');

        $this->assertDatabaseHas('chat_messages', [
            'chat_room_id' => $room->id,
            'sender_id'    => $user->id,
            'message'      => 'Hello there!',
        ]);
    }

    public function test_send_message_clears_input_after_send(): void
    {
        $user  = User::factory()->create();
        $other = User::factory()->create();
        $room  = $this->makeChatRoom($user, $other);

        Livewire::actingAs($user)
            ->test(ChatDrawer::class)
            ->call('selectRoom', $room->id)
            ->set('newMessage', 'Hello!')
            ->call('sendMessage')
            ->assertSet('newMessage', '');
    }

    public function test_send_message_fails_validation_when_empty(): void
    {
        $user  = User::factory()->create();
        $other = User::factory()->create();
        $room  = $this->makeChatRoom($user, $other);

        Livewire::actingAs($user)
            ->test(ChatDrawer::class)
            ->call('selectRoom', $room->id)
            ->set('newMessage', '')
            ->call('sendMessage')
            ->assertHasErrors(['newMessage' => 'required']);
    }

    public function test_send_message_fails_validation_when_too_long(): void
    {
        $user  = User::factory()->create();
        $other = User::factory()->create();
        $room  = $this->makeChatRoom($user, $other);

        Livewire::actingAs($user)
            ->test(ChatDrawer::class)
            ->call('selectRoom', $room->id)
            ->set('newMessage', str_repeat('a', 1001))
            ->call('sendMessage')
            ->assertHasErrors(['newMessage' => 'max']);
    }

    public function test_send_message_does_nothing_without_active_room(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(ChatDrawer::class)
            ->set('newMessage', 'Should not save')
            ->call('sendMessage');

        $this->assertDatabaseCount('chat_messages', 0);
    }

    // ----------------------------------------------------------------
    // loadMoreRooms
    // ----------------------------------------------------------------

    public function test_load_more_rooms_increases_limit(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(ChatDrawer::class)
            ->assertSet('roomsLimit', 15)
            ->call('loadMoreRooms')
            ->assertSet('roomsLimit', 30);
    }

    // ----------------------------------------------------------------
    // Contact channel gate
    // ----------------------------------------------------------------

    public function test_current_user_has_contact_channels_is_false_without_verified_channels(): void
    {
        $user  = User::factory()->create();
        $other = User::factory()->create();
        $room  = $this->makeChatRoom($user, $other);

        Livewire::actingAs($user)
            ->test(ChatDrawer::class)
            ->call('selectRoom', $room->id)
            ->assertSet('currentUserHasContactChannels', false);
    }

    public function test_current_user_has_contact_channels_is_true_with_verified_channel(): void
    {
        $user  = User::factory()->create();
        $other = User::factory()->create();
        $room  = $this->makeChatRoom($user, $other);

        ContactChannel::create([
            'user_id'     => $user->id,
            'type'        => ContactChannelType::WHATSAPP->value,
            'link'        => '+447700900000',
            'is_enabled'  => true,
            'verified_at' => now(),
        ]);

        Livewire::actingAs($user)
            ->test(ChatDrawer::class)
            ->call('selectRoom', $room->id)
            ->assertSet('currentUserHasContactChannels', true);
    }

    public function test_unverified_contact_channel_does_not_count(): void
    {
        $user  = User::factory()->create();
        $other = User::factory()->create();
        $room  = $this->makeChatRoom($user, $other);

        ContactChannel::create([
            'user_id'     => $user->id,
            'type'        => ContactChannelType::WHATSAPP->value,
            'link'        => '+447700900000',
            'is_enabled'  => true,
            'verified_at' => null,
        ]);

        Livewire::actingAs($user)
            ->test(ChatDrawer::class)
            ->call('selectRoom', $room->id)
            ->assertSet('currentUserHasContactChannels', false);
    }

    public function test_disabled_contact_channel_does_not_count(): void
    {
        $user  = User::factory()->create();
        $other = User::factory()->create();
        $room  = $this->makeChatRoom($user, $other);

        ContactChannel::create([
            'user_id'     => $user->id,
            'type'        => ContactChannelType::WHATSAPP->value,
            'link'        => '+447700900000',
            'is_enabled'  => false,
            'verified_at' => now(),
        ]);

        Livewire::actingAs($user)
            ->test(ChatDrawer::class)
            ->call('selectRoom', $room->id)
            ->assertSet('currentUserHasContactChannels', false);
    }

    // ----------------------------------------------------------------
    // Contact sharing
    // ----------------------------------------------------------------

    public function test_share_contacts_marks_user_as_shared(): void
    {
        $user  = User::factory()->create();
        $other = User::factory()->create();
        $room  = $this->makeChatRoom($user, $other);

        $user->sendRoommateRequest($other);
        $other->acceptRoommateRequest($user);

        Livewire::actingAs($user)
            ->test(ChatDrawer::class)
            ->call('selectRoom', $room->id)
            ->callAction('shareContacts');

        $column = $room->user_a_id === $user->id ? 'contact_shared_by_a' : 'contact_shared_by_b';
        $this->assertDatabaseHas('chat_rooms', ['id' => $room->id, $column => true]);
    }

    public function test_unshare_contacts_clears_user_share(): void
    {
        $user  = User::factory()->create();
        $other = User::factory()->create();
        $room  = $this->makeChatRoom($user, $other);

        $user->sendRoommateRequest($other);
        $other->acceptRoommateRequest($user);
        $room->markContactSharedBy($user);

        Livewire::actingAs($user)
            ->test(ChatDrawer::class)
            ->call('selectRoom', $room->id)
            ->callAction('unshareContacts');

        $column = $room->user_a_id === $user->id ? 'contact_shared_by_a' : 'contact_shared_by_b';
        $this->assertDatabaseHas('chat_rooms', ['id' => $room->id, $column => false]);
    }

    public function test_share_contacts_does_nothing_after_users_are_unmatched(): void
    {
        $user  = User::factory()->create();
        $other = User::factory()->create();
        $room  = $this->makeChatRoom($user, $other);

        $user->sendRoommateRequest($other);
        $other->acceptRoommateRequest($user);
        $user->unmatchRoommate($other);

        Livewire::actingAs($user)
            ->test(ChatDrawer::class)
            ->call('selectRoom', $room->id)
            ->callAction('shareContacts');

        $column = $room->user_a_id === $user->id ? 'contact_shared_by_a' : 'contact_shared_by_b';
        $this->assertDatabaseHas('chat_rooms', ['id' => $room->id, $column => false]);
    }

    // ----------------------------------------------------------------
    // Layout integration — chat button and drawer HTML structure
    // ----------------------------------------------------------------

    public function test_authenticated_page_body_has_x_data_for_alpine_processing(): void
    {
        $user = User::factory()->create();

        $this->withoutMiddleware()
            ->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('x-data=""', false);
    }

    public function test_chat_button_renders_with_store_open_modal_handler(): void
    {
        $user = User::factory()->create();

        $this->withoutMiddleware()
            ->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('$store.chat.openModal()', false);
    }

    public function test_chat_drawer_overlay_renders_with_store_open_x_show(): void
    {
        $user = User::factory()->create();

        $this->withoutMiddleware()
            ->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('$store.chat.open', false);
    }

    // ----------------------------------------------------------------
    // Direct URL navigation — authorization check in ChatRoomPage
    // ----------------------------------------------------------------

    public function test_chat_room_page_returns_403_for_non_participant(): void
    {
        $user      = User::factory()->create();
        $stranger1 = User::factory()->create();
        $stranger2 = User::factory()->create();
        $room      = $this->makeChatRoom($stranger1, $stranger2);

        $this->withoutMiddleware()
            ->actingAs($user)
            ->get(route('chat.room', $room))
            ->assertForbidden();
    }

    public function test_chat_room_page_returns_200_for_participant(): void
    {
        $user  = User::factory()->create();
        $other = User::factory()->create();
        $room  = $this->makeChatRoom($user, $other);

        $this->withoutMiddleware()
            ->actingAs($user)
            ->get(route('chat.room', $room))
            ->assertOk();
    }
}
