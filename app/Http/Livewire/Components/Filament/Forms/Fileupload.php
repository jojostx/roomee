<?php

namespace App\Http\Livewire\Components\Filament\Forms;

use Closure;
use Filament\Forms\Components\FileUpload as ComponentsFileUpload;

class Fileupload extends ComponentsFileUpload
{
    protected string $view = 'livewire.components.filament.forms.fileupload';

    protected ?Closure $getPosterFileUrlUsing = null;

    public string $posterFileUrl = "";

    public function setPosterFileUrl(string $url = null): static
    {
        $this->posterFileUrl = $url ?? $this->getPosterFileUrl();

        return $this;
    }

    public function getPosterFileUrl(): string
    {
        $email = auth()->user()?->email ?? 'roomee@roomee.com';

        return "https://www.gravatar.com/avatar/" . md5(strtolower(trim($email))) . "&s=" . 40;
    }
}
