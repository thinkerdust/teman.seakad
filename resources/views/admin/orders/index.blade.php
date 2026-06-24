@extends('admin.layouts.app')

@section('title', 'Order Management')

@section('content')
    <!-- Breadcrumb -->
    <x-admin.breadcrumb pageTitle="Order Management" :items="['Order Management' => '']" />

    <!-- Main Container -->
    <div 
        x-data="ordersManager({
            hasErrors: @json($errors->any()),
            oldId: '{{ old('id', '') }}',
            oldCustomerName: '{{ old('customer_name', '') }}',
            oldPhone: '{{ old('phone', '') }}',
            oldEmail: '{{ old('email', '') }}',
            oldPackageId: '{{ old('package_id', '') }}',
            oldQuota: '{{ old('quota', 1) }}',
            oldPrice: '{{ old('price', 0) }}',
            oldStatus: '{{ old('status', 'pending') }}',
            oldStartDate: '{{ old('start_date', '') }}',
            oldEndDate: '{{ old('end_date', '') }}',
            oldNotes: '{{ old('notes', '') }}',
            packages: @json($packages)
        })"
    >
        <!-- Table & Filter Card -->
        <div id="orders-table-container">
            <x-admin.card>
                <x-slot:header>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                        Daftar Pesanan
                    </h3>
                    
                    @if(auth()->user()->hasPermission('order.create'))
                    <button 
                        @click="createModalOpen = true"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition duration-150"
                    >
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Order
                    </button>
                    @endif
                </div>
            </x-slot:header>

            <!-- Filters -->
            <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <!-- Search Form -->
                <form action="{{ route('admin.orders.index') }}" method="GET" class="flex-grow max-w-md">
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Cari no. order, pelanggan, email..."
                            class="w-full rounded-xl border border-slate-200 bg-transparent py-2.5 pl-10 pr-4 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        @if(request('search') || request('status'))
                            <a href="{{ route('admin.orders.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-rose-500 hover:underline">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Status Filter Dropdown -->
                <form id="filter-form" action="{{ route('admin.orders.index') }}" method="GET" class="flex items-center gap-3">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    <label for="status-filter" class="text-sm font-medium text-slate-500 dark:text-slate-400">Status:</label>
                    <select 
                        id="status-filter" 
                        name="status" 
                        onchange="document.getElementById('filter-form').submit()"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-900 dark:text-white"
                    >
                        <option value="">Semua</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="follow_up" {{ request('status') == 'follow_up' ? 'selected' : '' }}>Follow Up</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </form>
            </div>

            <!-- Datatable -->
            <div class="overflow-x-auto rounded-xl border border-slate-100 dark:border-slate-850">
                <table class="w-full border-collapse text-left text-sm text-slate-600 dark:text-slate-400">
                    <thead class="bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wider dark:bg-slate-900/50 dark:text-slate-400">
                        <tr>
                            <th class="px-6 py-4">Nomor Order</th>
                            <th class="px-6 py-4">Pelanggan</th>
                            <th class="px-6 py-4">Paket & Kuota</th>
                            <th class="px-6 py-4">Harga</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4">Masa Aktif</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/20">
                                <td class="px-6 py-4 font-semibold text-slate-800 dark:text-white">
                                    <button 
                                        @click="showDetail({{ json_encode($order->load('user')) }})"
                                        class="hover:text-indigo-600 hover:underline text-left focus:outline-none"
                                    >
                                        {{ $order->order_number }}
                                    </button>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800 dark:text-white">{{ $order->customer_name }}</div>
                                    <div class="text-xs text-slate-400">{{ $order->email }}</div>
                                    <div class="text-xs text-slate-400">{{ $order->phone }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-800 dark:text-white">
                                        {{ $order->package ? $order->package->name : 'Custom / Tanpa Paket' }}
                                    </div>
                                    <div class="text-xs text-slate-400">Kuota: {{ $order->quota }} Undangan</div>
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-800 dark:text-white">
                                    Rp {{ number_format($order->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($order->status === 'pending')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-semibold text-blue-700 dark:bg-blue-950/30 dark:text-blue-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-blue-600 dark:bg-blue-400"></span>
                                            Pending
                                        </span>
                                    @elseif($order->status === 'follow_up')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-semibold text-amber-700 dark:bg-amber-950/30 dark:text-amber-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-600 dark:bg-amber-400"></span>
                                            Follow Up
                                        </span>
                                    @elseif($order->status === 'confirmed')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-50 px-2.5 py-0.5 text-xs font-semibold text-purple-700 dark:bg-purple-950/30 dark:text-purple-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-purple-600 dark:bg-purple-400"></span>
                                            Confirmed
                                        </span>
                                    @elseif($order->status === 'active')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-600 dark:bg-emerald-400"></span>
                                            Active
                                        </span>
                                    @elseif($order->status === 'expired')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-slate-400 dark:bg-slate-500"></span>
                                            Expired
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-semibold text-rose-700 dark:bg-rose-950/30 dark:text-rose-400">
                                            <span class="h-1.5 w-1.5 rounded-full bg-rose-600 dark:bg-rose-400"></span>
                                            Cancelled
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400">
                                    @if($order->start_date && $order->end_date)
                                        <div>Mulai: {{ $order->start_date->format('d M Y') }}</div>
                                        <div>Akhir: {{ $order->end_date->format('d M Y') }}</div>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Actions dropdown/buttons -->
                                        @if(auth()->user()->hasPermission('order.update'))
                                            <!-- Follow Up WhatsApp -->
                                            <a 
                                                href="{{ route('admin.orders.follow-up', $order) }}" 
                                                target="_blank"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-emerald-600 hover:bg-emerald-50 dark:border-slate-800 dark:bg-slate-900 dark:hover:bg-slate-800"
                                                title="Hubungi WhatsApp"
                                            >
                                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                </svg>
                                            </a>

                                            <!-- Confirm order (Pending/Follow up -> Confirmed) -->
                                            @if(in_array($order->status, ['pending', 'follow_up']))
                                                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="confirmed">
                                                    <button 
                                                        type="submit"
                                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-purple-600 hover:bg-purple-50 dark:border-slate-800 dark:bg-slate-900 dark:hover:bg-slate-800"
                                                        title="Setujui Order"
                                                    >
                                                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Activate Order (Confirmed -> Active) -->
                                            @if($order->status === 'confirmed')
                                                <button 
                                                    @click="openActivate({{ json_encode($order) }})"
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-emerald-600 hover:bg-emerald-50 dark:border-slate-800 dark:bg-slate-900 dark:hover:bg-slate-800"
                                                    title="Aktifkan Masa Layanan"
                                                >
                                                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </button>
                                            @endif

                                            <!-- Create User Account -->
                                            @if(!$order->user_id && auth()->user()->hasPermission('user.create'))
                                                <form action="{{ route('admin.orders.create-user', $order) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button 
                                                        type="submit"
                                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-indigo-650 hover:bg-indigo-50 dark:border-slate-800 dark:bg-slate-900 dark:hover:bg-slate-800"
                                                        title="Buat Akun User"
                                                    >
                                                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Edit Details -->
                                            <button 
                                                @click="editOrder({{ json_encode($order) }})"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800"
                                                title="Edit Data Pesanan"
                                            >
                                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>
                                        @endif

                                        <!-- Delete -->
                                        @if(auth()->user()->hasPermission('order.delete'))
                                            <button 
                                                @click="confirmDelete({{ json_encode($order) }})"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-100 bg-white text-rose-600 hover:bg-rose-50 dark:border-rose-950/20 dark:bg-slate-900 dark:text-rose-400 dark:hover:bg-rose-950/40"
                                                title="Hapus Pesanan"
                                            >
                                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-slate-400 dark:text-slate-500">
                                    Tidak ada data pesanan yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </x-admin.card>
    </div>

    <!-- Modals -->
    <!-- 1. Detail Order Modal -->
    <div 
        x-show="detailModalOpen"
        class="fixed inset-0 z-999 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        x-transition
        x-cloak
    >
        <div 
            @click.outside="detailModalOpen = false"
            class="w-full max-w-2xl rounded-2xl bg-white p-6 shadow-2xl dark:bg-slate-950 border border-slate-200 dark:border-slate-800"
        >
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4 mb-4">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                    Detail Pesanan: <span x-text="selectedOrder.order_number" class="text-indigo-600 dark:text-indigo-400"></span>
                </h3>
                <button @click="detailModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-6">
                <div>
                    <h4 class="font-bold text-xs text-slate-400 uppercase tracking-wider mb-2">Informasi Pelanggan</h4>
                    <div class="space-y-2 text-slate-700 dark:text-slate-350">
                        <div>Nama: <span x-text="selectedOrder.customer_name" class="font-semibold text-slate-800 dark:text-white"></span></div>
                        <div>Email: <span x-text="selectedOrder.email"></span></div>
                        <div>Telepon: <span x-text="selectedOrder.phone"></span></div>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold text-xs text-slate-400 uppercase tracking-wider mb-2">Detail Paket</h4>
                    <div class="space-y-2 text-slate-700 dark:text-slate-350">
                        <div>Paket: <span x-text="selectedOrder.package ? selectedOrder.package.name : (selectedOrder.package_id ? 'Paket ID: ' + selectedOrder.package_id : 'Custom / Tanpa Paket')" class="font-semibold text-slate-800 dark:text-white"></span></div>
                        <div>Kuota Undangan: <span x-text="selectedOrder.quota"></span></div>
                        <div>Total Harga: <span x-text="'Rp ' + Number(selectedOrder.price).toLocaleString('id-ID')" class="font-semibold text-slate-800 dark:text-white"></span></div>
                    </div>
                </div>
                <div class="md:col-span-2 border-t border-slate-100 dark:border-slate-800 pt-4">
                    <h4 class="font-bold text-xs text-slate-400 uppercase tracking-wider mb-2">Status & Tanggal Aktif</h4>
                    <div class="grid grid-cols-2 gap-2 text-slate-700 dark:text-slate-350">
                        <div>Status: 
                            <span 
                                x-text="selectedOrder.status ? selectedOrder.status.toUpperCase() : ''" 
                                class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold"
                                :class="{
                                    'bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-400': selectedOrder.status === 'pending',
                                    'bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-400': selectedOrder.status === 'follow_up',
                                    'bg-purple-50 text-purple-700 dark:bg-purple-950/30 dark:text-purple-400': selectedOrder.status === 'confirmed',
                                    'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400': selectedOrder.status === 'active',
                                    'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400': selectedOrder.status === 'expired',
                                    'bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-400': selectedOrder.status === 'cancelled',
                                }"
                            ></span>
                        </div>
                        <div>Terdaftar Pada: <span x-text="selectedOrder.created_at ? new Date(selectedOrder.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}) : '-'"></span></div>
                        <div>Tanggal Mulai: <span x-text="selectedOrder.start_date ? new Date(selectedOrder.start_date).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}) : '-'" class="font-semibold"></span></div>
                        <div>Tanggal Berakhir: <span x-text="selectedOrder.end_date ? new Date(selectedOrder.end_date).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}) : '-'" class="font-semibold"></span></div>
                    </div>
                </div>
                <div class="md:col-span-2 border-t border-slate-100 dark:border-slate-800 pt-4" x-show="selectedOrder.notes">
                    <h4 class="font-bold text-xs text-slate-400 uppercase tracking-wider mb-1">Catatan Tambahan</h4>
                    <p class="text-slate-700 dark:text-slate-350 bg-slate-50 dark:bg-slate-900/40 p-2.5 rounded-lg border border-slate-100 dark:border-slate-800" x-text="selectedOrder.notes"></p>
                </div>
                <div class="md:col-span-2 border-t border-slate-100 dark:border-slate-800 pt-4">
                    <h4 class="font-bold text-xs text-slate-400 uppercase tracking-wider mb-2">Akun Pengguna Terhubung</h4>
                    <template x-if="selectedOrder.user">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-xs">
                                <span x-text="selectedOrder.user.name.substring(0, 2).toUpperCase()"></span>
                            </div>
                            <div>
                                <div class="font-semibold text-slate-850 dark:text-white" x-text="selectedOrder.user.name"></div>
                                <div class="text-xs text-slate-400" x-text="selectedOrder.user.email"></div>
                            </div>
                        </div>
                    </template>
                    <template x-if="!selectedOrder.user">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-slate-50 dark:bg-slate-900/20 p-3 rounded-xl border border-dashed border-slate-200 dark:border-slate-800">
                            <span class="text-xs text-slate-500">Belum ada akun user yang dikaitkan dengan pesanan ini.</span>
                            @if(auth()->user()->hasPermission('user.create'))
                                <form :action="'/admin/orders/' + selectedOrder.id + '/create-user'" method="POST" class="inline">
                                    @csrf
                                    <button 
                                        type="submit"
                                        class="text-xs font-semibold rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-1.5 shadow-sm transition"
                                    >
                                        Buat Akun Sekarang
                                    </button>
                                </form>
                            @endif
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100 dark:border-slate-800">
                <button 
                    @click="detailModalOpen = false" 
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800"
                >
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- 2. Tambah Order Modal -->
    <div 
        x-show="createModalOpen"
        class="fixed inset-0 z-999 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        x-transition
        x-cloak
    >
        <div 
            @click.outside="closeCreateModal()"
            class="w-full max-w-xl rounded-2xl bg-white p-6 shadow-2xl dark:bg-slate-950 border border-slate-200 dark:border-slate-800"
        >
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4 mb-4">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Tambah Pesanan Baru</h3>
                <button @click="closeCreateModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="create-order-form" action="{{ route('admin.orders.store') }}" method="POST" @submit.prevent="submitForm($event, 'create')">
                @csrf
                <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-1">
                    <!-- Customer Name -->
                    <div>
                        <label for="create_customer_name" class="mb-2.5 block text-sm font-semibold text-slate-800 dark:text-white">Nama Pelanggan <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            id="create_customer_name" 
                            name="customer_name"
                            value="{{ old('customer_name') }}"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                        <template x-if="errors.customer_name">
                            <span class="text-xs text-rose-500 mt-1 block" x-text="errors.customer_name[0]"></span>
                        </template>
                    </div>

                    <!-- Email & Phone -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="create_email" class="mb-2.5 block text-sm font-semibold text-slate-800 dark:text-white">Email <span class="text-rose-500">*</span></label>
                            <input 
                                type="email" 
                                id="create_email" 
                                name="email"
                                value="{{ old('email') }}"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <template x-if="errors.email">
                                <span class="text-xs text-rose-500 mt-1 block" x-text="errors.email[0]"></span>
                            </template>
                        </div>
                        <div>
                            <label for="create_phone" class="mb-2.5 block text-sm font-semibold text-slate-800 dark:text-white">Nomor Telepon <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                id="create_phone" 
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="Contoh: 08123456789"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <template x-if="errors.phone">
                                <span class="text-xs text-rose-500 mt-1 block" x-text="errors.phone[0]"></span>
                            </template>
                        </div>
                    </div>

                    <!-- Package ID, Quota, Price -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="create_package_id" class="mb-2.5 block text-sm font-semibold text-slate-800 dark:text-white">Paket</label>
                            <select 
                                id="create_package_id" 
                                name="package_id"
                                x-model="selectedOrder.package_id"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            >
                                <option value="">Pilih Paket...</option>
                                <template x-for="pkg in packages" :key="pkg.id">
                                    <option :value="pkg.id" x-text="pkg.name" :selected="pkg.id == selectedOrder.package_id"></option>
                                </template>
                            </select>
                            <template x-if="errors.package_id">
                                <span class="text-xs text-rose-500 mt-1 block" x-text="errors.package_id[0]"></span>
                            </template>
                        </div>
                        <div>
                            <label for="create_quota" class="mb-2.5 block text-sm font-semibold text-slate-800 dark:text-white">Kuota Undangan <span class="text-rose-500">*</span></label>
                            <input 
                                type="number" 
                                id="create_quota" 
                                name="quota"
                                value="{{ old('quota', 1) }}"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <template x-if="errors.quota">
                                <span class="text-xs text-rose-500 mt-1 block" x-text="errors.quota[0]"></span>
                            </template>
                        </div>
                        <div>
                            <label for="create_price" class="mb-2.5 block text-sm font-semibold text-slate-800 dark:text-white">Harga (Rp) <span class="text-rose-500">*</span></label>
                            <input 
                                type="number" 
                                id="create_price" 
                                name="price"
                                value="{{ old('price', 0) }}"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <template x-if="errors.price">
                                <span class="text-xs text-rose-500 mt-1 block" x-text="errors.price[0]"></span>
                            </template>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="create_status" class="mb-2.5 block text-sm font-semibold text-slate-800 dark:text-white">Status Pesanan <span class="text-rose-500">*</span></label>
                        <select 
                            id="create_status" 
                            name="status"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        >
                            <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="follow_up" {{ old('status') == 'follow_up' ? 'selected' : '' }}>Follow Up</option>
                            <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <template x-if="errors.status">
                            <span class="text-xs text-rose-500 mt-1 block" x-text="errors.status[0]"></span>
                        </template>
                    </div>

                    <!-- Start & End Date -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="create_start_date" class="mb-2.5 block text-sm font-semibold text-slate-800 dark:text-white">Tanggal Mulai</label>
                            <input 
                                type="date" 
                                id="create_start_date" 
                                name="start_date"
                                value="{{ old('start_date') }}"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <template x-if="errors.start_date">
                                <span class="text-xs text-rose-500 mt-1 block" x-text="errors.start_date[0]"></span>
                            </template>
                        </div>
                        <div>
                            <label for="create_end_date" class="mb-2.5 block text-sm font-semibold text-slate-800 dark:text-white">Tanggal Berakhir</label>
                            <input 
                                type="date" 
                                id="create_end_date" 
                                name="end_date"
                                value="{{ old('end_date') }}"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <template x-if="errors.end_date">
                                <span class="text-xs text-rose-500 mt-1 block" x-text="errors.end_date[0]"></span>
                            </template>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="create_notes" class="mb-2.5 block text-sm font-semibold text-slate-800 dark:text-white">Catatan Tambahan</label>
                        <textarea 
                            id="create_notes" 
                            name="notes"
                            rows="3"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        >{{ old('notes') }}</textarea>
                        <template x-if="errors.notes">
                            <span class="text-xs text-rose-500 mt-1 block" x-text="errors.notes[0]"></span>
                        </template>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 dark:border-slate-800 pt-4">
                    <button 
                        type="button"
                        @click="closeCreateModal()" 
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit"
                        :disabled="loading"
                        class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg x-show="loading" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 3. Edit Order Modal -->
    <div 
        x-show="editModalOpen"
        class="fixed inset-0 z-999 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        x-transition
        x-cloak
    >
        <div 
            @click.outside="closeEditModal()"
            class="w-full max-w-xl rounded-2xl bg-white p-6 shadow-2xl dark:bg-slate-950 border border-slate-200 dark:border-slate-800"
        >
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4 mb-4">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Edit Pesanan: <span x-text="selectedOrder.order_number" class="text-indigo-600 dark:text-indigo-400"></span></h3>
                <button @click="closeEditModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="edit-order-form" :action="'/admin/orders/' + selectedOrder.id" method="POST" @submit.prevent="submitForm($event, 'edit')">
                @csrf
                @method('PUT')
                <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-1">
                    <!-- Customer Name -->
                    <div>
                        <label for="edit_customer_name" class="mb-2.5 block text-sm font-semibold text-slate-800 dark:text-white">Nama Pelanggan <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            id="edit_customer_name" 
                            name="customer_name"
                            x-model="selectedOrder.customer_name"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                        <template x-if="errors.customer_name">
                            <span class="text-xs text-rose-500 mt-1 block" x-text="errors.customer_name[0]"></span>
                        </template>
                    </div>

                    <!-- Email & Phone -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_email" class="mb-2.5 block text-sm font-semibold text-slate-800 dark:text-white">Email <span class="text-rose-500">*</span></label>
                            <input 
                                type="email" 
                                id="edit_email" 
                                name="email"
                                x-model="selectedOrder.email"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <template x-if="errors.email">
                                <span class="text-xs text-rose-500 mt-1 block" x-text="errors.email[0]"></span>
                            </template>
                        </div>
                        <div>
                            <label for="edit_phone" class="mb-2.5 block text-sm font-semibold text-slate-800 dark:text-white">Nomor Telepon <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                id="edit_phone" 
                                name="phone"
                                x-model="selectedOrder.phone"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <template x-if="errors.phone">
                                <span class="text-xs text-rose-500 mt-1 block" x-text="errors.phone[0]"></span>
                            </template>
                        </div>
                    </div>

                    <!-- Package ID, Quota, Price -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="edit_package_id" class="mb-2.5 block text-sm font-semibold text-slate-800 dark:text-white">Paket</label>
                            <select 
                                id="edit_package_id" 
                                name="package_id"
                                x-model="selectedOrder.package_id"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            >
                                <option value="">Pilih Paket...</option>
                                <template x-for="pkg in packages" :key="pkg.id">
                                    <option :value="pkg.id" x-text="pkg.name" :selected="pkg.id == selectedOrder.package_id"></option>
                                </template>
                            </select>
                            <template x-if="errors.package_id">
                                <span class="text-xs text-rose-500 mt-1 block" x-text="errors.package_id[0]"></span>
                            </template>
                        </div>
                        <div>
                            <label for="edit_quota" class="mb-2.5 block text-sm font-semibold text-slate-800 dark:text-white">Kuota Undangan <span class="text-rose-500">*</span></label>
                            <input 
                                type="number" 
                                id="edit_quota" 
                                name="quota"
                                x-model="selectedOrder.quota"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <template x-if="errors.quota">
                                <span class="text-xs text-rose-500 mt-1 block" x-text="errors.quota[0]"></span>
                            </template>
                        </div>
                        <div>
                            <label for="edit_price" class="mb-2.5 block text-sm font-semibold text-slate-800 dark:text-white">Harga (Rp) <span class="text-rose-500">*</span></label>
                            <input 
                                type="number" 
                                id="edit_price" 
                                name="price"
                                x-model="selectedOrder.price"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <template x-if="errors.price">
                                <span class="text-xs text-rose-500 mt-1 block" x-text="errors.price[0]"></span>
                            </template>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="edit_status" class="mb-2.5 block text-sm font-semibold text-slate-800 dark:text-white">Status Pesanan <span class="text-rose-500">*</span></label>
                        <select 
                            id="edit_status" 
                            name="status"
                            x-model="selectedOrder.status"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        >
                            <option value="pending">Pending</option>
                            <option value="follow_up">Follow Up</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="active">Active</option>
                            <option value="expired">Expired</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        <template x-if="errors.status">
                            <span class="text-xs text-rose-500 mt-1 block" x-text="errors.status[0]"></span>
                        </template>
                    </div>

                    <!-- Start & End Date -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_start_date" class="mb-2.5 block text-sm font-semibold text-slate-800 dark:text-white">Tanggal Mulai</label>
                            <input 
                                type="date" 
                                id="edit_start_date" 
                                name="start_date"
                                x-model="selectedOrder.start_date"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <template x-if="errors.start_date">
                                <span class="text-xs text-rose-500 mt-1 block" x-text="errors.start_date[0]"></span>
                            </template>
                        </div>
                        <div>
                            <label for="edit_end_date" class="mb-2.5 block text-sm font-semibold text-slate-800 dark:text-white">Tanggal Berakhir</label>
                            <input 
                                type="date" 
                                id="edit_end_date" 
                                name="end_date"
                                x-model="selectedOrder.end_date"
                                class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                            />
                            <template x-if="errors.end_date">
                                <span class="text-xs text-rose-500 mt-1 block" x-text="errors.end_date[0]"></span>
                            </template>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="edit_notes" class="mb-2.5 block text-sm font-semibold text-slate-800 dark:text-white">Catatan Tambahan</label>
                        <textarea 
                            id="edit_notes" 
                            name="notes"
                            x-model="selectedOrder.notes"
                            rows="3"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        ></textarea>
                        <template x-if="errors.notes">
                            <span class="text-xs text-rose-500 mt-1 block" x-text="errors.notes[0]"></span>
                        </template>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 dark:border-slate-800 pt-4">
                    <button 
                        type="button"
                        @click="closeEditModal()" 
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit"
                        :disabled="loading"
                        class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg x-show="loading" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 4. Aktifkan Masa Layanan Modal -->
    <div 
        x-show="activateModalOpen"
        class="fixed inset-0 z-999 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        x-transition
        x-cloak
    >
        <div 
            @click.outside="activateModalOpen = false"
            class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl dark:bg-slate-950 border border-slate-200 dark:border-slate-800"
        >
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4 mb-4">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Aktifkan Masa Layanan</h3>
                <button @click="activateModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
                Tentukan masa aktif untuk pesanan <strong x-text="selectedOrder.order_number"></strong> atas nama <strong x-text="selectedOrder.customer_name"></strong>.
            </p>

            <form :action="'/admin/orders/' + selectedOrder.id + '/activate'" method="POST" @submit.prevent="submitForm($event, 'activate')">
                @csrf
                <div class="space-y-4">
                    <!-- Start Date -->
                    <div>
                        <label for="activate_start_date" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-white">Tanggal Mulai <span class="text-rose-500">*</span></label>
                        <input 
                            type="date" 
                            id="activate_start_date" 
                            name="start_date"
                            x-model="selectedOrder.start_date"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                        <template x-if="errors.start_date">
                            <span class="text-xs text-rose-500 mt-1 block" x-text="errors.start_date[0]"></span>
                        </template>
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="activate_end_date" class="mb-2 block text-sm font-semibold text-slate-800 dark:text-white">Tanggal Berakhir <span class="text-rose-500">*</span></label>
                        <input 
                            type="date" 
                            id="activate_end_date" 
                            name="end_date"
                            x-model="selectedOrder.end_date"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white"
                        />
                        <template x-if="errors.end_date">
                            <span class="text-xs text-rose-500 mt-1 block" x-text="errors.end_date[0]"></span>
                        </template>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 dark:border-slate-800 pt-4">
                    <button 
                        type="button"
                        @click="activateModalOpen = false" 
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit"
                        :disabled="loading"
                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg x-show="loading" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Aktifkan Layanan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 5. Delete Order Confirmation Modal -->
    <div 
        x-show="deleteModalOpen"
        class="fixed inset-0 z-999 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        x-transition
        x-cloak
    >
        <div 
            @click.outside="deleteModalOpen = false"
            class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl dark:bg-slate-950 border border-slate-200 dark:border-slate-800"
        >
            <div class="flex items-center gap-3 text-rose-600 dark:text-rose-500 mb-4">
                <div class="h-10 w-10 rounded-full bg-rose-50 dark:bg-rose-950/50 flex items-center justify-center">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Konfirmasi Hapus</h3>
            </div>

            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                Apakah Anda yakin ingin menghapus data pesanan <strong x-text="selectedOrder.order_number"></strong>? Tindakan ini permanen dan tidak dapat dibatalkan.
            </p>

            <form :action="'/admin/orders/' + selectedOrder.id" method="POST" @submit.prevent="submitForm($event, 'delete')">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3 border-t border-slate-100 dark:border-slate-800 pt-4">
                    <button 
                        type="button"
                        @click="deleteModalOpen = false" 
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit"
                        :disabled="loading"
                        class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg x-show="loading" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Hapus Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 6. User Created Success Modal -->
    @if(session('user_credentials'))
    <div 
        x-data="{ open: true }" 
        x-show="open" 
        class="fixed inset-0 z-99999 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        x-transition
        x-cloak
    >
        <div 
            @click.outside="open = false" 
            class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl dark:bg-slate-950 border border-slate-200 dark:border-slate-800"
        >
            <div class="flex items-center gap-3 text-emerald-600 dark:text-emerald-400 mb-4">
                <div class="h-10 w-10 rounded-full bg-emerald-50 dark:bg-emerald-950/50 flex items-center justify-center">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Akun Pelanggan Berhasil Dibuat!</h3>
            </div>
            
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                Kredensial login berikut telah dibuat untuk pesanan <strong>{{ session('user_credentials.order_number') }}</strong>. Silakan salin atau kirimkan langsung kepada pelanggan melalui WhatsApp.
            </p>
            
            <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800/60 mb-6 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400 font-medium">Nama Pelanggan:</span>
                    <span class="text-slate-800 dark:text-white font-semibold">{{ session('user_credentials.name') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400 font-medium">Email / Username:</span>
                    <span class="text-slate-800 dark:text-white font-semibold select-all">{{ session('user_credentials.email') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400 font-medium">Password Sementara:</span>
                    <span class="text-slate-855 dark:text-white font-semibold font-mono select-all bg-indigo-50 dark:bg-indigo-950/40 px-2 py-0.5 rounded">{{ session('user_credentials.password') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400 font-medium">No. Telepon:</span>
                    <span class="text-slate-800 dark:text-white font-semibold">{{ session('user_credentials.phone') }}</span>
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-100 dark:border-slate-800 pt-4">
                <button 
                    @click="open = false" 
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800"
                >
                    Tutup
                </button>
                @php
                    $waMsg = "Halo " . session('user_credentials.name') . ",\n\nAkun Teman Seakad Anda telah berhasil dibuat untuk pesanan " . session('user_credentials.order_number') . ".\n\nBerikut detail login Anda:\n- Email/Username: " . session('user_credentials.email') . "\n- Password Sementara: " . session('user_credentials.password') . "\n\nSilakan masuk ke dashboard Anda melalui tautan berikut:\n" . route('login') . "\n\nTerima kasih!";
                    $waSendUrl = "https://api.whatsapp.com/send?phone=" . session('user_credentials.phone') . "&text=" . rawurlencode($waMsg);
                @endphp
                <a 
                    href="{{ $waSendUrl }}" 
                    target="_blank"
                    @click="open = false"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 transition duration-150"
                >
                    <svg class="h-4.5 w-4.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.725 1.451 5.405.002 9.803-4.381 9.805-9.768.001-2.61-1.01-5.064-2.848-6.903C16.48 2.095 14.037 1.08 11.44 1.08c-5.408 0-9.807 4.382-9.809 9.771-.001 1.905.531 3.766 1.54 5.395l-.994 3.633 3.73-.977c1.518.826 3.033 1.254 4.15 1.254zm11.758-6.844c-.347-.174-2.055-1.014-2.373-1.13-.318-.116-.549-.174-.78.174-.231.349-.896 1.13-1.098 1.362-.202.231-.404.261-.751.087-.347-.173-1.464-.54-2.79-1.723-1.03-.919-1.725-2.054-1.927-2.4-.202-.347-.022-.534.152-.707.157-.156.347-.406.52-.609.174-.203.231-.348.347-.58.116-.232.058-.435-.029-.609-.087-.174-.78-1.884-1.068-2.58-.28-.677-.566-.584-.78-.596-.201-.01-.433-.012-.664-.012-.231 0-.607.087-.924.435-.318.348-1.214 1.189-1.214 2.902 0 1.713 1.243 3.364 1.417 3.595.174.231 2.446 3.734 5.925 5.239.827.357 1.472.57 1.977.73.832.264 1.587.227 2.185.138.667-.1 2.055-.839 2.344-1.65.289-.812.289-1.507.202-1.651-.087-.145-.318-.232-.665-.406z"/>
                    </svg>
                    Kirim ke WhatsApp
                </a>
            </div>
        </div>
    </div>
    @endif
@endsection
