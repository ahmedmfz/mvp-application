<?php

namespace Modules\Package\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Package\Models\UserSubscription;

interface SubscriptionRepositoryInterface
{
    public function getActiveSubscription(int $userId): ?UserSubscription;

    public function getUserHistory(int $userId): Collection;

    public function cancelSubscription(UserSubscription $subscription): void;

    public function create(array $data): UserSubscription;
}
