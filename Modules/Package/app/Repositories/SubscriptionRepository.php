<?php

namespace Modules\Package\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Package\Models\UserSubscription;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function getActiveSubscription(int $userId): ?UserSubscription
    {
        return UserSubscription::where('user_id', $userId)
            ->where('status', 'active')
            ->with('package')
            ->latest()
            ->first();
    }

    public function getUserHistory(int $userId): Collection
    {
        return UserSubscription::where('user_id', $userId)
            ->with('package')
            ->latest('started_at')
            ->get();
    }

    public function cancelSubscription(UserSubscription $subscription): void
    {
        $subscription->update([
            'status'   => 'cancelled',
            'ended_at' => now(),
        ]);
    }

    public function create(array $data): UserSubscription
    {
        return UserSubscription::create($data);
    }
}
