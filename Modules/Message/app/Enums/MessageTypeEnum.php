<?php

namespace Modules\Message\Enums;

enum MessageTypeEnum: string
{
    case EMAIL = 'EMAIL';
    case SMS = 'SMS';
}