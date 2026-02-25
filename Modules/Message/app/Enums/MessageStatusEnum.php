<?php

namespace Modules\Message\Enums;

enum MessageStatusEnum: string
{
    case SENT = 'SENT';
    case FAILED = 'FAILED';
    case PENDING = 'PENDING';
}
