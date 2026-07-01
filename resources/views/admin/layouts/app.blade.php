<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Teman Seakad Admin</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS & JS Assets -->
    <script>
        // Sinkronkan tema gelap dari localStorage sebelum page load untuk menghindari flash
        try {
            const darkMode = localStorage.getItem('_x_darkMode');
            if (darkMode === 'true') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        } catch (e) {
            console.error('Gagal memuat tema gelap', e);
        }
    </script>
    @vite(['resources/css/admin.css', 'resources/js/admin/app.js'])
    
    @stack('styles')
</head>
<body class="bg-slate-50 font-sans text-slate-600 antialiased dark:bg-slate-950 dark:text-slate-400">

    <!-- Page Wrapper -->
    <div 
        x-data="{ darkMode: $persist(false), sidebarToggle: false }" 
        x-init="
            $watch('darkMode', val => {
                if (val) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            });
            if (darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        "
        :class="{ 'dark': darkMode }"
        class="flex h-screen overflow-hidden"
    >
        <!-- Sidebar layout -->
        @include('admin.layouts.sidebar')

        <!-- Content Area -->
        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            <!-- Header layout -->
            @include('admin.layouts.header')

            <!-- Main Content -->
            <main class="flex-grow p-4 md:p-6 2xl:p-10">
                
                <!-- Display Alert Notifications -->
                <div id="flash-alerts-container">
                    @if(session('success'))
                        <div class="mb-6">
                            <x-admin.alert type="success" :message="session('success')" />
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6">
                            <x-admin.alert type="error" :message="session('error')" />
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="mb-6">
                            <x-admin.alert type="warning" :message="session('warning')" />
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="mb-6">
                            <x-admin.alert type="info" :message="session('info')" />
                        </div>
                    @endif
                </div>

                <!-- Yield Content -->
                @yield('content')
                
            </main>
            <!-- Main Content -->
            
            <!-- Footer -->
            <footer class="bg-white py-4 text-center text-xs text-slate-400 border-t border-slate-200 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-500">
                <p>&copy; {{ date('Y') }} Teman Seakad. All rights reserved.</p>
            </footer>
        </div>
        <!-- Content Area -->
    </div>
    <!-- Page Wrapper -->

    @stack('scripts')
</body>
</html>
