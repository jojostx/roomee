<?php

namespace App\Http\Livewire\Traits;

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

trait WithRequesting
{
    protected function getAuthModel(): ?User
    {
        return Auth::user();
    }
}
