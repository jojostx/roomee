<?php

namespace App\Http\Livewire\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

trait WithImageManipulation
{
    protected $file;

    private function storeImage($base64String, $folderName = 'avatars'): string|bool
    {
        if (!$base64String) {
            return false;
        }

        $image = $this->createTemporaryFile($base64String);

        if (!$image) {
            return false;
        }
        
        $imageName = $this->randName($image);

        try {
            $image->move(storage_path("app\\" . $folderName), $imageName);
            return $imageName;
        } catch (\Exception $th) {
            return false;
        }
    }

    protected function createTemporaryFile(string $data): UploadedFile|bool
    {
        $file = tmpfile();

        $written = fwrite(
            $file,
            base64_decode(Str::after($data, 'base64,'))
        );

        if (!$written) {
            return false;    
        }

        return new UploadedFile(stream_get_meta_data($file)['uri'], Str::random(6), null, null, true);
    }

    public function randName(UploadedFile $file): string
    {
        \dd($file);
        
        return time() . '-' . Str::random(8) . '.' . $file->guessExtension();
    }
}
