<?php

namespace Modules\Message\Services\Sms\Contracts;

use Modules\Message\Services\Sms\DTO\SmsSendResult;


interface SmsProvider
{
    /**
     * Send SMS message.
     *
     * @param  string  $to      E.164 format recommended, e.g. +9715xxxxxxx
     * @param  string  $message
     * @param  array   $options optional provider-specific overrides
     */
    public function send(string $to, string $message, array $options = []): SmsSendResult;
}