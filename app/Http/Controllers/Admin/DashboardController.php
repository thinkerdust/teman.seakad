<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\Invitation;
use App\Models\InvitationVisit;
use App\Models\Theme;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman utama dashboard dengan metrik dan grafik dinamis.
     */
    public function index()
    {
        $user = auth()->user();
        $isAdmin = $user->hasPermission('user.view');

        $now = Carbon::now();
        $currentMonthStart = $now->copy()->startOfMonth();
        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();

        // 1. Calculate stats and counts based on user permissions (RBAC)
        if ($isAdmin) {
            // Admin/Superadmin: Global stats
            $totalUsers = User::count();
            $totalInvitations = Invitation::count();
            $totalThemes = Theme::count();
            $totalGuests = Guest::count();

            // Calculate trends vs last month
            $currentMonthUsers = User::whereBetween('created_at', [$currentMonthStart, $now])->count();
            $lastMonthUsers = User::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
            $userTrend = $this->getMonthlyTrend($currentMonthUsers, $lastMonthUsers);

            $currentMonthInvitations = Invitation::whereBetween('created_at', [$currentMonthStart, $now])->count();
            $lastMonthInvitations = Invitation::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
            $invitationTrend = $this->getMonthlyTrend($currentMonthInvitations, $lastMonthInvitations);

            $currentMonthGuests = Guest::whereBetween('created_at', [$currentMonthStart, $now])->count();
            $lastMonthGuests = Guest::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
            $guestTrend = $this->getMonthlyTrend($currentMonthGuests, $lastMonthGuests);
        } else {
            // Regular User: User-owned stats
            $totalUsers = 0; // Not displayed
            $totalInvitations = Invitation::where('user_id', $user->id)->count();
            $totalThemes = Theme::where('status', 'active')->count();
            $totalGuests = Guest::whereHas('invitation', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count();

            // Calculate trends vs last month
            $userTrend = '0%';
            
            $currentMonthInvitations = Invitation::where('user_id', $user->id)
                ->whereBetween('created_at', [$currentMonthStart, $now])
                ->count();
            $lastMonthInvitations = Invitation::where('user_id', $user->id)
                ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
                ->count();
            $invitationTrend = $this->getMonthlyTrend($currentMonthInvitations, $lastMonthInvitations);

            $currentMonthGuests = Guest::whereHas('invitation', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->whereBetween('created_at', [$currentMonthStart, $now])->count();
            $lastMonthGuests = Guest::whereHas('invitation', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
            $guestTrend = $this->getMonthlyTrend($currentMonthGuests, $lastMonthGuests);
        }

        $stats = [
            'total_users' => $totalUsers,
            'total_invitations' => $totalInvitations,
            'total_themes' => $totalThemes,
            'total_guests' => $totalGuests,
            'user_trend' => $userTrend,
            'invitation_trend' => $invitationTrend,
            'guest_trend' => $guestTrend,
        ];

        // 2. Prepare visual charts data
        $monthMap = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        // Invitation Created per Month (Last 6 Months)
        $invitationChartMonths = [];
        $invitationChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $invitationChartMonths[] = $monthMap[$date->month] . ' ' . $date->year;

            $query = Invitation::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month);

            if (!$isAdmin) {
                $query->where('user_id', $user->id);
            }

            $invitationChartData[] = $query->count();
        }

        // Daily Visitor Statistics (Last 14 Days)
        $visitorChartDays = [];
        $visitorChartData = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $visitorChartDays[] = $date->day . ' ' . $monthMap[$date->month];

            $query = InvitationVisit::whereDate('created_at', $date->toDateString());

            if (!$isAdmin) {
                $query->whereHas('invitation', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }

            $visitorChartData[] = $query->count();
        }

        $chartData = [
            'invitations' => [
                'labels' => $invitationChartMonths,
                'series' => $invitationChartData,
            ],
            'visitors' => [
                'labels' => $visitorChartDays,
                'series' => $visitorChartData,
            ]
        ];

        return view('admin.dashboard', compact('stats', 'chartData'));
    }

    /**
     * Hitung persentase perbedaan tren bulanan.
     */
    private function getMonthlyTrend(int $currentCount, int $previousCount): string
    {
        if ($previousCount === 0) {
            return $currentCount > 0 ? '+100%' : '0%';
        }

        $diff = (($currentCount - $previousCount) / $previousCount) * 100;
        return ($diff >= 0 ? '+' : '') . round($diff, 1) . '%';
    }
}
