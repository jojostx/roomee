<?php

namespace App\Http\Livewire\Pages\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationsSettingsPage extends Component
{
    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.settings.notifications-settings-page');

        return $view->layout('layouts.guest');
    }
}
