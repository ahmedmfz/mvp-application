<?php

namespace Modules\Message\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Modules\Message\Services\MessageService;
use Modules\User\Events\UserCreated; 

class WelcomeMessageListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        private MessageService $messages
    ) {}

    public function handle(UserCreated $event): void
    {
        $this->messages->createWelcomeMessage($event->userId);

        Log::info('Welcome message simulated', [
            'user_id' => $event->userId,
            'type' => 'EMAIL',
            'status' => 'SENT',
        ]);
    }
}