<?php

namespace App\Livewire\Pages\Chat;

use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ChatIndexPage extends Component
{
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
            ->where('user_a_id', $userId)
            ->orWhere('user_b_id', $userId)
            ->with([
                'userA',
                'userB',
                'messages' => fn ($q) => $q->latest()->limit(1),
            ])
            ->latest('updated_at')
            ->get();
    }

    public function render()
    {
        return view('livewire.pages.chat.chat-index-page')
            ->layout('layouts.guest');
    }
}
