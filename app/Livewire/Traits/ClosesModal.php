<?php

namespace App\Livewire\Traits;

trait ClosesModal
{
    public function closeModal(): void
    {
        $this->dispatch('closeModal');
    }
}
