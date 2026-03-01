<?php

namespace App\Http\Livewire\Pages\Profile;

use App\Services\Settings\VerificationTimelineSettings;
use Livewire\Component;

class PendingVerificationPage extends Component
{
    public function getTimelineTextProperty(): string
    {
        return VerificationTimelineSettings::getDisplayText();
    }

    public function render()
    {
        /** @var \Illuminate\View\View */
        $view = view('livewire.pages.profile.pending-verification-page');

        return $view->layout('layouts.guest');
    }
}

