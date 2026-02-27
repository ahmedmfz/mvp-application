<?php

namespace Modules\Message\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Message\Services\SmsService;
use Modules\User\Events\UserCreated;
use Modules\User\Models\User;

class SendWelcomeSmsListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        private SmsService $smsService
    ) {}

    public function handle(UserCreated $event): void
    {
        $user = User::find($event->userId);

        if (! $user) {
            return;
        }

        $phone = (string) ($user->phone ?? '0555555555555');
      
        $message = "Welcome {$user->name}! Your account has been created successfully.";

        $result = $this->smsService->send($phone, $message);

        if (! $result->success) {
            throw new \RuntimeException("SMS failed via {$result->provider}: {$result->error}");
        }
    }
}
