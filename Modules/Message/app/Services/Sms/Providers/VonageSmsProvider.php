<?php

namespace Modules\Message\Services\Sms\Providers;

use Modules\Message\Services\Sms\Contracts\SmsProvider;
use Modules\Message\Services\Sms\DTO\SmsSendResult;
use Illuminate\Support\Facades\Http;

class VonageSmsProvider implements SmsProvider
{
    public function send(string $to, string $message, array $options = []): SmsSendResult
    {
        \Log::info('VonageSmsProvider::send', [
            'to' => $to,
            'message' => $message,
            'options' => $options,
        ]);
        return SmsSendResult::ok('vonage', null, []);
        // $cfg = config('sms.providers.nexmo');

        // $key = $cfg['api_key'];
        // $secret = $cfg['api_secret'];
        // $from = $options['from'] ?? $cfg['from'];

        // if (!$key || !$secret || !$from) {
        //     return SmsSendResult::fail('nexmo', 'Vonage config is missing (api_key/api_secret/from).');
        // }

        // $response = Http::asForm()->post('https://rest.nexmo.com/sms/json', [
        //     'api_key' => $key,
        //     'api_secret' => $secret,
        //     'from' => $from,
        //     'to' => $to,
        //     'text' => $message,
        // ]);

        // $data = $response->json() ?? [];

        // // Vonage returns messages[0].status == "0" for success
        // $status = data_get($data, 'messages.0.status');
        // if ($response->successful() && $status === '0') {
        //     $messageId = data_get($data, 'messages.0.message-id');
        //     return SmsSendResult::ok('nexmo', $messageId, $data);
        // }

        // $error = data_get($data, 'messages.0.error-text') ?? $response->body();
        // return SmsSendResult::fail('nexmo', (string) $error, $data);
    }
}