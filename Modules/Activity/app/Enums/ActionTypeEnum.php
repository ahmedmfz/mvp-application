<?php

namespace Modules\Activity\Enums;

enum ActionTypeEnum: string
{
    case USER_CREATED = 'USER_CREATED';
    case USER_UPDATED = 'USER_UPDATED';
    case USER_DELETED = 'USER_DELETED';

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function array(): array
    {
        return array_combine(self::values(), self::names());
    }
}
