<?php

namespace Modules\Package\Services;

use Illuminate\Support\Facades\DB;
use Modules\Package\Models\UserSubscription;
use Modules\Package\Repositories\SubscriptionRepositoryInterface;

class SubscriptionService
{
    public function __construct(
        public SubscriptionRepositoryInterface $subscriptionRepository
    ) {}

    /**
     * Subscribe a user to a package.
     * Any currently active subscription is cancelled first (history preserved).
     */
    public function subscribe(int $userId, int $packageId): UserSubscription
    {
        return DB::transaction(function () use ($userId, $packageId) {
            // Cancel the current active subscription if one exists
            $current = $this->subscriptionRepository->getActiveSubscription($userId);

            if ($current) {
                $this->subscriptionRepository->cancelSubscription($current);
            }

            // Create the new subscription
            return $this->subscriptionRepository->create([
                'user_id'    => $userId,
                'package_id' => $packageId,
                'status'     => 'active',
                'started_at' => now(),
                'ended_at'   => null,
            ]);
        });
    }

    /**
     * Return the full subscription history for a user (newest first).
     */
    public function getHistory(int $userId)
    {
        return $this->subscriptionRepository->getUserHistory($userId);
    }
}
