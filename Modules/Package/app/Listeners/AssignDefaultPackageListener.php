<?php

namespace Modules\Package\Listeners;

use Illuminate\Support\Facades\DB;
use Modules\Package\Repositories\PackageRepositoryInterface;
use Modules\Package\Repositories\SubscriptionRepositoryInterface;
use Modules\User\Events\UserCreated;
use Modules\User\Models\User;

class AssignDefaultPackageListener
{
    public function __construct(
        private PackageRepositoryInterface  $packageRepository
    ) {}

    public function handle(UserCreated $event): void
    {
        $package = $this->packageRepository->findOrCreateDefault();
    }
}
