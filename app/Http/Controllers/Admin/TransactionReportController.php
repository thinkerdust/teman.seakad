<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Package;
use Illuminate\Http\Request;

class TransactionReportController extends Controller
{
    /**
     * Tampilkan halaman laporan transaksi dengan berbagai filter.
     */
    public function index(Request $request)
    {
        $query = Order::query();

        // Filter: Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        // Filter: Date Range (Start Date)
        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('start_date', '<=', $request->date_to);
        }

        // Filter: Package
        if ($request->filled('package_id')) {
            $query->where('package_id', $request->package_id);
        }

        // Filter: Status
        if ($request->filled('status')) {
            $statuses = ['pending', 'follow_up', 'confirmed', 'active', 'expired', 'cancelled'];
            if (in_array($request->status, $statuses)) {
                $query->where('status', $request->status);
            }
        }

        // Filter: Active / Expired Period
        if ($request->filled('period')) {
            $today = now()->toDateString();
            if ($request->period === 'active') {
                $query->whereNotNull('start_date')
                    ->whereNotNull('end_date')
                    ->whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today);
            } elseif ($request->period === 'expired') {
                $query->whereNotNull('end_date')
                    ->whereDate('end_date', '<', $today);
            }
        }

        // Eager load package and user, sort by latest orders, and paginate
        $orders = $query->with(['package', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Ambil semua paket untuk pilihan dropdown filter
        $packages = Package::orderBy('name')->get();

        return view('admin.reports.transactions', compact('orders', 'packages'));
    }
}
