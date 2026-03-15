<?php

namespace Tests\Feature\Chat;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\User;
use App\Livewire\Pages\Chat\ChatRoomPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ChatRoomCreationTest extends TestCase
{
    use RefreshDatabase;

    // ------------------------------------------------------------------ //
    // ChatRoom creation on request acceptance
    // ------------------------------------------------------------------ //

    public function test_chat_room_is_created_when_roommate_request_is_accepted(): void
    {
        $sender    = User::factory()->create(['gender' => 'male']);
        $recipient = User::factory()->create(['gender' => 'male']);

        $sender->sendRoommateRequest($recipient);
        $recipient->acceptRoommateRequest($sender);

        $this->assertDatabaseHas('chat_rooms', [
            'user_a_id' => min($sender->id, $recipient->id),
            'user_b_id' => max($sender->id, $recipient->id),
        ]);
    }

    public function test_only_one_chat_room_is_created_for_a_pair(): void
    {
        $sender    = User::factory()->create(['gender' => 'male']);
        $recipient = User::factory()->create(['gender' => 'male']);

        $sender->sendRoommateRequest($recipient);
        $recipient->acceptRoommateRequest($sender);

        // Accepting a second time should not create a duplicate room.
        $recipient->acceptRoommateRequest($sender);

        $this->assertDatabaseCount('chat_rooms', 1);
    }

    // ------------------------------------------------------------------ //
    // Contact sharing gate
    // ------------------------------------------------------------------ //

    public function test_contacts_are_hidden_when_neither_user_has_shared(): void
    {
        [$userA, $userB, $room] = $this->createAcceptedRoomWithChatRoom();

        $this->assertFalse($room->hasBothSharedContacts());
    }

    public function test_contacts_are_hidden_when_only_one_user_has_shared(): void
    {
        [$userA, $userB, $room] = $this->createAcceptedRoomWithChatRoom();

        $room->markContactSharedBy($userA);
        $room->refresh();

        $this->assertFalse($room->hasBothSharedContacts());
    }

    public function test_contacts_are_unlocked_when_both_users_have_shared(): void
    {
        [$userA, $userB, $room] = $this->createAcceptedRoomWithChatRoom();

        $room->markContactSharedBy($userA);
        $room->markContactSharedBy($userB);
        $room->refresh();

        $this->assertTrue($room->hasBothSharedContacts());
    }

    // ------------------------------------------------------------------ //
    // ChatRoomPage access control
    // ------------------------------------------------------------------ //

    public function test_non_participant_cannot_access_chat_room(): void
    {
        [$userA, $userB, $room] = $this->createAcceptedRoomWithChatRoom();

        $stranger = User::factory()->create();

        $this->withoutMiddleware()
            ->actingAs($stranger)
            ->get(route('chat.room', $room))
            ->assertForbidden();
    }

    public function test_participant_can_access_chat_room(): void
    {
        [$userA, $userB, $room] = $this->createAcceptedRoomWithChatRoom();

        $this->withoutMiddleware()
            ->actingAs($userA)
            ->get(route('chat.room', $room))
            ->assertOk();
    }

    // ------------------------------------------------------------------ //
    // Sending messages via Livewire
    // ------------------------------------------------------------------ //

    public function test_participant_can_send_a_message(): void
    {
        [$userA, $userB, $room] = $this->createAcceptedRoomWithChatRoom();

        Livewire::actingAs($userA)
            ->test(ChatRoomPage::class, ['chatRoom' => $room])
            ->set('newMessage', 'Hello there!')
            ->call('sendMessage')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('chat_messages', [
            'chat_room_id' => $room->id,
            'sender_id'    => $userA->id,
            'message'      => 'Hello there!',
        ]);
    }

    public function test_empty_message_fails_validation(): void
    {
        [$userA, $userB, $room] = $this->createAcceptedRoomWithChatRoom();

        Livewire::actingAs($userA)
            ->test(ChatRoomPage::class, ['chatRoom' => $room])
            ->set('newMessage', '')
            ->call('sendMessage')
            ->assertHasErrors(['newMessage' => 'required']);
    }

    // ------------------------------------------------------------------ //
    // Share contacts via Livewire
    // ------------------------------------------------------------------ //

    public function test_user_can_share_contacts_via_livewire(): void
    {
        [$userA, $userB, $room] = $this->createAcceptedRoomWithChatRoom();

        Livewire::actingAs($userA)
            ->test(ChatRoomPage::class, ['chatRoom' => $room])
            ->call('shareContacts');

        $room->refresh();
        $this->assertTrue($room->hasUserSharedContacts($userA));
        $this->assertFalse($room->hasUserSharedContacts($userB));
    }

    public function test_contact_modal_not_openable_until_both_share(): void
    {
        [$userA, $userB, $room] = $this->createAcceptedRoomWithChatRoom();

        $component = Livewire::actingAs($userA)
            ->test(ChatRoomPage::class, ['chatRoom' => $room]);

        // openContactModal should silently do nothing because consent is not mutual.
        $component->call('openContactModal')
            ->assertNotDispatched('openModal');
    }

    public function test_contact_modal_is_openable_after_both_share(): void
    {
        [$userA, $userB, $room] = $this->createAcceptedRoomWithChatRoom();

        $room->update(['contact_shared_by_a' => true, 'contact_shared_by_b' => true]);

        Livewire::actingAs($userA)
            ->test(ChatRoomPage::class, ['chatRoom' => $room])
            ->call('openContactModal')
            ->assertDispatched('openModal');
    }

    // ------------------------------------------------------------------ //
    // Helpers
    // ------------------------------------------------------------------ //

    /**
     * Creates two users with an accepted roommate request and a chat room.
     *
     * @return array{0: User, 1: User, 2: ChatRoom}
     */
    private function createAcceptedRoomWithChatRoom(): array
    {
        $userA = User::factory()->create(['gender' => 'male']);
        $userB = User::factory()->create(['gender' => 'male']);

        $userA->sendRoommateRequest($userB);
        $userB->acceptRoommateRequest($userA);

        $room = ChatRoom::findBetween($userA, $userB);

        return [$userA, $userB, $room];
    }
}
