<?php 

namespace Modules\Activity\Services;

use Modules\Activity\Models\Activity;
use Modules\Activity\Enums\ActionTypeEnum;

class ActivityLogger
{
    public static function log(ActionTypeEnum $action, int $referenceId, array $metadata = []): Activity
    {
        return Activity::create([
            'action_type' => $action->value,
            'reference_id' => $referenceId,
            'metadata' => $metadata,
        ]);
    }
}
