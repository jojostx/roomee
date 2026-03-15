<?php

namespace App\Livewire\Pages\Profile;

use App\Services\Settings\VerificationTimelineSettings;
use Livewire\Attributes\Computed;
use Livewire\Component;

class PendingVerificationPage extends Component
{
    #[Computed]
    public function timelineText(): string
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

