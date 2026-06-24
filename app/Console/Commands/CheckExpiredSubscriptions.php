<?php

namespace App\Console\Commands;

use App\Models\Invitation;
use App\Models\UserSubscription;
use App\Services\InvitationService;
use App\Services\SubscriptionService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

/** Command untuk memeriksa dan menandai subscription dan invitation yang kedaluwarsa. */
class CheckExpiredSubscriptions extends Command
{
    /**
     * Nama dan signature dari console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check-expired';

    /**
     * Deskripsi console command.
     *
     * @var string
     */
    protected $description = 'Periksa dan tandai subscription dan invitation yang melewati tanggal kedaluwarsa';

    protected SubscriptionService $subscriptionService;

    protected InvitationService $invitationService;

    /**
     * Membuat instance command baru.
     */
    public function __construct(SubscriptionService $subscriptionService, InvitationService $invitationService)
    {
        parent::__construct();
        $this->subscriptionService = $subscriptionService;
        $this->invitationService = $invitationService;
    }

    /**
     * Eksekusi console command.
     */
    public function handle(): void
    {
        $today = Carbon::today();

        // 1. Dapatkan semua subscription aktif yang tanggal selesainya sebelum hari ini
        $expiredSubscriptions = UserSubscription::where('status', 'active')
            ->where('end_date', '<', $today)
            ->get();

        $expiredSubCount = 0;
        $expiredInvCount = 0;

        foreach ($expiredSubscriptions as $subscription) {
            $this->subscriptionService->expireSubscription($subscription);
            $expiredSubCount++;

            // Dapatkan semua undangan published milik user yang bersangkutan
            $userInvitations = Invitation::where('user_id', $subscription->user_id)
                ->where('status', 'published')
                ->get();

            foreach ($userInvitations as $invitation) {
                $this->invitationService->expireInvitation($invitation);
                $expiredInvCount++;
            }
        }

        // 2. Dapatkan juga undangan published lain yang expired_at-nya sebelum hari ini
        // (yang belum diperbarui di atas)
        $expiredInvitations = Invitation::where('status', 'published')
            ->where('expired_at', '<', $today)
            ->get();

        foreach ($expiredInvitations as $invitation) {
            $this->invitationService->expireInvitation($invitation);
            $expiredInvCount++;
        }

        $this->info("Berhasil memperbarui {$expiredSubCount} subscription menjadi expired.");
        $this->info("Berhasil memperbarui {$expiredInvCount} invitation menjadi expired.");
    }
}
