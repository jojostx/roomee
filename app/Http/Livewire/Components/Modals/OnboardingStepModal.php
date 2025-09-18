<?php

namespace App\Http\Livewire\Components\Modals;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use LivewireUI\Modal\ModalComponent;

class OnboardingStepModal extends ModalComponent
{
    public $step_cta, $step_title, $step_body, $step_link;
    protected static $modalMaxWidth = 'sm';

    public function mount($step_data)
    {
        $this->step_cta = $step_data['cta'];
        $this->step_title = $step_data['title'];
        $this->step_body = $step_data['body'];
        $this->step_link = $step_data['link'];
        static::$modalMaxWidth = $step_data['modalMaxWidth'] ?? 'sm';
    }

    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }

    public static function modalMaxWidth(): string
    {
        return static::$modalMaxWidth ?? 'sm';
    }

    public function render()
    {
        return view('livewire.components.modals.onboarding-step-modal');
    }
}
