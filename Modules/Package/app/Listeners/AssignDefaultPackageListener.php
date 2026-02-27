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
        private PackageRepositoryInterface      $packageRepository,
        private SubscriptionRepositoryInterface $subscriptionRepository,
    ) {}

    public function handle(UserCreated $event): void
    {
        $user = User::find($event->userId);

        if (!$user || !$user->isConsumer()) {
            return;
        }

        DB::transaction(function () use ($event) {
            $package = $this->packageRepository->findOrCreateDefault();

            $this->subscriptionRepository->create([
                'user_id'    => $event->userId,
                'package_id' => $package->id,
                'status'     => 'active',
                'started_at' => now(),
                'ended_at'   => null,
            ]);
        });
    }
}
