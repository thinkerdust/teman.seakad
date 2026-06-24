<?php

namespace App\Http\View\Composers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/** Menyediakan data notifikasi unread untuk layout header admin. */
class NotificationComposer
{
    /**
     * Bind data ke view.
     */
    public function compose(View $view): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Ambil 5 notifikasi unread terbaru
            $notifications = $user->unreadNotifications()->limit(5)->get();
            $unreadNotificationCount = $user->unreadNotifications()->count();
        } else {
            $notifications = collect();
            $unreadNotificationCount = 0;
        }

        $view->with([
            'notifications' => $notifications,
            'unreadNotificationCount' => $unreadNotificationCount,
        ]);
    }
}
