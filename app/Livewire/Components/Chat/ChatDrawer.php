<?php

namespace App\Livewire\Components\Chat;

use App\Events\MessageSent;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\User;
use App\Livewire\Traits\WithUserActionModals;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ChatDrawer extends Component implements HasForms, HasActions
{
    use InteractsWithForms, InteractsWithActions, WithUserActionModals;

    public ?string $activeChatRoomId = null;

    public string $newMessage = '';

    public int $roomsLimit = 15;

    public function mount(?string $initialRoomId = null): void
    {
        if ($initialRoomId) {
            $this->selectRoom($initialRoomId);
        }
    }

    public function placeholder(): string
    {
        return '<div class="w-full h-full flex items-center justify-center bg-white"><div class="w-8 h-8 border-2 border-primary-600 border-t-transparent rounded-full animate-spin"></div></div>';
    }

    protected function getListeners(): array
    {
        if (!$this->activeChatRoomId) {
            return [];
        }

        return [
            "echo-private:chat-room.{$this->activeChatRoomId},MessageSent" => 'handleIncomingMessage',
        ];
    }

    public function handleIncomingMessage(): void
    {
        if ($this->activeChatRoom) {
            $this->activeChatRoom->refresh();
            $this->markMessagesAsRead();
        }

        unset($this->chatMessages, $this->chatRooms);
        $this->dispatch('message-sent');
    }

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    /** @return Collection<int, ChatRoom> */
    #[Computed]
    public function chatRooms(): Collection
    {
        $userId = $this->getAuthModel()->getKey();

        return ChatRoom::query()
            ->where(function ($q) use ($userId) {
                $q->where('user_a_id', $userId)->orWhere('user_b_id', $userId);
            })
            ->with([
                'userA',
                'userB',
                'messages' => fn ($q) => $q->latest()->limit(1),
            ])
            ->latest('updated_at')
            ->limit($this->roomsLimit)
            ->get();
    }

    #[Computed]
    public function hasMoreRooms(): bool
    {
        return $this->chatRooms->count() >= $this->roomsLimit;
    }

    #[Computed]
    public function activeChatRoom(): ?ChatRoom
    {
        if (!$this->activeChatRoomId) {
            return null;
        }

        return ChatRoom::with(['userA', 'userB'])->find($this->activeChatRoomId);
    }

    #[Computed]
    public function otherUser(): ?User
    {
        return $this->activeChatRoom?->otherParticipant($this->getAuthModel());
    }

    /** @return Collection<int, ChatMessage> */
    #[Computed]
    public function chatMessages(): Collection
    {
        if (!$this->activeChatRoomId) {
            return new Collection();
        }

        return $this->activeChatRoom
            ?->messages()
            ->with('sender')
            ->oldest()
            ->get() ?? new Collection();
    }

    #[Computed]
    public function hasBothSharedContacts(): bool
    {
        return $this->activeChatRoom?->hasBothSharedContacts() ?? false;
    }

    #[Computed]
    public function hasCurrentUserSharedContacts(): bool
    {
        return $this->activeChatRoom?->hasUserSharedContacts($this->getAuthModel()) ?? false;
    }

    #[Computed]
    public function currentUserHasContactChannels(): bool
    {
        return $this->getAuthModel()
            ->contactChannels()
            ->where('is_enabled', true)
            ->whereNotNull('verified_at')
            ->exists();
    }

    #[Computed]
    public function isMatchedWithOtherUser(): bool
    {
        return filled($this->otherUser) && $this->getAuthModel()->isRoommateWith($this->otherUser);
    }

    public function selectRoom(string $uuid): void
    {
        $authUser = $this->getAuthModel();
        $room = ChatRoom::find($uuid);

        if (!$room) {
            return;
        }

        if ((int) $room->user_a_id !== $authUser->getKey()
            && (int) $room->user_b_id !== $authUser->getKey()) {
            return;
        }

        $this->activeChatRoomId = $uuid;
        unset($this->chatMessages, $this->activeChatRoom, $this->otherUser, $this->hasBothSharedContacts, $this->hasCurrentUserSharedContacts, $this->isMatchedWithOtherUser);

        $this->markMessagesAsRead();
        unset($this->chatRooms);

        $this->js('history.pushState({}, "", "/chat/' . rawurlencode($uuid) . '")');
    }

    public function loadMoreRooms(): void
    {
        $this->roomsLimit += 15;
        unset($this->chatRooms, $this->hasMoreRooms);
    }

    public function sendMessage(): void
    {
        $this->validate(['newMessage' => ['required', 'string', 'max:1000']]);

        if (!$this->activeChatRoom) {
            return;
        }

        $message = $this->activeChatRoom->messages()->create([
            'sender_id' => $this->getAuthModel()->getKey(),
            'message'   => $this->newMessage,
        ]);

        MessageSent::dispatch($message);

        $this->newMessage = '';
        $this->activeChatRoom->touch();
        unset($this->chatMessages, $this->chatRooms);
        $this->dispatch('message-sent');
    }

    public function shareContactsAction(): Action
    {
        return Action::make('shareContacts')
            ->label('Share Contacts')
            ->requiresConfirmation()
            ->modalHeading('Share your contact details?')
            ->modalDescription('Your contact information will be shared with ' . ($this->otherUser?->first_name ?? 'this user') . '. They will need to share theirs too before either of you can view them.')
            ->modalSubmitActionLabel('Yes, share contacts')
            ->color('primary')
            ->action(function (): void {
                $authUser = $this->getAuthModel();

                if (!$this->activeChatRoom || !$this->isMatchedWithOtherUser || $this->activeChatRoom->hasUserSharedContacts($authUser)) {
                    return;
                }

                $this->activeChatRoom->markContactSharedBy($authUser);
                $this->activeChatRoom->refresh();
                unset($this->hasBothSharedContacts, $this->hasCurrentUserSharedContacts);

                Notification::make()
                    ->title('Contact sharing request sent')
                    ->body('Waiting for ' . $this->otherUser?->first_name . ' to also share their contacts.')
                    ->info()
                    ->send();
            });
    }

    public function unshareContactsAction(): Action
    {
        return Action::make('unshareContacts')
            ->label('Undo')
            ->requiresConfirmation()
            ->modalHeading('Withdraw contact sharing request?')
            ->modalDescription('Your contact sharing request will be cancelled. ' . ($this->otherUser?->first_name ?? 'The other user') . ' will no longer be notified that you want to share contacts.')
            ->modalSubmitActionLabel('Yes, withdraw')
            ->color('danger')
            ->action(function (): void {
                $authUser = $this->getAuthModel();

                if (!$this->activeChatRoom || !$this->isMatchedWithOtherUser || !$this->activeChatRoom->hasUserSharedContacts($authUser)) {
                    return;
                }

                $this->activeChatRoom->markContactUnsharedBy($authUser);
                $this->activeChatRoom->refresh();
                unset($this->hasBothSharedContacts, $this->hasCurrentUserSharedContacts);

                Notification::make()
                    ->title('Contact sharing request withdrawn')
                    ->body('Your contact sharing request has been cancelled.')
                    ->warning()
                    ->send();
            });
    }

    public function openContactModal(): void
    {
        if (!$this->isMatchedWithOtherUser || !$this->activeChatRoom?->hasBothSharedContacts()) {
            return;
        }

        $this->mountAction('contactUser', ['user' => $this->otherUser?->uuid]);
    }

    protected function markMessagesAsRead(): void
    {
        if (!$this->activeChatRoomId) {
            return;
        }

        ChatRoom::find($this->activeChatRoomId)
            ?->messages()
            ->where('sender_id', '!=', $this->getAuthModel()->getKey())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.components.chat.chat-drawer');
    }
}
