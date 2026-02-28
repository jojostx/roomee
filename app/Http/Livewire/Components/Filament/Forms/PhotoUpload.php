<?php

namespace App\Http\Livewire\Components\Filament\Forms;

use Closure;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\Concerns\HasExtraInputAttributes;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use Illuminate\Support\Arr;
use Intervention\Image\Image;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Throwable;

class PhotoUpload extends BaseFileUpload
{
    use HasExtraInputAttributes;
    use HasPlaceholder;
    use HasExtraAlpineAttributes;

    protected string $view = 'livewire.components.filament.forms.photo-upload';

    public ?string $previewImageUrl = '';

    protected bool | Closure $isAvatar = false;

    protected int | Closure | null $minCroppedWidth = 320;

    protected int | Closure | null $maxCroppedWidth = 960;

    protected int | Closure | null $minCroppedHeight = 320;

    protected string | Closure | null $imageCropAspectRatio = null;

    protected int | Closure | null $imagePreviewHeight = null;

    protected int | Closure | null $imageResizeTargetHeight = null;

    protected int | Closure | null $imageResizeTargetWidth = null;

    protected string | Closure | null $altText = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->image();

        $this->reorderable(); // Fixed: was enableReordering()

        $this->afterStateHydrated(static function (PhotoUpload $component, string | array | null $state): void {
            if (blank($component->getMinSize())) {
                // kilobytes
                $component->minSize(10);
            }

            if (blank($component->getMaxSize())) {
                $component->maxSize(5242);
            }

            if (blank($state)) {
                $component->state([]);

                return;
            }

            $files = collect(Arr::wrap($state))
                ->filter(static fn (string $file) => blank($file) || $component->getDisk()->exists($file))
                ->mapWithKeys(static fn (string $file): array => [(string) str()->uuid() => $file])
                ->all();

            $component->state($files);
        });

        $this->afterStateUpdated(static function (PhotoUpload $component, $state, $old) {
            // Fixed: Check for multiple using the actual state structure
            // In Filament 3.x, multiple is determined by whether the component accepts multiple files
            if (! $component->isMultiple() && filled($old)) {
                // Delete old files when replacing single file
                foreach (Arr::wrap($old) as $key => $value) {
                    if (is_string($key)) {
                        $component->deleteUploadedFile($key);
                    }
                }
            }

            // Transform newState using Intervention image (gd driver)
            if ($state instanceof TemporaryUploadedFile) {
                try {
                    $currentState = $component->getState();
                    $key = array_search($state, Arr::wrap($currentState), true);

                    if (blank($key) || !is_string($key)) {
                        return;
                    }

                    $cropData = json_decode(str($key)->after('::')->value(), true);

                    if (filled($cropData) && is_array($cropData)) {
                        $cropData = array_merge([
                            'width' => null,
                            'height' => null,
                            'x' => null,
                            'y' => null
                        ], $cropData);

                        [
                            'width' => $width,
                            'height' => $height,
                            'x' => $x,
                            'y' => $y
                        ] = $cropData;

                        // Fixed: Better null/blank checking
                        if (filled($width) && filled($height) && filled($x) && filled($y)) {
                            $state->manipulate(function (Image $image) use ($width, $height, $x, $y, $component) {
                                $resizeWidth = $component->getImageResizeTargetWidth();
                                $resizeHeight = $component->getImageResizeTargetHeight();

                                if ($resizeWidth || $resizeHeight) {
                                    $image
                                        ->crop((int) $width, (int) $height, (int) $x, (int) $y)
                                        ->resize(
                                            $resizeWidth,
                                            $resizeHeight,
                                            function ($constraint) {
                                                $constraint->aspectRatio();
                                            }
                                        );
                                } else {
                                    $image->crop((int) $width, (int) $height, (int) $x, (int) $y);
                                }

                                return [];
                            });
                        }
                    }

                    return;
                } catch (Throwable $th) {
                    throw $th;
                }
            }

            if (blank($state)) {
                return;
            }

            if (is_array($state)) {
                return;
            }

            $component->state([str()->uuid() => $state]);
        });
    }

    // Removed: callAfterStateUpdated() override - it's incompatible with BaseFileUpload's signature

    public function idleLabel(string | Closure | null $label): static
    {
        $this->placeholder($label);

        return $this;
    }

    public function avatar(bool | Closure $condition = true): static
    {
        $this->isAvatar = $condition;

        $this->image();

        $this->imageCropAspectRatio('1:1'); // Fixed: passing string instead of int

        return $this;
    }

    public function isAvatar(): bool
    {
        return (bool) $this->evaluate($this->isAvatar);
    }

    public function image(): static
    {
        // Fixed: Use proper MIME types
        $this->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png']);

        return $this;
    }

    public function imageResizeTargetWidth(float | int | Closure | null $imageResizeTargetWidth): static
    {
        $this->imageResizeTargetWidth = $imageResizeTargetWidth;

        return $this;
    }

    public function imageResizeTargetHeight(float | int | Closure | null $imageResizeTargetHeight): static
    {
        $this->imageResizeTargetHeight = $imageResizeTargetHeight;

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

    public function altText(string | Closure | null $altText): static
    {
        $this->altText = $altText;

        return $this;
    }

    public function getPreviewImageUrlUsing(string | Closure | null $previewImageUrl): static
    {
        $this->previewImageUrl = $previewImageUrl;

        return $this;
    }

    public function getPreviewImageUrl(): ?string
    {
        if (blank($this->evaluate($this->previewImageUrl))) {
            return '';
        }

        return $this->evaluate($this->previewImageUrl);
    }

    public function getAltText(): ?string
    {
        if (blank($this->evaluate($this->altText))) {
            return $this->isAvatar() ? 'avatar image' : 'profile image';
        }

        return $this->evaluate($this->altText);
    }

    public function getImageResizeTargetWidth(): ?int
    {
        return $this->evaluate($this->imageResizeTargetWidth);
    }

    public function getImageResizeTargetHeight(): ?int
    {
        return $this->evaluate($this->imageResizeTargetHeight);
    }

    public function getMinCroppedWidth(): ?int
    {
        $evaluated = $this->evaluate($this->minCroppedWidth);
        
        if (blank($evaluated)) {
            return $this->isAvatar() ? 320 : (int) ((16 / 9) * 320); // Fixed: proper aspect ratio calculation
        }

        return $evaluated;
    }

    public function getMaxCroppedWidth(): ?int
    {
        $evaluated = $this->evaluate($this->maxCroppedWidth);
        
        if (blank($evaluated)) {
            return $this->isAvatar() ? 960 : (int) ((16 / 9) * 960); // Fixed: should be different from min
        }

        return $evaluated;
    }

    protected function getCropAspectRatioValue(): float
    {
        $ratio = $this->getImageCropAspectRatio();

        if (blank($ratio)) {
            return 1.0;
        }

        if (str_contains($ratio, ':')) {
            [$width, $height] = array_pad(explode(':', $ratio, 2), 2, 1);
            $width = (float) $width;
            $height = (float) $height;

            return $height > 0 ? $width / $height : 1.0;
        }

        return is_numeric($ratio) ? (float) $ratio : 1.0;
    }

    public function getMinCroppedHeight(): int
    {
        if (filled($this->evaluate($this->minCroppedHeight))) {
            return $this->evaluate($this->minCroppedHeight);
        }

        $ratio = $this->getCropAspectRatioValue();
        $ratio = $ratio > 0 ? $ratio : 1.0;
        $minWidth = $this->getMinCroppedWidth() ?? 0;

        return (int) round($minWidth / $ratio);
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
        if (filled($this->evaluate($this->imagePreviewHeight))) {
            return $this->evaluate($this->imagePreviewHeight);
        }

        $ratio = $this->getCropAspectRatioValue();
        $ratio = $ratio > 0 ? $ratio : 1.0;
        $minWidth = $this->getMinCroppedWidth() ?? 0;

        return (int) round($minWidth / $ratio);
    }

    public function getUploadedFileNameForStorage(TemporaryUploadedFile $file): string
    {
        $name = $this->evaluate($this->getUploadedFileNameForStorageUsing, [
            'file' => $file,
        ]);

        $extension = '';
        if ($guessedExtension = $file->guessExtension()) {
            $extension = '.' . $guessedExtension;
        }

        $name = $this->normalizeFilename($name);

        return $name . $extension;
    }

    public function normalizeFilename(?string $name = null): string
    {
        if (blank($name)) {
            $name = str()->random(40);
        }

        $name = trim($name, "/ \t\n\r\0\x0B");

        return $name;
    }
}
