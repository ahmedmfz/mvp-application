<?php

namespace Modules\Activity\Listeners;

use Modules\User\Events\UserCreated;
use Modules\User\Events\UserUpdated;
use Modules\User\Events\UserDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Activity\Enums\ActionTypeEnum;
use Modules\Activity\Services\ActivityLogger;
use Illuminate\Support\Facades\Log;


class LogUserActivityListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        private ActivityLogger $logger
    ) {}

    public function handle(object $event): void
    {
        Log::info('Activity listener received event', [
            'event' => get_class($event),
            'user_id' => $event->userId ?? null,
        ]);

        $actionType = match (true) {
            $event instanceof UserCreated => ActionTypeEnum::USER_CREATED,
            $event instanceof UserUpdated => ActionTypeEnum::USER_UPDATED,
            $event instanceof UserDeleted => ActionTypeEnum::USER_DELETED,
            default => null,
        };

        if (!$actionType) {
            return;
        }

        $metadata = [
            'module' => 'users',
            'event'  => class_basename($event),
        ];

        // Assumes event has $userId
        $this->logger->log($actionType, $event->userId, $metadata);
    }
}
