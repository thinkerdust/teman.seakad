<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Controller untuk mengelola notifikasi user di admin panel. */
class NotificationController extends Controller
{
    /**
     * Tampilkan semua notifikasi user (paginated).
     */
    public function index(Request $request): View
    {
        $notifications = $request->user()
            ->notifications()
            ->paginate(15);

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca.
     */
    public function markAsRead(string $id): RedirectResponse
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return redirect()->back()->with('success', 'Notifikasi berhasil ditandai sebagai dibaca.');
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca.
     */
    public function markAllAsRead(): RedirectResponse
    {
        auth()->user()
            ->unreadNotifications
            ->markAsRead();

        return redirect()->back()->with('success', 'Semua notifikasi berhasil ditandai sebagai dibaca.');
    }
}
