<?php

namespace Modules\Users\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Users\Models\User;
use Illuminate\Support\Facades\Log;

class UpdateDailyOnboardingStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly User $user) {}

    public function handle(): void
    {
        Log::info('User created : Update Daily Onboarding Stats', ['user' => $this->user]);
        // Update daily onboarding statistics here
    }
}
