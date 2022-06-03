<?php

namespace App\Http\Livewire\Components\Filament\Forms;

use Closure;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\Concerns\HasExtraInputAttributes;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Support\Concerns\HasExtraAlpineAttributes;

class PhotoUpload extends BaseFileUpload
{
    use HasExtraInputAttributes;
    use HasPlaceholder;
    use HasExtraAlpineAttributes;
    
    protected string $view = 'livewire.components.filament.forms.photo-upload';

    public ?string $imageUrl = '';

    protected bool | Closure $isAvatar = false;

    protected int | Closure | null $minCroppedWidth = 320;

    protected int | Closure | null $maxCroppedWidth = 960;

    protected int | Closure | null $minCroppedHeight = 320;

    protected string | Closure | null $imageCropAspectRatio = null;

    protected int | Closure | null $imagePreviewHeight = null;

    protected string | Closure | null $altText = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(static function (BaseFileUpload $component, string | array | null $state): void {
            $component->imageUrl = $state;

            if (blank($component->getMinSize())) {
                // kilobytes
                $component->minSize(10);
            }

            if (blank($component->getMaxSize())) {
                $component->maxSize(5242);
            }
        });
    }

    public function idleLabel(string | Closure | null $label): static
    {
        $this->placeholder($label);

        return $this;
    }

    public function avatar(): static
    {
        $this->isAvatar = true;

        $this->image();

        $this->imageCropAspectRatio(1);

        return $this;
    }

    public function isAvatar(): bool
    {
        return (bool) $this->evaluate($this->isAvatar);
    }

    public function image(): static
    {
        $this->acceptedFileTypes(['image/jpg', 'image/png', 'image/jpeg']);

        return $this;
    }

    public function minCroppedWidth(float | int | Closure | null $minCroppedWidth): static
    {
        $this->minCroppedWidth = $minCroppedWidth;

        return $this;
    }

    public function maxCroppedWidth(float | int | Closure | null $maxCroppedWidth): static
    {
        $this->maxCroppedWidth = $maxCroppedWidth;

        return $this;
    }

    public function minCroppedHeight(float | int | Closure | null $minCroppedHeight): static
    {
        $this->minCroppedHeight = $minCroppedHeight;

        return $this;
    }

    public function imageCropAspectRatio(string | Closure | null $ratio): static
    {
        $this->imageCropAspectRatio = $ratio;

        return $this;
    }

    public function imagePreviewHeight(int | Closure | null $height): static
    {
        $this->imagePreviewHeight = $height;

        return $this;
    }

    public function imageUrl(string | Closure | null $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function altText(string | Closure | null $altText): static
    {
        $this->altText = $altText;

        return $this;
    }

    public function getAltText(): ?string
    {
        if (blank($this->evaluate($this->altText))) {
            return  $this->isAvatar() ? 'avatar image' : 'cover image';
        }

        return $this->evaluate($this->altText);
    }

    public function getImageUrl(): ?string
    {
        if (blank($this->evaluate($this->imageUrl))) {
            return '';
        }

        return $this->evaluate($this->imageUrl);
    }

    public function getMinCroppedWidth(): ?int
    {
        if (blank($this->evaluate($this->minCroppedWidth))) {
            return $this->isAvatar() ? 320 : (16 / 9) * 320;
        }

        return $this->evaluate($this->minCroppedWidth);
    }

    public function getMaxCroppedWidth(): ?int
    {
        if (blank($this->evaluate($this->maxCroppedWidth))) {
            return $this->isAvatar() ? 320 : (16 / 9) * 320;
        }

        return $this->evaluate($this->maxCroppedWidth);
    }

    public function getMinCroppedHeight(): int
    {
        if (blank($this->evaluate($this->minCroppedHeight))) {
            return $this->isAvatar() ? 320 : (16 / 9) / 320;
        }

        return $this->evaluate($this->minCroppedHeight);
    }

    public function getImageCropAspectRatio(): string
    {
        if (blank($this->evaluate($this->imageCropAspectRatio))) {
            return $this->isAvatar() ? '1:1' : '16:9';
        }

        return $this->isAvatar() ? '1:1' : $this->evaluate($this->imageCropAspectRatio);
    }

    public function getImagePreviewHeight(): int
    {
        if (blank($this->evaluate($this->imagePreviewHeight))) {
            return $this->isAvatar() ? 320 : (16 / 9) / 320;
        }

        return $this->evaluate($this->imagePreviewHeight);
    }
}
