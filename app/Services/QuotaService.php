<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

/** Layanan untuk mengelola kuota undangan user. */
class QuotaService
{
    /**
     * Memeriksa apakah user memiliki kuota tersisa untuk membuat undangan baru.
     */
    public function checkQuota(User $user): bool
    {
        // Bypass pemeriksaan untuk admin, superadmin, dan email utama
        if ($user->email === 'admin@teman-seakad.com' || $user->hasRole('Superadmin') || $user->hasRole('Admin')) {
            return true;
        }

        $today = Carbon::today();
        $activeSub = $user->subscriptions()
            ->where('status', 'active')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->first();

        if (! $activeSub) {
            return false;
        }

        $totalQuota = $activeSub->order ? $activeSub->order->quota : 0;
        $createdCount = $user->invitations()->count();

        return $createdCount < $totalQuota;
    }

    /**
     * Mengonsumsi kuota user (memvalidasi ketersediaan kuota).
     */
    public function consumeQuota(User $user): bool
    {
        return $this->checkQuota($user);
    }
}
