<?php

namespace Modules\Activity\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Activity\Enums\ActionTypeEnum;
// use Modules\Activity\Database\Factories\ActivityFactory;

class Activity extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'metadata' => 'array',
        'action_type' => ActionTypeEnum::class,
    ];
}
