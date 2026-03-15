<?php

namespace App\Livewire\Components\Modals;

use App\Livewire\Traits\ClosesModal;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OnboardingStepModal extends Component
{
    use ClosesModal;
    public $step_cta, $step_title, $step_body, $step_link;

    public function mount($step_data)
    {
        $this->step_cta = $step_data['cta'];
        $this->step_title = $step_data['title'];
        $this->step_body = $step_data['body'];
        $this->step_link = $step_data['link'];
    }

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    public function render()
    {
        return view('livewire.components.modals.onboarding-step-modal');
    }
}
