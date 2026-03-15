<?php

namespace App\Livewire\Pages\Settings;

use Illuminate\View\View;
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
        /** @var View */
        $view = view('livewire.pages.settings.notifications-settings-page');

        return $view->layout('layouts.guest');
    }
}
