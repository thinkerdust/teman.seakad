@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Breadcrumb -->
    <x-admin.breadcrumb pageTitle="Dashboard" />

    <!-- Stat Cards Grid -->
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 {{ auth()->user()->hasPermission('user.view') ? 'lg:grid-cols-4' : 'lg:grid-cols-3' }} md:gap-6">
        
        @if(auth()->user()->hasPermission('user.view'))
        <!-- Stat Card: Users -->
        <x-admin.stat-card 
            title="Total Pengguna" 
            value="{{ $stats['total_users'] }}" 
            trend="{{ $stats['user_trend'] }}" 
            :trendUp="!str_contains($stats['user_trend'], '-')"
        >
            <x-slot:iconSlot>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </x-slot:iconSlot>
        </x-admin.stat-card>
        @endif

        <!-- Stat Card: Invitations -->
        <x-admin.stat-card 
            title="Total Undangan" 
            value="{{ $stats['total_invitations'] }}" 
            trend="{{ $stats['invitation_trend'] }}" 
            :trendUp="!str_contains($stats['invitation_trend'], '-')"
        >
            <x-slot:iconSlot>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 19v-8.93a2 2 0 01.89-1.664l8-5.333a2 2 0 012.22 0l8 5.333A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-2.25-1.5a2 2 0 00-2.22 0l-2.25 1.5M12 14v4h-.01" />
                </svg>
            </x-slot:iconSlot>
        </x-admin.stat-card>

        @if(auth()->user()->hasPermission('theme.view'))
        <!-- Stat Card: Themes -->
        <x-admin.stat-card 
            title="Tema Undangan" 
            value="{{ $stats['total_themes'] }}" 
            trend="0%" 
            :trendUp="true"
        >
            <x-slot:iconSlot>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </x-slot:iconSlot>
        </x-admin.stat-card>
        @endif

        <!-- Stat Card: Guests -->
        <x-admin.stat-card 
            title="Tamu RSVP" 
            value="{{ $stats['total_guests'] }}" 
            trend="{{ $stats['guest_trend'] }}" 
            :trendUp="!str_contains($stats['guest_trend'], '-')"
        >
            <x-slot:iconSlot>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </x-slot:iconSlot>
        </x-admin.stat-card>

    </div>

    @if(auth()->user()->hasPermission('user.view'))
        <!-- Subscription Statistics -->
        <h3 class="mb-4 text-lg font-bold text-slate-800 dark:text-white">Statistik Langganan</h3>
        <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 md:gap-6">
            <!-- Active Subscriptions -->
            <x-admin.stat-card 
                title="Langganan Aktif" 
                value="{{ $stats['total_active_subscriptions'] }}" 
            >
                <x-slot:iconSlot>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </x-slot:iconSlot>
            </x-admin.stat-card>

            <!-- Expired Subscriptions -->
            <x-admin.stat-card 
                title="Langganan Berakhir" 
                value="{{ $stats['total_expired_subscriptions'] }}" 
            >
                <x-slot:iconSlot>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </x-slot:iconSlot>
            </x-admin.stat-card>

            <!-- Expired This Month -->
            <x-admin.stat-card 
                title="Berakhir Bulan Ini" 
                value="{{ $stats['expired_this_month'] }}" 
            >
                <x-slot:iconSlot>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </x-slot:iconSlot>
            </x-admin.stat-card>

            <!-- Upcoming Expired -->
            <x-admin.stat-card 
                title="Segera Berakhir (30 Hari)" 
                value="{{ $stats['upcoming_expired'] }}" 
            >
                <x-slot:iconSlot>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </x-slot:iconSlot>
            </x-admin.stat-card>
        </div>

        <!-- Order Statistics -->
        <h3 class="mb-4 text-lg font-bold text-slate-800 dark:text-white">Statistik Order</h3>
        <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 md:gap-6">
            <!-- Total Orders -->
            <x-admin.stat-card 
                title="Total Order" 
                value="{{ $stats['total_orders'] }}" 
            >
                <x-slot:iconSlot>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </x-slot:iconSlot>
            </x-admin.stat-card>

            <!-- Pending Orders -->
            <x-admin.stat-card 
                title="Order Pending" 
                value="{{ $stats['pending_orders'] }}" 
            >
                <x-slot:iconSlot>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </x-slot:iconSlot>
            </x-admin.stat-card>

            <!-- Active Orders -->
            <x-admin.stat-card 
                title="Order Aktif" 
                value="{{ $stats['active_orders'] }}" 
            >
                <x-slot:iconSlot>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </x-slot:iconSlot>
            </x-admin.stat-card>

            <!-- Expired Orders -->
            <x-admin.stat-card 
                title="Order Berakhir" 
                value="{{ $stats['expired_orders'] }}" 
            >
                <x-slot:iconSlot>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </x-slot:iconSlot>
            </x-admin.stat-card>
        </div>

        <!-- Revenue Statistics -->
        <h3 class="mb-4 text-lg font-bold text-slate-800 dark:text-white">Statistik Pendapatan</h3>
        <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6">
            <!-- Total Revenue -->
            <x-admin.stat-card 
                title="Total Pendapatan" 
                value="Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}" 
            >
                <x-slot:iconSlot>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </x-slot:iconSlot>
            </x-admin.stat-card>

            <!-- Monthly Revenue -->
            <x-admin.stat-card 
                title="Pendapatan Bulan Ini" 
                value="Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}" 
            >
                <x-slot:iconSlot>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </x-slot:iconSlot>
            </x-admin.stat-card>
        </div>
    @endif

    <!-- Charts Visualization Grid -->
    <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Chart: Monthly Invitations -->
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-all duration-200 hover:shadow-md">
            <div class="mb-4">
                <h4 class="text-base font-bold text-slate-800 dark:text-white">Undangan Dibuat</h4>
                <p class="text-xs text-slate-400 dark:text-slate-500">Statistik volume pembuatan undangan baru per bulan (6 bulan terakhir)</p>
            </div>
            <div id="chart-invitations" class="min-h-[300px]"></div>
        </div>

        <!-- Chart: Visitor Statistics -->
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-all duration-200 hover:shadow-md">
            <div class="mb-4">
                <h4 class="text-base font-bold text-slate-800 dark:text-white">Statistik Pengunjung</h4>
                <p class="text-xs text-slate-400 dark:text-slate-500">Jumlah kunjungan tamu ke tautan undangan digital pernikahan (14 hari terakhir)</p>
            </div>
            <div id="chart-visitors" class="min-h-[300px]"></div>
        </div>
    </div>

    @if(auth()->user()->hasPermission('user.view'))
    <!-- Admin Charts Visualization Grid -->
    <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Chart: Subscription Growth -->
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-all duration-200 hover:shadow-md">
            <div class="mb-4">
                <h4 class="text-base font-bold text-slate-800 dark:text-white">Pertumbuhan Langganan</h4>
                <p class="text-xs text-slate-400 dark:text-slate-500">Statistik volume pertumbuhan subscription baru per bulan (6 bulan terakhir)</p>
            </div>
            <div id="chart-subscription-growth" class="min-h-[300px]"></div>
        </div>

        <!-- Chart: Order Monthly -->
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-all duration-200 hover:shadow-md">
            <div class="mb-4">
                <h4 class="text-base font-bold text-slate-800 dark:text-white">Order Bulanan</h4>
                <p class="text-xs text-slate-400 dark:text-slate-500">Statistik volume order baru masuk per bulan (6 bulan terakhir)</p>
            </div>
            <div id="chart-order-monthly" class="min-h-[300px]"></div>
        </div>
    </div>

    <!-- Chart: Revenue Monthly (Full Width) -->
    <div class="mb-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-all duration-200 hover:shadow-md">
        <div class="mb-4">
            <h4 class="text-base font-bold text-slate-800 dark:text-white">Pendapatan Bulanan</h4>
            <p class="text-xs text-slate-400 dark:text-slate-500">Total pendapatan dari order terkonfirmasi/aktif per bulan (6 bulan terakhir)</p>
        </div>
        <div id="chart-revenue-monthly" class="min-h-[300px]"></div>
    </div>
    @endif

    <!-- Dashboard Content Area -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        
        <!-- Welcome Card & Quick Actions -->
        <div class="{{ auth()->user()->hasPermission('user.view') ? 'lg:col-span-2' : 'lg:col-span-3' }} space-y-6">
            
            <!-- Greeting Card -->
            <div class="relative overflow-hidden rounded-xl border border-indigo-100 bg-gradient-to-br from-indigo-600 to-violet-700 p-6 text-white shadow-sm dark:border-none">
                <div class="relative z-10">
                    <h3 class="text-xl font-bold md:text-2xl">Selamat Datang di Teman Seakad!</h3>
                    <p class="mt-2 text-sm text-indigo-100 max-w-xl">
                        @if(auth()->user()->hasPermission('user.view'))
                            Aplikasi undangan pernikahan digital premium. Kelola pengguna, rancang tema indah, dan pantau kehadiran tamu undangan pernikahan dengan mudah di sini.
                        @else
                            Aplikasi undangan pernikahan digital premium. Buat undangan pernikahan digital impian Anda, pilih tema elegan, dan undang para tamu dengan mudah di sini.
                        @endif
                    </p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="#" class="rounded-lg bg-white px-4 py-2 text-xs font-semibold text-indigo-700 hover:bg-slate-50 transition duration-150 shadow-sm">
                            Buat Undangan Baru
                        </a>
                        @if(auth()->user()->hasPermission('theme.view'))
                        <a href="#" class="rounded-lg bg-indigo-500/30 border border-indigo-400/30 px-4 py-2 text-xs font-semibold text-white hover:bg-indigo-500/40 transition duration-150">
                            Lihat Template Tema
                        </a>
                        @endif
                    </div>
                </div>
                <!-- Background decoration shapes -->
                <div class="absolute -right-16 -top-16 h-40 w-40 rounded-full bg-white/10 blur-xl"></div>
                <div class="absolute -bottom-20 -left-20 h-48 w-48 rounded-full bg-indigo-500/20 blur-2xl"></div>
            </div>

            <!-- Quick Start Cards -->
            <x-admin.card title="Aktivitas Terbaru">
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <div class="rounded-full bg-slate-100 p-3 dark:bg-slate-800">
                        <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="mt-4 font-semibold text-slate-800 dark:text-white">Belum Ada Aktivitas</h4>
                    <p class="mt-1 text-sm text-slate-400 dark:text-slate-500 max-w-xs">
                        Aktivitas login, pembuatan undangan, dan perubahan tema akan tampil di sini.
                    </p>
                </div>
            </x-admin.card>

        </div>

        @if(auth()->user()->hasPermission('user.view'))
        <!-- Sidebar Widget Column -->
        <div class="space-y-6">
            
            <!-- Quick Info / Status -->
            <x-admin.card title="Status Sistem">
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-slate-500 dark:text-slate-400">Versi Laravel</span>
                            <span class="font-semibold text-slate-800 dark:text-white">12.x</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-slate-100 pt-3 dark:border-slate-800">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-slate-500 dark:text-slate-400">Versi PHP</span>
                            <span class="font-semibold text-slate-800 dark:text-white">8.2+</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-slate-100 pt-3 dark:border-slate-800">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-slate-500 dark:text-slate-400">Database</span>
                            <span class="font-semibold text-slate-800 dark:text-white">MySQL (Docker)</span>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-3 dark:border-slate-800">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-slate-500 dark:text-slate-400">Mode Aplikasi</span>
                            <span class="rounded bg-indigo-50 px-2 py-0.5 text-xs font-semibold text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400">
                                Development
                            </span>
                        </div>
                    </div>
                </div>
            </x-admin.card>

        </div>
        @endif

    </div>
@endsection

@push('scripts')
    <!-- CDN ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($chartData);
            const isDarkInit = document.documentElement.classList.contains('dark');
            const initialTheme = isDarkInit ? 'dark' : 'light';
            const initialGridColor = isDarkInit ? '#1e293b' : '#f1f5f9';
            const initialLabelColor = isDarkInit ? '#94a3b8' : '#64748b';

            // 1. Invitations Created Chart (Bar Chart)
            const optionsInvitations = {
                chart: {
                    type: 'bar',
                    height: 300,
                    toolbar: { show: false },
                    fontFamily: 'Inter, sans-serif',
                    background: 'transparent'
                },
                theme: {
                    mode: initialTheme
                },
                series: [{
                    name: 'Undangan Dibuat',
                    data: chartData.invitations.series
                }],
                xaxis: {
                    categories: chartData.invitations.labels,
                    labels: {
                        style: {
                            colors: initialLabelColor,
                            fontSize: '12px'
                        }
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: initialLabelColor,
                            fontSize: '12px'
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        type: "vertical",
                        shadeIntensity: 0.25,
                        gradientToColors: ['#8b5cf6'], // Violet
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 0.85,
                        stops: [50, 0, 100]
                    }
                },
                colors: ['#6366f1'], // Indigo
                plotOptions: {
                    bar: {
                        borderRadius: 5,
                        columnWidth: '45%',
                        distributed: false
                    }
                },
                grid: {
                    borderColor: initialGridColor,
                    strokeDashArray: 4,
                    padding: { top: 0, right: 0, bottom: 0, left: 10 }
                },
                dataLabels: { enabled: false }
            };

            const chartInvitations = new ApexCharts(document.querySelector("#chart-invitations"), optionsInvitations);
            chartInvitations.render();

            // 2. Visitor Statistics Chart (Area Chart)
            const optionsVisitors = {
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: { show: false },
                    fontFamily: 'Inter, sans-serif',
                    background: 'transparent'
                },
                theme: {
                    mode: initialTheme
                },
                series: [{
                    name: 'Jumlah Kunjungan',
                    data: chartData.visitors.series
                }],
                xaxis: {
                    categories: chartData.visitors.labels,
                    labels: {
                        style: {
                            colors: initialLabelColor,
                            fontSize: '11px'
                        }
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: initialLabelColor,
                            fontSize: '12px'
                        }
                    }
                },
                colors: ['#10b981'], // Emerald
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.45,
                        opacityTo: 0.05,
                        stops: [0, 100]
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                grid: {
                    borderColor: initialGridColor,
                    strokeDashArray: 4,
                    padding: { top: 0, right: 0, bottom: 0, left: 10 }
                },
                dataLabels: { enabled: false },
                markers: {
                    size: 4,
                    colors: ['#10b981'],
                    strokeColors: '#fff',
                    strokeWidth: 2,
                    hover: { size: 6 }
                }
            };

            const chartVisitors = new ApexCharts(document.querySelector("#chart-visitors"), optionsVisitors);
            chartVisitors.render();

            // Phase 21: Admin Charts Initialization
            const growthEl = document.querySelector("#chart-subscription-growth");
            const orderEl = document.querySelector("#chart-order-monthly");
            const revenueEl = document.querySelector("#chart-revenue-monthly");

            let chartSubscriptionGrowth;
            if (growthEl) {
                const optionsSubscriptionGrowth = {
                    chart: {
                        type: 'line',
                        height: 300,
                        toolbar: { show: false },
                        fontFamily: 'Inter, sans-serif',
                        background: 'transparent'
                    },
                    theme: { mode: initialTheme },
                    series: [{
                        name: 'Subscription Baru',
                        data: chartData.subscriptionGrowth.series
                    }],
                    xaxis: {
                        categories: chartData.subscriptionGrowth.labels,
                        labels: {
                            style: {
                                colors: initialLabelColor,
                                fontSize: '12px'
                            }
                        },
                        axisBorder: { show: false },
                        axisTicks: { show: false }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: initialLabelColor,
                                fontSize: '12px'
                            }
                        }
                    },
                    colors: ['#06b6d4'], // Cyan
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    grid: {
                        borderColor: initialGridColor,
                        strokeDashArray: 4,
                        padding: { top: 0, right: 0, bottom: 0, left: 10 }
                    },
                    dataLabels: { enabled: false },
                    markers: {
                        size: 4,
                        colors: ['#06b6d4'],
                        strokeColors: '#fff',
                        strokeWidth: 2,
                        hover: { size: 6 }
                    }
                };
                chartSubscriptionGrowth = new ApexCharts(growthEl, optionsSubscriptionGrowth);
                chartSubscriptionGrowth.render();
            }

            let chartOrderMonthly;
            if (orderEl) {
                const optionsOrderMonthly = {
                    chart: {
                        type: 'bar',
                        height: 300,
                        toolbar: { show: false },
                        fontFamily: 'Inter, sans-serif',
                        background: 'transparent'
                    },
                    theme: { mode: initialTheme },
                    series: [{
                        name: 'Order Baru',
                        data: chartData.orderMonthly.series
                    }],
                    xaxis: {
                        categories: chartData.orderMonthly.labels,
                        labels: {
                            style: {
                                colors: initialLabelColor,
                                fontSize: '12px'
                            }
                        },
                        axisBorder: { show: false },
                        axisTicks: { show: false }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: initialLabelColor,
                                fontSize: '12px'
                            }
                        }
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'light',
                            type: "vertical",
                            shadeIntensity: 0.25,
                            gradientToColors: ['#f59e0b'], // Amber
                            inverseColors: true,
                            opacityFrom: 0.85,
                            opacityTo: 0.85,
                            stops: [50, 0, 100]
                        }
                    },
                    colors: ['#d97706'], // Orange-ish amber
                    plotOptions: {
                        bar: {
                            borderRadius: 5,
                            columnWidth: '45%',
                            distributed: false
                        }
                    },
                    grid: {
                        borderColor: initialGridColor,
                        strokeDashArray: 4,
                        padding: { top: 0, right: 0, bottom: 0, left: 10 }
                    },
                    dataLabels: { enabled: false }
                };
                chartOrderMonthly = new ApexCharts(orderEl, optionsOrderMonthly);
                chartOrderMonthly.render();
            }

            let chartRevenueMonthly;
            if (revenueEl) {
                const optionsRevenueMonthly = {
                    chart: {
                        type: 'area',
                        height: 300,
                        toolbar: { show: false },
                        fontFamily: 'Inter, sans-serif',
                        background: 'transparent'
                    },
                    theme: { mode: initialTheme },
                    series: [{
                        name: 'Pendapatan',
                        data: chartData.revenueMonthly.series
                    }],
                    xaxis: {
                        categories: chartData.revenueMonthly.labels,
                        labels: {
                            style: {
                                colors: initialLabelColor,
                                fontSize: '12px'
                            }
                        },
                        axisBorder: { show: false },
                        axisTicks: { show: false }
                    },
                    yaxis: {
                        labels: {
                            formatter: function (value) {
                                return "Rp " + new Intl.NumberFormat('id-ID').format(value);
                            },
                            style: {
                                colors: initialLabelColor,
                                fontSize: '12px'
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function (value) {
                                return "Rp " + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    },
                    colors: ['#10b981'], // Emerald
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.45,
                            opacityTo: 0.05,
                            stops: [0, 100]
                        }
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    grid: {
                        borderColor: initialGridColor,
                        strokeDashArray: 4,
                        padding: { top: 0, right: 0, bottom: 0, left: 10 }
                    },
                    dataLabels: { enabled: false },
                    markers: {
                        size: 4,
                        colors: ['#10b981'],
                        strokeColors: '#fff',
                        strokeWidth: 2,
                        hover: { size: 6 }
                    }
                };
                chartRevenueMonthly = new ApexCharts(revenueEl, optionsRevenueMonthly);
                chartRevenueMonthly.render();
            }

            // 3. Dynamic Dark/Light Mode Switcher using MutationObserver
            const updateChartsTheme = (isDark) => {
                const themeMode = isDark ? 'dark' : 'light';
                const gridColor = isDark ? '#334155' : '#f1f5f9';
                const labelColor = isDark ? '#94a3b8' : '#64748b';

                const newOptions = {
                    theme: { mode: themeMode },
                    grid: { borderColor: gridColor },
                    xaxis: {
                        labels: {
                            style: { colors: labelColor }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: { colors: labelColor }
                        }
                    }
                };

                chartInvitations.updateOptions(newOptions);
                chartVisitors.updateOptions(newOptions);
                if (chartSubscriptionGrowth) chartSubscriptionGrowth.updateOptions(newOptions);
                if (chartOrderMonthly) chartOrderMonthly.updateOptions(newOptions);
                if (chartRevenueMonthly) chartRevenueMonthly.updateOptions(newOptions);
            };

            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.attributeName === 'class') {
                        const isDark = document.documentElement.classList.contains('dark');
                        updateChartsTheme(isDark);
                    }
                });
            });

            observer.observe(document.documentElement, { attributes: true });
        });
    </script>
@endpush
