<?php

namespace Modules\Activity\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\User\Events\UserCreated;
use Modules\User\Events\UserUpdated;
use Modules\User\Events\UserDeleted;
use Modules\Activity\Listeners\LogUserActivityListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        UserCreated::class => [
           LogUserActivityListener::class,
        ],
        UserUpdated::class => [
            LogUserActivityListener::class,
        ],
        UserDeleted::class => [
           LogUserActivityListener::class,
        ],  
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
