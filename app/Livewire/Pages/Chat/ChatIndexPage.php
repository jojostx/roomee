<?php

namespace App\Livewire\Pages\Chat;

use Livewire\Component;

class ChatIndexPage extends Component
{
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.pages.chat.chat-index-page')
            ->layout('layouts.guest');
    }
}
