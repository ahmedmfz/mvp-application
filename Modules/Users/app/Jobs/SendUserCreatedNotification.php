<?php

namespace Modules\Users\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Users\Models\User;
use Illuminate\Support\Facades\Log;

class SendUserCreatedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly User $user) {}

    public function handle(): void
    {
        Log::info('User created : Send User Created Notification', ['user' => $this->user]);
        // $this->user->notify(new UserCreatedNotification($this->user));
    }
}
