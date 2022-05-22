<?php

namespace App\Http\Livewire\Components\Filament\Forms;

use Closure;
use Filament\Forms\Components\FileUpload as ComponentsFileUpload;

class Fileupload extends ComponentsFileUpload
{
    protected string $view = 'livewire.components.filament.forms.fileupload';

    protected bool $hasDefaultState = true;

    public ?Closure $image = null;

    protected function setUp(): void
    {
        parent::setUp();
        
        // $this->afterStateHydrated(static function ($component, string | array | null $state): void {
        //     if (blank($state)) {
        //         $component->state([]);

        //         return;
        //     }

        //     $files = collect(Arr::wrap($state))
        //         ->mapWithKeys(static fn (string $file): array => [(string) Str::uuid() => $file])
        //         ->toArray();

        //     $component->state($files);
        // });

        // if ($this->hasDefaultState()) {
        //     $this->setImage($this->getDefaultState());
        // }else {
        //     $this->setImage();
        // }
    }

    public function setImage($image = "https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=N+A"): static
    {
        $this->image = $image;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->evaluate($this->image);
    }
}
