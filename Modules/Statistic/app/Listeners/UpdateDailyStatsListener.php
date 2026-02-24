<?php

namespace Modules\Statistic\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Modules\Statistic\Enums\DailyStatCounter;
use Modules\Statistic\Services\DailyStatisticsService;
use Modules\User\Events\UserCreated;
use Modules\User\Events\UserUpdated;
use Modules\User\Events\UserDeleted;


class UpdateDailyStatsListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        private readonly DailyStatisticsService $stats
    ) {}

    public function handle(object $event): void
    {
        $counter = match (true) {
            $event instanceof UserCreated => DailyStatCounter::USERS_CREATED,
            $event instanceof UserUpdated => DailyStatCounter::USERS_UPDATED,
            $event instanceof UserDeleted => DailyStatCounter::USERS_DELETED,
            default => null,
        };

        if (!$counter) {
            return;
        }

        $this->stats->increment($counter, 1);

        Log::info('Daily stats updated', [
            'event' => class_basename($event),
            'counter' => $counter->value,
            'user_id' => $event->userId ?? null,
            'date' => now()->toDateString(),
        ]);
    }
}