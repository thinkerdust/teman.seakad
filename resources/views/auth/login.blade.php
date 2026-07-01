<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Teman Seakad Admin</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS Assets -->
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
    @vite(['resources/css/admin.css'])
</head>
<body class="bg-slate-50 font-sans text-slate-600 antialiased dark:bg-slate-950 dark:text-slate-400">

    <div class="flex min-h-screen items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-md">
            
            <!-- Brand Logo / Title -->
            <div class="mb-8 text-center">
                <div class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-md shadow-indigo-200 dark:shadow-none">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21l-8.25-7.5A5.25 5.25 0 0112 5.25a5.25 5.25 0 018.25 8.25L12 21z" />
                    </svg>
                </div>
                <h1 class="mt-4 text-2xl font-bold tracking-tight text-slate-800 dark:text-white">
                    Selamat Datang Kembali
                </h1>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    Masuk ke admin panel Teman Seakad
                </p>
            </div>

            <!-- Login Card -->
            <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                
                <!-- Display session success messages -->
                @if(session('success'))
                    <div class="mb-6">
                        <div class="rounded-lg bg-emerald-50 p-4 text-sm font-medium text-emerald-800 dark:bg-emerald-950/20 dark:text-emerald-300">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                <!-- Validation Errors Alert -->
                @if($errors->any())
                    <div class="mb-6">
                        <div class="rounded-lg bg-rose-50 p-4 text-sm font-medium text-rose-800 dark:bg-rose-950/20 dark:text-rose-300">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <!-- Email Input -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                            Alamat Email
                        </label>
                        <div class="mt-2">
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                autocomplete="email" 
                                required 
                                value="{{ old('email') }}"
                                placeholder="nama@email.com"
                                class="block w-full rounded-xl border border-slate-200 bg-transparent px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white dark:placeholder-slate-500 dark:focus:border-indigo-400"
                            >
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Password
                            </label>
                            <a href="{{ route('password.request') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                                Lupa password?
                            </a>
                        </div>
                        <div class="mt-2">
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                autocomplete="current-password" 
                                required
                                placeholder="••••••••"
                                class="block w-full rounded-xl border border-slate-200 bg-transparent px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-800 dark:text-white dark:placeholder-slate-500 dark:focus:border-indigo-400"
                            >
                        </div>
                    </div>

                    <!-- Remember Me Box -->
                    <div class="mb-6 flex items-center">
                        <input 
                            id="remember" 
                            name="remember" 
                            type="checkbox"
                            class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-800 dark:bg-slate-950"
                        >
                        <label for="remember" class="ml-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                            Ingat saya di perangkat ini
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button 
                            type="submit"
                            class="flex w-full justify-center rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition duration-150"
                        >
                            Masuk
                        </button>
                    </div>
                </form>

            </div>

            <!-- Footer info -->
            <p class="mt-8 text-center text-xs text-slate-400">
                &copy; {{ date('Y') }} Teman Seakad. All rights reserved.
            </p>

        </div>
    </div>

</body>
</html>
