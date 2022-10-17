<?php

namespace App\Http\Livewire\Pages\Settings;

use Livewire\Component;

class NotificationsSettingsPage extends Component
{
    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.settings.notifications-settings-page');

        return $view->layout('layouts.guest');
    }
}
