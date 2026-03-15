<?php

namespace App\Livewire\Components;

use Livewire\Attributes\On;
use Livewire\Component;

class GlobalModal extends Component
{
    public ?string $componentName = null;

    public array $componentArgs = [];

    public int $renderKey = 0;

    #[On('openModal')]
    public function openModal(string $component, array $arguments = []): void
    {
        $this->componentName = $component;
        $this->componentArgs = $arguments;
        $this->renderKey++;
    }

    #[On('closeModal')]
    public function handleCloseModal(): void
    {
        $this->componentName = null;
        $this->componentArgs = [];
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.components.global-modal');
    }
}
