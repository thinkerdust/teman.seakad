<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Undangan Tidak Tersedia | Teman Seakad</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <!-- CSS Assets -->
    @vite(['resources/css/admin.css'])
</head>
<body class="bg-slate-50 font-sans text-slate-600 antialiased dark:bg-slate-950 dark:text-slate-400" style="font-family: 'Instrument Sans', sans-serif;">

    <div class="flex min-h-screen flex-col items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-md text-center">
            
            <!-- Icon / Warning -->
            <div class="mb-6">
                <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 dark:bg-amber-950/20 dark:text-amber-400">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.249-8.25-3.286zm0 13.036h.008v.008H12v-.008z" />
                    </svg>
                </div>
            </div>

            <!-- Title & Message -->
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-800 dark:text-white sm:text-4xl" style="font-family: 'Instrument Sans', sans-serif;">
                Undangan Tidak Tersedia
            </h1>
            
            <p class="mt-4 text-base text-slate-500 dark:text-slate-400">
                Undangan sudah tidak tersedia
            </p>

            <div class="mt-8">
                <a 
                    href="{{ route('landing') }}" 
                    class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-indigo-200 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:shadow-none transition-colors"
                >
                    Kembali ke Beranda
                </a>
            </div>

            <div class="mt-12 text-xs text-slate-400">
                &copy; {{ date('Y') }} Teman Seakad. All rights reserved.
            </div>
            
        </div>
    </div>

</body>
</html>
