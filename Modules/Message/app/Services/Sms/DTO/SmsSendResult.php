<?php

namespace Modules\Message\Services\Sms\DTO;

class SmsSendResult
{
    public function __construct(
        public bool $success,
        public ?string $providerMessageId = null,
        public ?string $provider = null,
        public ?string $error = null,
        public array $raw = [],
    ) {}

    public static function ok(string $provider, ?string $messageId = null, array $raw = []): self
    {
        return new self(true, $messageId, $provider, null, $raw);
    }

    public static function fail(string $provider, string $error, array $raw = []): self
    {
        return new self(false, null, $provider, $error, $raw);
    }
}