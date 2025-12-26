<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Log;

class NullSmsSender implements SmsSender
{
    public function send(string $to, string $message): bool
    {
        Log::warning('SMS provider is not configured.', [
            'to' => $to,
            'message' => $message,
        ]);

        return false;
    }
}
