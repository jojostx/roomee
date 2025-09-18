<?php

namespace App\Http\Livewire\Components\Filament\Forms;

use Closure;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class Password extends TextInput
{
    protected string $view = 'livewire.components.filament.forms.password';

    protected string $showIcon = 'heroicon-o-eye';

    protected string $hideIcon = 'heroicon-o-eye-off';

    protected bool|Closure  $revealable = true;

    protected bool|Closure  $copyable = false;

    protected string $copyIcon = 'heroicon-o-clipboard';

    protected string $generateIcon = 'heroicon-o-key';

    protected bool|Closure  $generatable = false;

    protected int $passwordMinLen = 8;

    protected bool $passwordUsesNumbers = true;

    protected bool $passwordUsesSymbols = true;

    public function generatable(bool|Closure $condition = true): static
    {
        $this->generatable = $condition;

        return $this;
    }

    public function generateIcon(string $icon): static
    {
        $this->generateIcon = $icon;

        return $this;
    }

    public function getGenerateIcon(): string
    {
        return $this->generateIcon;
    }

    public function isGeneratable(): bool
    {
        return (bool) $this->evaluate($this->generatable);
    }

    public function passwordLength(int $len): static
    {
        $this->passwordMinLen = $len;
        return $this;
    }

    public function passwordUsesNumbers(bool $use = true): static
    {
        $this->passwordUsesNumbers = $use;
        return $this;
    }

    public function passwordUsesSymbols(bool $use = true): static
    {
        $this->passwordUsesSymbols = $use;
        return $this;
    }

    public function getPasswordLength(): int
    {
        return $this->passwordMinLen;
    }

    public function getPasswordChars(): string
    {
        return collect(range('a', 'z'))
            ->merge(range('A', 'Z'))
            ->when($this->passwordUsesNumbers, fn ($chars) => $chars->merge(range(0, 9)))
            ->when($this->passwordUsesSymbols, fn ($chars) => $chars->merge(['!#$%&()*+,-./:;<=>?@[\]^_`{|}~']))
            ->join('');
    }

    public function copyable(bool|Closure $condition = true): static
    {
        $this->copyable = $condition;

        return $this;
    }

    public function copyIcon(string $icon): static
    {
        $this->copyIcon = $icon;

        return $this;
    }

    public function isCopyable(): bool
    {
        return (bool) $this->evaluate($this->copyable);
    }

    public function getCopyIcon(): string
    {
        return $this->copyIcon;
    }

    public function revealable(bool|Closure $condition = true): static
    {
        $this->revealable = $condition;

        return $this;
    }

    public function showIcon(string $icon): static
    {
        $this->showIcon = $icon;

        return $this;
    }

    public function hideIcon(string $icon): static
    {
        $this->hideIcon = $icon;

        return $this;
    }

    public function getShowIcon(): string
    {
        return $this->showIcon;
    }

    public function getHideIcon(): string
    {
        return $this->hideIcon;
    }

    public function isRevealable(): bool
    {
        return (bool) $this->evaluate($this->revealable);
    }

    public function getXRef(): string
    {
        return Str::of($this->getId())->replace(".", "_")->prepend('input_')->studly()->snake()->toString();
    }
}
