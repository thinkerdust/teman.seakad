<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Akses Ditolak | Teman Seakad</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    @vite(['resources/css/admin.css'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(1.5deg); }
        }
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
        .glow-blob {
            filter: blur(80px);
        }
    </style>
</head>
<body class="h-full bg-slate-950 text-slate-100 flex items-center justify-center p-4 overflow-hidden relative">
    
    <!-- Ambient glowing backgrounds -->
    <div class="glow-blob absolute top-1/4 left-1/4 w-96 h-96 rounded-full bg-indigo-600/10 pointer-events-none"></div>
    <div class="glow-blob absolute bottom-1/4 right-1/4 w-96 h-96 rounded-full bg-rose-600/10 pointer-events-none"></div>

    <!-- Main Container -->
    <div class="max-w-md w-full text-center relative z-10">
        
        <!-- Animated 403 Icon / Card -->
        <div class="inline-block animate-float mb-8">
            <div class="relative flex items-center justify-center w-28 h-28 rounded-3xl bg-slate-900 border border-slate-800 shadow-2xl mx-auto">
                <svg class="h-14 w-14 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">Akses Ditolak</h1>
        
        <p class="mt-4 text-slate-400 text-sm leading-relaxed max-w-sm mx-auto">
            {{ $exception->getMessage() ?: 'Maaf, Anda tidak memiliki hak akses atau izin yang cukup untuk membuka halaman ini.' }}
        </p>

        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a 
                href="{{ auth()->check() ? route('admin.dashboard') : url('/') }}" 
                class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold shadow-lg shadow-indigo-600/20 transition duration-150 active:scale-95 cursor-pointer"
            >
                Kembali ke Dashboard
            </a>
            
            <a 
                href="javascript:history.back()" 
                class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 font-semibold transition duration-150 cursor-pointer"
            >
                Kembali Sebelumnya
            </a>
        </div>
        
        <p class="mt-16 text-xs text-slate-600 dark:text-slate-650">
            Teman Seakad &copy; {{ date('Y') }}
        </p>
    </div>

</body>
</html>
