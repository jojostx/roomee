<?php

namespace App\Providers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\Laravel\Facades\Image;

class UploadedFileServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        UploadedFile::macro('manipulate', function (callable $callback) {
            return tap($this, function (UploadedFile $file) use ($callback) {
                /** @var \Intervention\Image\Image $image */
                $image = Image::read($file->getRealPath());

                $params = $callback($image);

                if (filled($params) && is_array($params)) {
                    [
                        'path' => $path,
                        'quality' => $quality,
                        'format' => $format
                    ] = array_merge([
                        'path' => null,
                        'quality' => null,
                        'format' => null
                    ], $params);

                    $encoded = $image->encodeByPath($path ?? $file->getRealPath(), quality: $quality ?? 90);
                    file_put_contents($path ?? $file->getRealPath(), $encoded);
                } else {
                    $encoded = $image->encodeByPath($file->getRealPath());
                    file_put_contents($file->getRealPath(), $encoded);
                }
            });
        });
    }
}
