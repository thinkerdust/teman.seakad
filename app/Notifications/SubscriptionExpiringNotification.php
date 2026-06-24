<?php

namespace App\Notifications;

use App\Models\UserSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/** Notifikasi pengingat masa aktif langganan akan berakhir. */
class SubscriptionExpiringNotification extends Notification
{
    use Queueable;

    protected UserSubscription $subscription;

    protected int $daysRemaining;

    /**
     * Buat instance notifikasi baru.
     */
    public function __construct(UserSubscription $subscription, int $daysRemaining)
    {
        $this->subscription = $subscription;
        $this->daysRemaining = $daysRemaining;
    }

    /**
     * Tentukan channel notifikasi yang dikirim.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Dapatkan representasi email dari notifikasi.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reminder: Langganan Anda Akan Berakhir')
            ->view('emails.subscription-expiring', [
                'name' => $notifiable->name,
                'daysRemaining' => $this->daysRemaining,
                'endDate' => $this->subscription->end_date->format('d-m-Y'),
            ]);
    }

    /**
     * Dapatkan representasi array database dari notifikasi.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Reminder: Masa Aktif Langganan',
            'message' => 'Subscription Anda akan berakhir pada tanggal '.$this->subscription->end_date->format('d-m-Y'),
            'type' => 'subscription_expiring',
            'days_remaining' => $this->daysRemaining,
            'subscription_id' => $this->subscription->id,
        ];
    }
}
