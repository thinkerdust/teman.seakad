@extends('admin.layouts.app')

@section('title', 'Laporan Transaksi')

@section('content')
    <!-- Breadcrumb -->
    <x-admin.breadcrumb pageTitle="Laporan Transaksi" :items="['Laporan Transaksi' => '']" />

    <!-- Main Container -->
    <div class="space-y-6">
        <!-- Table & Filter Card -->
        <x-admin.card>
            <x-slot:header>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                            Laporan Transaksi
                        </h3>
                        <p class="text-xs text-slate-400 dark:text-slate-400 mt-0.5">
                            Menampilkan ringkasan seluruh transaksi dan status paket undangan.
                        </p>
                    </div>
                </div>
            </x-slot:header>

            <!-- Filters -->
            <form action="{{ route('admin.reports.transactions') }}" method="GET" class="mb-6 space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
                    <!-- Search Input -->
                    <div class="relative">
                        <label for="search" class="sr-only">Cari...</label>
                        <input 
                            type="text" 
                            id="search"
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Cari No. Order / Pelanggan..."
                            class="w-full rounded-xl border border-slate-200 bg-transparent py-2.5 pl-10 pr-4 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white dark:bg-slate-900"
                        />
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                    </div>

                    <!-- Date From -->
                    <div>
                        <input 
                            type="date" 
                            name="date_from" 
                            value="{{ request('date_from') }}"
                            placeholder="Mulai Tanggal"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white dark:bg-slate-900"
                            title="Tanggal Mulai (Dari)"
                        />
                    </div>

                    <!-- Date To -->
                    <div>
                        <input 
                            type="date" 
                            name="date_to" 
                            value="{{ request('date_to') }}"
                            placeholder="Sampai Tanggal"
                            class="w-full rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white dark:bg-slate-900"
                            title="Tanggal Mulai (Sampai)"
                        />
                    </div>

                    <!-- Package Filter -->
                    <div>
                        <select 
                            name="package_id" 
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-900 dark:text-white"
                        >
                            <option value="">Semua Paket</option>
                            @foreach($packages as $pkg)
                                <option value="{{ $pkg->id }}" {{ request('package_id') == $pkg->id ? 'selected' : '' }}>
                                    {{ $pkg->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <select 
                            name="status" 
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-900 dark:text-white"
                        >
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="follow_up" {{ request('status') === 'follow_up' ? 'selected' : '' }}>Follow Up</option>
                            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <!-- Period Filter -->
                    <div>
                        <select 
                            name="period" 
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-900 dark:text-white"
                        >
                            <option value="">Semua Periode</option>
                            <option value="active" {{ request('period') === 'active' ? 'selected' : '' }}>Aktif (Dalam Periode)</option>
                            <option value="expired" {{ request('period') === 'expired' ? 'selected' : '' }}>Berakhir (Lewat Periode)</option>
                        </select>
                    </div>

                    <!-- Filter & Reset Buttons -->
                    <div class="flex items-center gap-2 lg:col-span-2">
                        <button 
                            type="submit"
                            class="inline-flex flex-grow items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition duration-150"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'date_from', 'date_to', 'package_id', 'status', 'period']))
                            <a 
                                href="{{ route('admin.reports.transactions') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-transparent px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition duration-150 dark:border-slate-850 dark:text-slate-300 dark:hover:bg-slate-900/50"
                            >
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            <!-- Datatable -->
            <div class="overflow-x-auto rounded-xl border border-slate-100 dark:border-slate-850">
                <table class="w-full border-collapse text-left text-sm text-slate-600 dark:text-slate-400">
                    <thead class="bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wider dark:bg-slate-900/50 dark:text-slate-400">
                        <tr>
                            <th class="px-6 py-4 w-12 text-center">#</th>
                            <th class="px-6 py-4">No. Order</th>
                            <th class="px-6 py-4">Pelanggan</th>
                            <th class="px-6 py-4">Paket</th>
                            <th class="px-6 py-4 text-center">Kuota</th>
                            <th class="px-6 py-4">Tgl. Mulai</th>
                            <th class="px-6 py-4">Tgl. Akhir</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/20">
                                <td class="px-6 py-4 text-center text-slate-500 font-medium">
                                    {{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-800 dark:text-white">
                                    {{ $order->order_number }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-900 dark:text-white">{{ $order->customer_name }}</div>
                                    <div class="text-xs text-slate-400 dark:text-slate-400">{{ $order->email }}</div>
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-800 dark:text-white">
                                    {{ $order->package ? $order->package->name : 'Custom / Tanpa Paket' }}
                                </td>
                                <td class="px-6 py-4 text-center font-semibold text-slate-800 dark:text-white">
                                    {{ $order->quota }}
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-800 dark:text-white">
                                    {{ $order->start_date ? $order->start_date->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-800 dark:text-white">
                                    {{ $order->end_date ? $order->end_date->format('d M Y') : '-' }}
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <svg class="h-8 w-8 text-slate-300 dark:text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="text-sm font-medium">Tidak ada data transaksi yang ditemukan.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        </x-admin.card>
    </div>
@endsection
