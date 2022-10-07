<?php

namespace App\Http\Livewire\Components\Filament\Forms;

use Closure;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\Concerns\HasExtraInputAttributes;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use Illuminate\Support\Arr;
use Intervention\Image\Image;
use Livewire\TemporaryUploadedFile;
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

        $this->enableReordering();

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

        /**
         * @todo - bug
         *          the uploadedfile object is not updated as the component's state 
         *          if the component was hydrated with a previous state via
         *          $this->fill() or [FilamentField]->default() methods
         */
        $this->afterStateUpdated(static function (PhotoUpload $component, $state) {
            // if the component does not support multiple file upload
            // and the component has an oldState, delete oldState (mixed),
            if (!$component->isMultiple() && filled($component->getOldState())) {
                foreach ($component->getOldState() as $key => $value) {
                    $component->deleteUploadedFile($key);
                };
            }

            // \dd(
            //     // Arr::where($component->getState(), fn (string $file): bool => $file == $state),
            //     // collect($get($component->getStatePath()))->last(),
            //     // collect(Arr::wrap($component->getOldState()))->each(static fn ($state) => $component->deleteUploadedFile($state)),
            //     // collect($component->getState())
            //     //     ->filter(function (string $file) use ($state) {
            //     //         return $file != $state;
            //     //     })
            //     //     ->all(),
            //     // $component->reorderUploadedFiles(),
            //     // array_search($state, $component->getState(), true),
            //     $state,
            //     $component->getOldState(),
            //     $component->getState(),
            //     $component->getStateToDehydrate(),
            // );

            // transform newState using Intervention image (gd driver)
            if ($state instanceof TemporaryUploadedFile) {
                try {
                    $key = array_search($state, Arr::wrap($component->getState()), true);

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

                        !\hasAnyBlankElement($width, $height, $x, $y) &&
                            $state->manipulate(function (Image $image) use ($width, $height, $x, $y, $component) {
                                $resizeWidth = $component->getImageResizeTargetWidth();
                                $resizeHeight = $component->getImageResizeTargetHeight();

                                if ($resizeWidth || $resizeHeight) {
                                    $image
                                        ->crop($width, $height, $x, $y)
                                        ->resize(
                                            $resizeWidth,
                                            $resizeHeight,
                                            function ($constraint) {
                                                $constraint->aspectRatio();
                                            }
                                        );
                                } else {
                                    $image->crop($width, $height, $x, $y);
                                }

                                return [];
                            });
                    };

                    return;
                } catch (Throwable $th) {
                    throw $th;
                }

                return;
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

    public function callAfterStateUpdated(): static
    {
        if ($callback = $this->afterStateUpdated) {
            $state = $this->getState();

            $this->evaluate($callback, [
                'state' => $this->isMultiple() ? $state : Arr::last($state ?? []),
            ]);
        }

        return $this;
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
            return  $this->isAvatar() ? 'avatar image' : 'cover image';
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

    public function getUploadedFileNameForStorage(TemporaryUploadedFile $file): string
    {
        $name = $this->evaluate($this->getUploadedFileNameForStorageUsing, [
            'file' => $file,
        ]);

        // @todo || str($name)->test() - check if filename endsWith a valid extension 
        // or with an extension present in the acceptable types array
        if ($extension = $file->guessExtension()) {
            $extension = '.' . $extension;
        }

        $name = $this->normalizeFilename($name);

        return $name . $extension;
    }

    public function normalizeFilename(?string $name = null): string
    {
        if (blank($name)) {
            $name = str()->random(40);
        }

        $name = trim($name, '/ \t\n\r\0\x0B');

        return $name;
    }
}
