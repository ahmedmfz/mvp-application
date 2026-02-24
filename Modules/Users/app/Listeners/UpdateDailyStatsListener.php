<?php

namespace Modules\Users\Listeners;

use Modules\Users\Events\UserCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class UpdateDailyStatsListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(UserCreated $event): void {
        Log::info('Update Daily Stats Listener', ['user' => $event->user]);

        // we will update the daily stats for the user
    }
}
