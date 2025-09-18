<?php

namespace App\Providers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\Facades\Image;


class UploadedFileServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    UploadedFile::macro('manipulate', function (callable $callback) {
      return tap($this, function (UploadedFile $file) use ($callback) {
        /** @var \Intervention\Image\Image $image */
        $image = Image::make($file->getRealPath());

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

          $image->save($path, $quality, $format);
        } else {
          $image->save();
        }
      });
    });
  }
}
