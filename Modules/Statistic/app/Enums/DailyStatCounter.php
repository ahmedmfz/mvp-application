<?php

namespace Modules\Statistic\Enums;

enum DailyStatCounter: string
{
    case USERS_CREATED = 'total_users_created';
    case USERS_UPDATED = 'total_users_updated';
    case USERS_DELETED = 'total_users_deleted';
}