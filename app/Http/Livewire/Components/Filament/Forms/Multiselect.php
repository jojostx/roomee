<?php

namespace App\Http\Livewire\Components\Filament\Forms;

use Filament\Forms\Components\Concerns;
use Filament\Forms\Components\Field;

class Multiselect extends Field
{
    use Concerns\HasExtraAlpineAttributes;
    use Concerns\HasOptions;
    use Concerns\HasPlaceholder;
    
    protected string $view = 'livewire.components.filament.forms.multiselect';

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

        $this->placeholder(__('forms::components.multi_select.placeholder'));
    }

    public function getSelectedOptions()
    {
       return auth()->user()->{$this->getName()}->pluck('id')->toArray();
    }
}
