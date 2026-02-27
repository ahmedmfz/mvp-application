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

    
    public function subscribe(int $userId, int $packageId): array
    {
        return DB::transaction(function () use ($userId, $packageId) {
            // Check for an existing active subscription
            $current = $this->subscriptionRepository->getActiveSubscription($userId);

            // If the user is already subscribed to the same package, do nothing
            if ($current && (int) $current->package_id === $packageId) {
                return [
                    'subscription' => $current,
                    'already_active' => true,
                ];
            }

            // Cancel the current active subscription if it's a different package
            if ($current) {
                $this->subscriptionRepository->cancelSubscription($current);
            }

            // Create the new subscription
            $subscription = $this->subscriptionRepository->create([
                'user_id'    => $userId,
                'package_id' => $packageId,
                'status'     => 'active',
                'started_at' => now(),
                'ended_at'   => null,
            ]);

            return [
                'subscription'   => $subscription,
                'already_active' => false,
            ];
        });
    }

    public function getHistory(int $userId)
    {
        return $this->subscriptionRepository->getUserHistory($userId);
    }
}
