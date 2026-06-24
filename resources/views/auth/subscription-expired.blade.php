<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masa Aktif Berakhir | Teman Seakad</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS Assets -->
    @vite(['resources/css/admin.css'])
</head>
<body class="bg-slate-50 font-sans text-slate-600 antialiased dark:bg-slate-950 dark:text-slate-400">

    <div class="flex min-h-screen items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-md">
            
            <!-- Brand Logo / Title -->
            <div class="mb-8 text-center">
                <div class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-rose-600 text-white shadow-md shadow-rose-200 dark:shadow-none">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h1 class="mt-4 text-2xl font-bold tracking-tight text-slate-800 dark:text-white">
                    Layanan Tertangguh
                </h1>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    Akses Dibatasi
                </p>
            </div>

            <!-- Card -->
            <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900 text-center">
                
                <div class="mb-6 text-slate-700 dark:text-slate-300">
                    <p class="text-base font-medium">
                        Masa aktif akun Anda sudah berakhir, silahkan melakukan perpanjangan
                    </p>
                </div>

                <div class="mt-8 flex flex-col space-y-3">
                    <!-- WhatsApp Admin Button for Renewal -->
                    <a 
                        href="https://api.whatsapp.com/send?phone=628123456789&text=Halo%20Admin%20Teman%20Seakad,%20saya%20ingin%20memperpanjang%20masa%20aktif%20langganan%20saya." 
                        target="_blank" 
                        class="flex w-full items-center justify-center rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-md shadow-indigo-200 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:shadow-none"
                    >
                        Hubungi Admin untuk Perpanjang
                    </a>

                    <!-- Logout Form -->
                    <form action="{{ route('admin.logout') }}" method="POST" class="w-full">
                        @csrf
                        <button 
                            type="submit" 
                            class="flex w-full items-center justify-center rounded-xl border border-slate-200 bg-transparent px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 dark:border-slate-800 dark:text-slate-300 dark:hover:bg-slate-800"
                        >
                            Keluar / Logout
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-8 text-center text-xs text-slate-400">
                &copy; {{ date('Y') }} Teman Seakad. Hak cipta dilindungi undang-undang.
            </div>
            
        </div>
    </div>

</body>
</html>
