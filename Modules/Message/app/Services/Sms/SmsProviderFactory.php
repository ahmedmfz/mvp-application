<?php

namespace Modules\Message\Services\Sms;

use Modules\Message\Services\Sms\Contracts\SmsProvider;
use Modules\Message\Services\Sms\Providers\MessageBirdSmsProvider;
use Modules\Message\Services\Sms\Providers\TwilioSmsProvider;
use Modules\Message\Services\Sms\Providers\VonageSmsProvider;
use InvalidArgumentException;

final class SmsProviderFactory
{
    public function make(?string $provider = null): SmsProvider
    {
        $provider = $provider ?: config('sms.default');

        return match ($provider) {
            'twilio' => app(TwilioSmsProvider::class),
            'nexmo'  => app(VonageSmsProvider::class),
            'messagebird' => app(MessageBirdSmsProvider::class),
            default  => throw new InvalidArgumentException("Unsupported SMS provider: {$provider}"),
        };
    }
}