<?php

namespace Modules\Users\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Users\Events\UserCreated;
use Modules\Users\Listeners\LogActivityListener;
use Modules\Users\Listeners\UpdateDailyStatsListener;
use Modules\Users\Listeners\WelcomeMessageListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        UserCreated::class => [
            LogActivityListener::class,
            UpdateDailyStatsListener::class,
            WelcomeMessageListener::class,
        ]
    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void {}
}
