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
