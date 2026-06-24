<?php

namespace App\Console\Commands;

use App\Models\UserSubscription;
use App\Notifications\SubscriptionExpiringNotification;
use Illuminate\Console\Command;

/** Command untuk mengirim notifikasi pengingat masa aktif langganan. */
class SendSubscriptionReminders extends Command
{
    /**
     * Nama dan signature dari console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:send-reminders';

    /**
     * Deskripsi console command.
     *
     * @var string
     */
    protected $description = 'Kirim notifikasi pengingat sebelum masa aktif langganan berakhir (H-30, H-7, H-1)';

    /**
     * Eksekusi console command.
     */
    public function handle(): void
    {
        $h30 = today()->addDays(30)->toDateString();
        $h7 = today()->addDays(7)->toDateString();
        $h1 = today()->addDays(1)->toDateString();

        $subscriptions = UserSubscription::where('status', 'active')
            ->where(function ($query) use ($h30, $h7, $h1) {
                $query->whereDate('end_date', $h30)
                    ->orWhereDate('end_date', $h7)
                    ->orWhereDate('end_date', $h1);
            })
            ->with('user')
            ->get();

        $count = 0;

        foreach ($subscriptions as $subscription) {
            $user = $subscription->user;
            if (! $user) {
                continue;
            }

            $daysRemaining = (int) today()->diffInDays($subscription->end_date, false);

            if (! in_array($daysRemaining, [30, 7, 1])) {
                continue;
            }

            // Mencegah duplikasi notifikasi untuk user + subscription + days_remaining yang sama
            $exists = $user->notifications()
                ->where('data->subscription_id', $subscription->id)
                ->where('data->days_remaining', $daysRemaining)
                ->exists();

            if (! $exists) {
                $user->notify(new SubscriptionExpiringNotification($subscription, $daysRemaining));
                $count++;
            }
        }

        $this->info("Berhasil mengirim {$count} notifikasi pengingat langganan.");
    }
}
