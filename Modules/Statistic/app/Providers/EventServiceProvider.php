<?php

namespace Modules\Statistic\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\User\Events\UserCreated;
use Modules\User\Events\UserUpdated;
use Modules\User\Events\UserDeleted;
use Modules\Statistic\Listeners\UpdateDailyStatsListener;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        UserCreated::class => [UpdateDailyStatsListener::class],
        UserUpdated::class => [UpdateDailyStatsListener::class],
        UserDeleted::class => [UpdateDailyStatsListener::class],
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
