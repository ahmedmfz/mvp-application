<?php

namespace Modules\Message\Services;

use Modules\Message\Services\Sms\DTO\SmsSendResult;
use Modules\Message\Services\Sms\SmsProviderFactory;

class SmsService 
{
    public function __construct(private SmsProviderFactory $factory) {}

    public function send(string $to, string $message, array $options = []): SmsSendResult
    {
        $provider = $this->factory->make(); // uses config('sms.default')
        return $provider->send($to, $message, $options);
    }

    public function sendVia(string $providerName, string $to, string $message, array $options = []): SmsSendResult
    {
        $provider = $this->factory->make($providerName);
        return $provider->send($to, $message, $options);
    }
}