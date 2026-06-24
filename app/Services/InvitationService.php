<?php

namespace App\Services;

use App\Models\Invitation;

/** Layanan untuk mengelola penerbitan dan siklus hidup undangan. */
class InvitationService
{
    /**
     * Menerbitkan (publish) undangan.
     */
    public function publishInvitation(Invitation $invitation): Invitation
    {
        $invitation->update([
            'status' => 'published',
        ]);

        return $invitation;
    }

    /**
     * Mengatur tanggal kedaluwarsa undangan secara manual.
     */
    public function setExpiredDate(Invitation $invitation, $expiredDate): Invitation
    {
        $invitation->update([
            'expired_at' => $expiredDate,
        ]);

        return $invitation;
    }

    /**
     * Menandai undangan sebagai berakhir (expired).
     */
    public function expireInvitation(Invitation $invitation): Invitation
    {
        $invitation->update([
            'status' => 'expired',
        ]);

        return $invitation;
    }
}
