<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOrderRequest;
use App\Http\Requests\Admin\UpdateOrderRequest;
use App\Models\Order;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Tampilkan daftar order (dengan search & filter).
     */
    public function index(Request $request)
    {
        $query = Order::query();

        // Search by order number, customer name, email, or phone
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $statuses = ['pending', 'follow_up', 'confirmed', 'active', 'expired', 'cancelled'];
            if (in_array($request->status, $statuses)) {
                $query->where('status', $request->status);
            }
        }

        // Eager load relationships and paginate
        $orders = $query->with(['user', 'package'])->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Fetch active packages for dropdown in forms
        $packages = \App\Models\Package::where('status', 'active')->orderBy('name')->get();

        return view('admin.orders.index', compact('orders', 'packages'));
    }

    /**
     * Simpan order baru ke database.
     */
    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();
        
        Order::create($data);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order baru berhasil dibuat.');
    }

    /**
     * Update data order di database.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $data = $request->validated();

        $order->update($data);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Data order berhasil diperbarui.');
    }

    /**
     * Hapus order dari database.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order berhasil dihapus.');
    }

    /**
     * Ubah status order secara cepat (misal confirm, cancel).
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:pending,follow_up,confirmed,active,expired,cancelled'],
        ], [
            'status.required' => 'Status wajib diisi.',
            'status.in' => 'Status tidak valid.',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Status order berhasil diperbarui.');
    }

    /**
     * Aktifkan order dengan menetapkan start_date dan end_date.
     */
    public function activate(Request $request, Order $order)
    {
        $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ], [
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'start_date.date' => 'Format tanggal mulai tidak valid.',
            'end_date.required' => 'Tanggal akhir wajib diisi.',
            'end_date.date' => 'Format tanggal akhir tidak valid.',
            'end_date.after_or_equal' => 'Tanggal akhir harus sama atau setelah tanggal mulai.',
        ]);

        $order->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'active',
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order ' . $order->order_number . ' telah diaktifkan.');
    }

    /**
     * Lakukan follow up pelanggan melalui WhatsApp.
     */
    public function followUp(Order $order)
    {
        // Ubah status ke follow_up jika sebelumnya pending
        if ($order->status === 'pending') {
            $order->update(['status' => 'follow_up']);
        }

        $message = "Halo " . $order->customer_name . ", kami dari Teman Seakad ingin menindaklanjuti pesanan Anda dengan nomor " . $order->order_number . ". Apakah ada yang bisa kami bantu untuk menyelesaikan pesanan Anda? Terima kasih.";
        
        $waUrl = 'https://api.whatsapp.com/send?phone=' . $order->formatted_phone . '&text=' . rawurlencode($message);

        return redirect()->away($waUrl);
    }

    /**
     * Buat akun user otomatis untuk pelanggan.
     */
    public function createUser(Order $order)
    {
        // Proteksi: jangan buat akun jika sudah terhubung
        if ($order->user_id) {
            return redirect()->route('admin.orders.index')
                ->with('error', 'Akun user sudah dibuat untuk order ini.');
        }

        // Cek apakah email sudah terdaftar di database
        $existingUser = User::where('email', $order->email)->first();
        if ($existingUser) {
            $order->update(['user_id' => $existingUser->id]);
            return redirect()->route('admin.orders.index')
                ->with('success', 'Email pelanggan sudah terdaftar. Order ini telah dihubungkan ke akun tersebut.');
        }

        // Generate password acak 10 karakter
        $password = Str::random(10);

        // Buat user baru
        $user = User::create([
            'name' => $order->customer_name,
            'email' => $order->email,
            'phone' => $order->phone,
            'password' => Hash::make($password),
            'status' => 'active',
        ]);

        // Sync ke role 'User'
        $userRole = Role::where('name', 'User')->first();
        if ($userRole) {
            $user->roles()->sync([$userRole->id]);
        }

        // Hubungkan order ke user
        $order->update(['user_id' => $user->id]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Akun user berhasil dibuat untuk ' . $order->customer_name)
            ->with('user_credentials', [
                'name' => $user->name,
                'email' => $user->email,
                'password' => $password,
                'phone' => $order->formatted_phone,
                'order_number' => $order->order_number,
            ]);
    }
}
