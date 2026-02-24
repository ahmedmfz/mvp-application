<?php

namespace Modules\Statistic\Services;

use Illuminate\Support\Facades\DB;
use Modules\Statistic\Enums\DailyStatCounter;
use Modules\Statistic\Models\Statistic;

class DailyStatisticsService
{
    public function increment(DailyStatCounter $counter, int $by = 1, ?string $date = null): void
    {
        $date = $date ?? now()->toDateString();

        $stat = Statistic::firstOrCreate(
            ['date' => $date],
            ['total_users_created' => 0, 'total_users_updated' => 0, 'total_users_deleted' => 0]
        );

        Statistic::where('date', $date)->increment($counter->value, $by, ['updated_at' => now()]);
    }
}