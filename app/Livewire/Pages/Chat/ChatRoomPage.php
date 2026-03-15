<?php

namespace App\Livewire\Pages\Chat;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatRoomPage extends Component
{
    public ChatRoom $chatRoom;
    public string $newMessage = '';

    public function mount(ChatRoom $chatRoom): void
    {
        $authUser = $this->getAuthModel();

        abort_unless(
            (int) $chatRoom->user_a_id === $authUser->getKey()
                || (int) $chatRoom->user_b_id === $authUser->getKey(),
            403
        );

        $this->chatRoom = $chatRoom;
    }

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    public function sendMessage(): void
    {
        $this->validate(['newMessage' => ['required', 'string']]);

        ChatMessage::create([
            'chat_room_id' => $this->chatRoom->getKey(),
            'sender_id'    => $this->getAuthModel()->getKey(),
            'message'      => $this->newMessage,
        ]);

        $this->newMessage = '';
    }

    public function shareContacts(): void
    {
        $this->chatRoom->markContactSharedBy($this->getAuthModel());
    }

    public function openContactModal(): void
    {
        $this->chatRoom->refresh();

        if (! $this->chatRoom->hasBothSharedContacts()) {
            return;
        }

        $otherUser = $this->chatRoom->otherParticipant($this->getAuthModel());

        $this->dispatch('openModal', 'components.modals.contact-user-modal', ['user' => $otherUser->uuid]);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.pages.chat.chat-room-page')
            ->layout('layouts.guest');
    }
}
