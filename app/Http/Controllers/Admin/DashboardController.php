<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman utama dashboard.
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_invitations' => 0, // Placeholder untuk fase berikutnya
            'total_themes' => 0,      // Placeholder untuk fase berikutnya
            'total_guests' => 0,      // Placeholder untuk fase berikutnya
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
