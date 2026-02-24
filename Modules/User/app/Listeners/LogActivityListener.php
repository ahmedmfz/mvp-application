<?php

namespace Modules\User\Listeners;

use Modules\User\Events\UserCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogActivityListener implements ShouldQueue
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
        Log::info('Log Activity Listener', ['user' => $event->user]);

        // we will create a log activity for the user
    }
}
