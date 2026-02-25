<?php

namespace Modules\Message\Services;

use Modules\Message\Enums\MessageStatusEnum;
use Modules\Message\Enums\MessageTypeEnum;
use Modules\Message\Models\Message;

class MessageService
{
    public function createWelcomeMessage(int $userId): void
    {
        Message::create([
            'user_id'      => $userId,
            'message_type' => MessageTypeEnum::EMAIL->value,
            'status'       => MessageStatusEnum::SENT->value,
            'message_content'=> 'Welcome to our platform!',
            'created_at'   => now(),
        ]);
    }
}