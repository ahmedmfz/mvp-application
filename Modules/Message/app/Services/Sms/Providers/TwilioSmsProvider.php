<?php

namespace Modules\Message\Services\Sms\Providers;

use Modules\Message\Services\Sms\Contracts\SmsProvider;
use Modules\Message\Services\Sms\DTO\SmsSendResult;
use Illuminate\Support\Facades\Http;

class TwilioSmsProvider implements SmsProvider
{
    public function send(string $to, string $message, array $options = []): SmsSendResult
    {
        \Log::info('TwilioSmsProvider::send', [
            'to' => $to,
            'message' => $message,
            'options' => $options,
        ]);
       return SmsSendResult::ok('twilio', null, []);
        // $cfg = config('sms.providers.twilio');

        // $from = $options['from'] ?? $cfg['from'];
        // $sid  = $cfg['account_sid'];
        // $token = $cfg['auth_token'];

        // if (!$sid || !$token || !$from) {
        //     return SmsSendResult::fail('twilio', 'Twilio config is missing (account_sid/auth_token/from).');
        // }

        // $url = "https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json";

        // $response = Http::asForm()
        //     ->withBasicAuth($sid, $token)
        //     ->post($url, [
        //         'From' => $from,
        //         'To'   => $to,
        //         'Body' => $message,
        //     ]);

        // if ($response->successful()) {
        //     $data = $response->json();
        //     return SmsSendResult::ok('twilio', $data['sid'] ?? null, $data ?? []);
        // }

        // return SmsSendResult::fail('twilio', $response->body(), $response->json() ?? []);
    }
}