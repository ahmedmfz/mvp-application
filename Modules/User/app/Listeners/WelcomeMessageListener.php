<?php

namespace Modules\User\Listeners;

use Modules\User\Events\UserCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;


class WelcomeMessageListener implements ShouldQueue
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
        Log::info('Welcome Message Listener', ['user' => $event->userId]);

        // we will send a welcome message to the user
    }
}
