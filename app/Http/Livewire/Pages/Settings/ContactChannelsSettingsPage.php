<?php

namespace App\Http\Livewire\Pages\Settings;

use Livewire\Component;

class ContactChannelsSettingsPage extends Component
{
    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.settings.contact-channels-settings-page');

        return $view->layout('layouts.guest');
    }
}
