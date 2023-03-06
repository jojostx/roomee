<?php

namespace App\Http\Livewire\Pages\Settings;

use Livewire\Component;

class NotificationsSettingsPage extends Component
{
    //defaults
    /**
     * phone_number
     * email
     * facebook
     * instagram
     * snapchat
     * telegram
     * twitter
     * whatsapp
     */
    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.settings.notifications-settings-page');

        return $view->layout('layouts.guest');
    }
}
