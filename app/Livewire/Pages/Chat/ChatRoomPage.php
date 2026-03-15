<?php

namespace App\Livewire\Pages\Chat;

use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatRoomPage extends Component
{
    public ChatRoom $chatRoom;

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

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.pages.chat.chat-room-page')
            ->layout('layouts.guest');
    }
}
