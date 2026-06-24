<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use App\Models\UserSubscription;
use Carbon\Carbon;

/** Layanan untuk mengelola subscription user. */
class SubscriptionService
{
    /**
     * Membuat subscription baru untuk user berdasarkan order dan paket.
     */
    public function createSubscription(User $user, Order $order, ?Package $package, string $startDate, string $endDate): UserSubscription
    {
        return UserSubscription::create([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'package_id' => $package?->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
        ]);
    }

    /**
     * Memeriksa apakah user memiliki subscription aktif hari ini.
     */
    public function checkActive(User $user): bool
    {
        $today = Carbon::today();

        return $user->subscriptions()
            ->where('status', 'active')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->exists();
    }

    /**
     * Memperpanjang masa aktif subscription.
     */
    public function extendSubscription(UserSubscription $subscription, string $newEndDate): UserSubscription
    {
        $subscription->update([
            'end_date' => $newEndDate,
            'status' => 'active',
        ]);

        return $subscription;
    }

    /**
     * Menandai subscription sebagai berakhir (expired).
     */
    public function expireSubscription(UserSubscription $subscription): UserSubscription
    {
        $subscription->update([
            'status' => 'expired',
        ]);

        return $subscription;
    }
}
