<?php

namespace App\Livewire\Components\Filament\Forms;

use Filament\Support\Concerns\HasExtraAlpineAttributes;
use Filament\Forms\Components\Concerns\HasOptions;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Forms\Components\Concerns;
use Filament\Forms\Components\Field;

class Multiselect extends Field
{
    Use HasExtraAlpineAttributes;
    use HasOptions;
    use HasPlaceholder;
    
    protected string $view = 'livewire.components.filament.forms.multi-select';

    protected function setUp(): void
    {
        parent::setUp();

        $this->default([]);

        $this->afterStateHydrated(static function (MultiSelect $component, $state) {
            if (is_array($state)) {
                return;
            }

            $component->state([]);
        });

        $this->placeholder(__('Please select at least one option'));
    }

    public function getSelectedOptions()
    {
       return auth()->user()->{$this->getName()}->pluck('id')->toArray();
    }
}
