<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\Invitation;
use App\Models\InvitationVisit;
use App\Models\Theme;
use App\Models\User;
use App\Models\Order;
use App\Models\UserSubscription;
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

        // Phase 21: Subscription, Order & Revenue Metrics
        if ($isAdmin) {
            $stats['total_active_subscriptions'] = UserSubscription::where('status', 'active')->count();
            $stats['total_expired_subscriptions'] = UserSubscription::where('status', 'expired')->count();
            $stats['expired_this_month'] = UserSubscription::where('status', 'expired')
                ->whereYear('end_date', $now->year)
                ->whereMonth('end_date', $now->month)
                ->count();
            $stats['upcoming_expired'] = UserSubscription::where('status', 'active')
                ->whereBetween('end_date', [$now->toDateString(), $now->copy()->addDays(30)->toDateString()])
                ->count();

            $stats['total_orders'] = Order::count();
            $stats['pending_orders'] = Order::where('status', 'pending')->count();
            $stats['active_orders'] = Order::where('status', 'active')->count();
            $stats['expired_orders'] = Order::where('status', 'expired')->count();

            $stats['total_revenue'] = Order::whereIn('status', ['confirmed', 'active'])->sum('price');
            $stats['monthly_revenue'] = Order::whereIn('status', ['confirmed', 'active'])
                ->whereYear('created_at', $now->year)
                ->whereMonth('created_at', $now->month)
                ->sum('price');
        } else {
            $stats['total_active_subscriptions'] = 0;
            $stats['total_expired_subscriptions'] = 0;
            $stats['expired_this_month'] = 0;
            $stats['upcoming_expired'] = 0;
            $stats['total_orders'] = 0;
            $stats['pending_orders'] = 0;
            $stats['active_orders'] = 0;
            $stats['expired_orders'] = 0;
            $stats['total_revenue'] = 0;
            $stats['monthly_revenue'] = 0;
        }

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

        // Phase 21: Subscription Growth, Order Monthly, Revenue Monthly Charts
        $subscriptionGrowthLabels = [];
        $subscriptionGrowthSeries = [];
        $orderMonthlyLabels = [];
        $orderMonthlySeries = [];
        $revenueMonthlyLabels = [];
        $revenueMonthlySeries = [];

        if ($isAdmin) {
            for ($i = 5; $i >= 0; $i--) {
                $date = $now->copy()->subMonths($i);
                $label = $monthMap[$date->month] . ' ' . $date->year;

                $subscriptionGrowthLabels[] = $label;
                $subscriptionGrowthSeries[] = UserSubscription::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();

                $orderMonthlyLabels[] = $label;
                $orderMonthlySeries[] = Order::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();

                $revenueMonthlyLabels[] = $label;
                $revenueMonthlySeries[] = (float) Order::whereIn('status', ['confirmed', 'active'])
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('price');
            }
        }

        $chartData = [
            'invitations' => [
                'labels' => $invitationChartMonths,
                'series' => $invitationChartData,
            ],
            'visitors' => [
                'labels' => $visitorChartDays,
                'series' => $visitorChartData,
            ],
            'subscriptionGrowth' => [
                'labels' => $subscriptionGrowthLabels,
                'series' => $subscriptionGrowthSeries,
            ],
            'orderMonthly' => [
                'labels' => $orderMonthlySeries ? $orderMonthlyLabels : [],
                'series' => $orderMonthlySeries,
            ],
            'revenueMonthly' => [
                'labels' => $revenueMonthlySeries ? $revenueMonthlyLabels : [],
                'series' => $revenueMonthlySeries,
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
