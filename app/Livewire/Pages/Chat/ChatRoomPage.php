<?php

namespace App\Livewire\Pages\Chat;

use App\Events\MessageSent;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ChatRoomPage extends Component
{
    public ChatRoom $chatRoom;

    public string $newMessage = '';

    public function mount(ChatRoom $chatRoom): void
    {
        $authUser = $this->getAuthModel();

        // Ensure the authenticated user is a participant in this room.
        abort_unless(
            (int) $chatRoom->user_a_id === $authUser->getKey()
                || (int) $chatRoom->user_b_id === $authUser->getKey(),
            403
        );

        $this->chatRoom = $chatRoom;

        // Mark all messages from the other participant as read.
        $this->markMessagesAsRead();
    }

    protected function getListeners(): array
    {
        return [
            "echo-private:chat-room.{$this->chatRoom->id},MessageSent" => 'handleIncomingMessage',
        ];
    }

    public function handleIncomingMessage(): void
    {
        $this->chatRoom->refresh();
        $this->markMessagesAsRead();
        unset($this->chatMessages);
    }

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    /** @return Collection<int, ChatMessage> */
    #[Computed]
    public function chatMessages(): Collection
    {
        return $this->chatRoom
            ->messages()
            ->with('sender')
            ->oldest()
            ->get();
    }

    #[Computed]
    public function otherUser(): ?User
    {
        return $this->chatRoom->otherParticipant($this->getAuthModel());
    }

    #[Computed]
    public function hasBothSharedContacts(): bool
    {
        return $this->chatRoom->hasBothSharedContacts();
    }

    #[Computed]
    public function hasCurrentUserSharedContacts(): bool
    {
        return $this->chatRoom->hasUserSharedContacts($this->getAuthModel());
    }

    public function sendMessage(): void
    {
        $this->validate(['newMessage' => ['required', 'string', 'max:1000']]);

        $message = $this->chatRoom->messages()->create([
            'sender_id' => $this->getAuthModel()->getKey(),
            'message' => $this->newMessage,
        ]);

        MessageSent::dispatch($message);

        $this->newMessage = '';

        // Bump the room's updated_at so the index sorts it to the top.
        $this->chatRoom->touch();
        unset($this->chatMessages);
    }

    public function shareContacts(): void
    {
        $authUser = $this->getAuthModel();

        if ($this->chatRoom->hasUserSharedContacts($authUser)) {
            return;
        }

        $this->chatRoom->markContactSharedBy($authUser);
        $this->chatRoom->refresh();
        unset($this->hasBothSharedContacts, $this->hasCurrentUserSharedContacts);

        Notification::make()
            ->title('Contact sharing request sent')
            ->body('Waiting for ' . $this->otherUser?->first_name . ' to also share their contacts.')
            ->info()
            ->send();
    }

    public function openContactModal(): void
    {
        if (!$this->chatRoom->hasBothSharedContacts()) {
            return;
        }

        $this->dispatch('openModal', 'components.modals.contact-user-modal', [
            'user' => $this->otherUser?->uuid,
        ]);
    }

    protected function markMessagesAsRead(): void
    {
        $this->chatRoom
            ->messages()
            ->where('sender_id', '!=', $this->getAuthModel()->getKey())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function render()
    {
        return view('livewire.pages.chat.chat-room-page')
            ->layout('layouts.guest');
    }
}
