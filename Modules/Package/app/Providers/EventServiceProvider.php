<?php

namespace Modules\Package\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Package\Listeners\AssignDefaultPackageListener;
use Modules\User\Events\UserCreated;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserCreated::class => [
            AssignDefaultPackageListener::class,
        ],
    ];

    protected static $shouldDiscoverEvents = true;

    protected function configureEmailVerification(): void {}
}
