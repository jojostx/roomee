<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Http;

class TwilioSmsSender implements SmsSender
{
    public function __construct(
        protected string $sid,
        protected string $token,
        protected string $from,
    ) {
    }

    public function send(string $to, string $message): bool
    {
        $response = Http::asForm()
            ->withBasicAuth($this->sid, $this->token)
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$this->sid}/Messages.json", [
                'From' => $this->from,
                'To' => $to,
                'Body' => $message,
            ]);

        return $response->successful();
    }
}
