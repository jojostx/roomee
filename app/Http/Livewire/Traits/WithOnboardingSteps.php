<?php

namespace App\Http\Livewire\Traits;

use App\Models\Blocklist;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

trait WithOnboardingSteps
{
    abstract protected function getAuthModel(): ?User;

    public function openOnboardingStepModal()
    {
        if ($this->getAuthModel()->onboarding()->finished()) {
            return;
        }

        $step = $this->getAuthModel()->onboarding()->nextUnfinishedStep();

        $this->emit(
            'openModal',
            'components.modals.onboarding-step-modal',
            ['step_data' => [
                'cta' => $step->cta,
                'title' => $step->title,
                'body' => $step->body,
                'link' => $step->link,
            ]]
        );
    }
}
