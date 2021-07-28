<?php

namespace App\Http\Livewire\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

trait WithImageManipulation
{
    private function storeImage($base64String, $folderName = 'avatars'): string
    {
        if (!$base64String) {
            return '';
        }
        $image = $this->createTemporaryFile($base64String);
        $imageName = $this->randName($image);
        try {
            $image->move(storage_path("app\\" . $folderName), $imageName);
            return $imageName;
        } catch (\Exception $th) {
            return '';
        }
    }

    protected function createTemporaryFile(string $data): UploadedFile
    {
        $this->file = tmpfile();
        fwrite(
            $this->file,
            base64_decode(Str::after($data, 'base64,'))
        );
        return new UploadedFile(stream_get_meta_data($this->file)['uri'], Str::random(6), null, null, true);
    }

    public function randName(UploadedFile $file): string
    {
        return time() . '-' . Str::random(8) . '.' . $file->guessExtension();
    }
}
