<?php

namespace Modules\Message\Services\Sms\Providers;

use Modules\Message\Services\Sms\Contracts\SmsProvider;
use Modules\Message\Services\Sms\DTO\SmsSendResult;
use Illuminate\Support\Facades\Http;

final class MessageBirdSmsProvider implements SmsProvider
{
    public function send(string $to, string $message, array $options = []): SmsSendResult
    {
        \Log::info('MessageBirdSmsProvider::send', [
            'to' => $to,
            'message' => $message,
            'options' => $options,
        ]);
        return SmsSendResult::ok('messagebird', null, []);
        // $cfg = config('sms.providers.messagebird');

        // $key = $cfg['access_key'];
        // $originator = $options['originator'] ?? $cfg['originator'];

        // if (!$key || !$originator) {
        //     return SmsSendResult::fail('messagebird', 'MessageBird config is missing (access_key/originator).');
        // }

        // $response = Http::withHeaders([
        //     'Authorization' => "AccessKey {$key}",
        // ])->asForm()->post('https://rest.messagebird.com/messages', [
        //     'originator' => $originator,
        //     'recipients' => $to,
        //     'body' => $message,
        // ]);

        // $data = $response->json() ?? [];

        // if ($response->successful()) {
        //     return SmsSendResult::ok('messagebird', $data['id'] ?? null, $data);
        // }

        // return SmsSendResult::fail('messagebird', $response->body(), $data);
    }
}